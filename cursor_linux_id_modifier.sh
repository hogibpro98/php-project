#!/bin/bash

# Error handling setup
set -e

# Color definitions
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Log functions
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

log_debug() {
    echo -e "${BLUE}[DEBUG]${NC} $1"
}

# Get current user
get_current_user() {
    if [ "$EUID" -eq 0 ]; then
        echo "$SUDO_USER"
    else
        echo "$USER"
    fi
}

CURRENT_USER=$(get_current_user)
if [ -z "$CURRENT_USER" ]; then
    log_error "Unable to get username"
    exit 1
fi

# Define configuration file path
STORAGE_FILE="$HOME/.config/Cursor/User/globalStorage/storage.json"
BACKUP_DIR="$HOME/.config/Cursor/User/globalStorage/backups"

# Check permissions
check_permissions() {
    if [ "$EUID" -ne 0 ]; then
        log_error "Please run this script with sudo"
        echo "Example: sudo $0"
        exit 1
    fi
}

# Check and kill Cursor process
check_and_kill_cursor() {
    log_info "Checking Cursor process..."
    
    local attempt=1
    local max_attempts=5
    
    # Function: Get process details
    get_process_details() {
        local process_name="$1"
        log_debug "Getting process details for $process_name:"
        ps aux | grep -i "$process_name" | grep -v grep
    }
    
    while [ $attempt -le $max_attempts ]; do
        CURSOR_PIDS=$(pgrep -i "cursor" || true)
        
        if [ -z "$CURSOR_PIDS" ]; then
            log_info "No running Cursor process found"
            return 0
        fi
        
        log_warn "Found running Cursor process"
        get_process_details "cursor"
        
        log_warn "Attempting to close Cursor process..."
        
        if [ $attempt -eq $max_attempts ]; then
            log_warn "Attempting force termination..."
            kill -9 $CURSOR_PIDS 2>/dev/null || true
        else
            kill $CURSOR_PIDS 2>/dev/null || true
        fi
        
        sleep 1
        
        if ! pgrep -i "cursor" > /dev/null; then
            log_info "Cursor process successfully closed"
            return 0
        fi
        
        log_warn "Waiting for process to close, attempt $attempt/$max_attempts..."
        ((attempt++))
    done
    
    log_error "Unable to close Cursor process after $max_attempts attempts"
    get_process_details "cursor"
    log_error "Please close the process manually and try again"
    exit 1
}

# Backup system ID
backup_system_id() {
    log_info "Backing up system ID..."
    local system_id_file="$BACKUP_DIR/system_id.backup_$(date +%Y%m%d_%H%M%S)"
    
    # Create backup directory
    mkdir -p "$BACKUP_DIR"
    
    {
        echo "# Original System ID Backup - $(date)" > "$system_id_file"
        echo "## Machine ID:" >> "$system_id_file"
        cat /etc/machine-id >> "$system_id_file"
        echo -e "\n## DMI System UUID:" >> "$system_id_file"
        dmidecode -s system-uuid >> "$system_id_file" 2>/dev/null || echo "N/A"
        
        chmod 444 "$system_id_file"
        chown "$CURRENT_USER" "$system_id_file"
        log_info "System ID backed up to: $system_id_file"
    } || {
        log_error "Failed to back up system ID"
        return 1
    }
}

# Backup configuration file
backup_config() {
    if [ ! -f "$STORAGE_FILE" ]; then
        log_warn "Configuration file does not exist, skipping backup"
        return 0
    fi
    
    mkdir -p "$BACKUP_DIR"
    local backup_file="$BACKUP_DIR/storage.json.backup_$(date +%Y%m%d_%H%M%S)"
    
    if cp "$STORAGE_FILE" "$backup_file"; then
        chmod 644 "$backup_file"
        chown "$CURRENT_USER" "$backup_file"
        log_info "Configuration backed up to: $backup_file"
    else
        log_error "Backup failed"
        exit 1
    fi
}

# Generate random ID
generate_random_id() {
    # Generate 32 bytes (64 hexadecimal characters) of random data and ensure single line output
    head -c 32 /dev/urandom | xxd -p -c 32
}

# Generate random UUID
generate_uuid() {
    uuidgen | tr '[:upper:]' '[:lower:]'
}

# Modify existing file
modify_or_add_config() {
    local key="$1"
    local value="$2"
    local file="$3"
    
    # Escape special characters
    local key_escaped=$(sed 's/[\/&]/\\&/g' <<< "$key")
    local value_escaped=$(sed 's/[\/&]/\\&/g' <<< "$value")
    
    if [ ! -f "$file" ]; then
        log_error "File does not exist: $file"
        return 1
    fi
    
    # Check and remove chattr readonly attribute (if exists)
    if lsattr "$file" 2>/dev/null | grep -q '^....i'; then
        log_debug "Removing file immutable attribute..."
        sudo chattr -i "$file" || {
            log_error "Unable to remove file immutable attribute"
            return 1
        }
    fi
    
    # Ensure file is writable
    chmod 644 "$file" || {
        log_error "Unable to modify file permissions: $file"
        return 1
    }
    
    # Create temporary file
    local temp_file=$(mktemp)
    
    # Check if key exists
    if grep -q "\"$key\":" "$file"; then
        # Use # as separator to avoid conflicts and escape special characters
        sed "s#\"${key_escaped}\":[[:space:]]*\"[^\"]*\"#\"${key_escaped}\": \"${value_escaped}\"#" "$file" > "$temp_file" || {
            log_error "Failed to modify configuration: $key"
            rm -f "$temp_file"
            return 1
        }
    else
        # Escape special characters when adding new key-value pair
        sed "s/}$/,\n    \"${key_escaped}\": \"${value_escaped}\"\n}/" "$file" > "$temp_file" || {
            log_error "Failed to add configuration: $key"
            rm -f "$temp_file"
            return 1
        }
    fi
    
    # Check if temporary file is empty
    if [ ! -s "$temp_file" ]; then
        log_error "Generated temporary file is empty"
        rm -f "$temp_file"
        return 1
    fi
    
    # Use cat to replace original file content
    cat "$temp_file" > "$file" || {
        log_error "Unable to write to file: $file"
        rm -f "$temp_file"
        return 1
    }
    
    rm -f "$temp_file"
    
    # Restore file permissions
    chmod 444 "$file"
    
    return 0
}

# Generate new configuration
generate_new_config() {
    # Modify system ID
    log_info "Modifying system ID..."
    
    # Backup current system ID
    backup_system_id
    
    # Generate new machine-id
    local new_machine_id=$(generate_random_id | cut -c1-32)
    
    # Backup and modify machine-id
    if [ -f "/etc/machine-id" ]; then
        cp /etc/machine-id /etc/machine-id.backup
        echo "$new_machine_id" > /etc/machine-id
        log_info "System machine-id updated"
    fi
    
    # Convert auth0|user_ to byte array hexadecimal
    local prefix_hex=$(echo -n "auth0|user_" | xxd -p)
    local random_part=$(generate_random_id)
    local machine_id="${prefix_hex}${random_part}"
    
    local mac_machine_id=$(generate_random_id)
    local device_id=$(generate_uuid | tr '[:upper:]' '[:lower:]')
    local sqm_id="{$(generate_uuid | tr '[:lower:]' '[:upper:]')}"
    
    log_info "Modifying configuration file..."
    # Check if configuration file exists
    if [ ! -f "$STORAGE_FILE" ]; then
        log_error "Configuration file not found: $STORAGE_FILE"
        log_warn "Please install and run Cursor once before using this script"
        exit 1
    fi
    
    # Ensure configuration file directory exists
    mkdir -p "$(dirname "$STORAGE_FILE")" || {
        log_error "Unable to create configuration directory"
        exit 1
    }
    
    # If file does not exist, create a basic JSON structure
    if [ ! -s "$STORAGE_FILE" ]; then
        echo '{}' > "$STORAGE_FILE" || {
            log_error "Unable to initialize configuration file"
            exit 1
        }
    fi
    
    # Modify existing file
    modify_or_add_config "telemetry.machineId" "$machine_id" "$STORAGE_FILE" || exit 1
    modify_or_add_config "telemetry.macMachineId" "$mac_machine_id" "$STORAGE_FILE" || exit 1
    modify_or_add_config "telemetry.devDeviceId" "$device_id" "$STORAGE_FILE" || exit 1
    modify_or_add_config "telemetry.sqmId" "$sqm_id" "$STORAGE_FILE" || exit 1
    
    # Set file permissions and owner
    chmod 444 "$STORAGE_FILE"  # Change to read-only permission
    chown "$CURRENT_USER" "$STORAGE_FILE"
    
    # Verify permission settings
    if [ -w "$STORAGE_FILE" ]; then
        log_warn "Unable to set read-only permission, trying alternative method..."
        chattr +i "$STORAGE_FILE" 2>/dev/null || true
    else
        log_info "Successfully set read-only permission for file"
    fi
    
    echo
    log_info "Configuration updated: $STORAGE_FILE"
    log_debug "machineId: $machine_id"
    log_debug "macMachineId: $mac_machine_id"
    log_debug "devDeviceId: $device_id"
    log_debug "sqmId: $sqm_id"
}

# Display file tree structure
show_file_tree() {
    local base_dir=$(dirname "$STORAGE_FILE")
    echo
    log_info "File structure:"
    echo -e "${BLUE}$base_dir${NC}"
    echo "├── globalStorage"
    echo "│   ├── storage.json (Modified)"
    echo "│   └── backups"
    
    # List backup files
    if [ -d "$BACKUP_DIR" ]; then
        local backup_files=("$BACKUP_DIR"/*)
        if [ ${#backup_files[@]} -gt 0 ]; then
            for file in "${backup_files[@]}"; do
                if [ -f "$file" ]; then
                    echo "│       └── $(basename "$file")"
                fi
            done
        else
            echo "│       └── (Empty)"
        fi
    fi
    echo
}

# Display WeChat Official Account information
show_follow_info() {
    echo
    echo -e "${GREEN}================================${NC}"
    echo -e "${YELLOW}   Follow WeChat Official Account【JianBingGuoZiJuanAI】 for more tips and AI knowledge (Script is free, follow for more tips) ${NC}"
    echo -e "${GREEN}================================${NC}"
    echo
}

# Modify disable_auto_update function, add manual tutorial in case of failure
disable_auto_update() {
    echo
    log_warn "Do you want to disable Cursor automatic update feature?"
    echo "0) No - Keep default setting (Press Enter)"
    echo "1) Yes - Disable automatic update"
    read -r choice
    
    if [ "$choice" = "1" ]; then
        echo
        log_info "Processing automatic update..."
        local updater_path="$HOME/.config/cursor-updater"
        
        # Define manual setup tutorial
        show_manual_guide() {
            echo
            log_warn "Automatic setup failed, please try manual operation:"
            echo -e "${YELLOW}Manual disable update steps:${NC}"
            echo "1. Open terminal"
            echo "2. Copy and paste the following command:"
            echo -e "${BLUE}rm -rf \"$updater_path\" && touch \"$updater_path\" && chmod 444 \"$updater_path\"${NC}"
            echo
            echo -e "${YELLOW}If the above command prompts permission denied, use sudo:${NC}"
            echo -e "${BLUE}sudo rm -rf \"$updater_path\" && sudo touch \"$updater_path\" && sudo chmod 444 \"$updater_path\"${NC}"
            echo
            echo -e "${YELLOW}If you want to add extra protection (recommended), please execute:${NC}"
            echo -e "${BLUE}sudo chattr +i \"$updater_path\"${NC}"
            echo
            echo -e "${YELLOW}Verification method:${NC}"
            echo "1. Run command: ls -l \"$updater_path\""
            echo "2. Confirm file permissions are r--r--r--"
            echo "3. Run command: lsattr \"$updater_path\""
            echo "4. Confirm 'i' attribute (if chattr command was executed)"
            echo
            log_warn "Please restart Cursor after completion"
        }
        
        if [ -d "$updater_path" ]; then
            rm -rf "$updater_path" 2>/dev/null || {
                log_error "Failed to delete cursor-updater directory"
                show_manual_guide
                return 1
            }
            log_info "Successfully deleted cursor-updater directory"
        fi
        
        touch "$updater_path" 2>/dev/null || {
            log_error "Failed to create block file"
            show_manual_guide
            return 1
        }
        
        if ! chmod 444 "$updater_path" 2>/dev/null || ! chown "$CURRENT_USER:$CURRENT_USER" "$updater_path" 2>/dev/null; then
            log_error "Failed to set file permissions"
            show_manual_guide
            return 1
        fi
        
        # Try to set immutable attribute
        if command -v chattr &> /dev/null; then
            chattr +i "$updater_path" 2>/dev/null || {
                log_warn "chattr setting failed"
                show_manual_guide
                return 1
            }
        fi
        
        # Verify setting success
        if [ ! -f "$updater_path" ] || [ -w "$updater_path" ]; then
            log_error "Verification failed: File permissions setting may not have taken effect"
            show_manual_guide
            return 1
        fi
        
        log_info "Successfully disabled automatic update"
    else
        log_info "Keeping default setting, no changes"
    fi
}

# Main function
main() {
    # Check if running on Linux
    if [[ $(uname) != "Linux" ]]; then
        log_error "This script only supports Linux systems"
        exit 1
    fi
    
    clear
    # Display Logo
    echo -e "
    ██████╗██╗   ██╗██████╗ ███████╗ ██████╗ ██████╗ 
   ██╔════╝██║   ██║██╔══██╗██╔════╝██╔═══██╗██╔══██╗
   ██║     ██║   ██║██████╔╝███████╗██║   ██║██████╔╝
   ██║     ██║   ██║██╔══██╗╚════██║██║   ██║██╔══██╗
   ╚██████╗╚██████╔╝██║  ██║███████║╚██████╔╝██║  ██║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═╝╚══════╝ ╚═════╝ ╚═╝  ╚═╝
    "
    echo -e "${BLUE}================================${NC}"
    echo -e "${GREEN}   Cursor Device ID Modification Tool (Linux)  ${NC}"
    echo -e "${YELLOW}  Follow WeChat Official Account【JianBingGuoZiJuanAI】     ${NC}"
    echo -e "${YELLOW}  Share more Cursor tips and AI knowledge (Script is free, follow for more tips)  ${NC}"
    echo -e "${BLUE}================================${NC}"
    echo
    echo -e "${YELLOW}[Important Notice]${NC} This tool supports Cursor v0.45.x"
    echo -e "${YELLOW}[Important Notice]${NC} This tool is free, if it helps you, please follow WeChat Official Account【JianBingGuoZiJuanAI】"
    echo
    
    check_permissions
    check_and_kill_cursor
    backup_config
    generate_new_config
    show_file_tree
    show_follow_info
    
    # Add disable auto-update function
    disable_auto_update
    
    log_info "Please restart Cursor to apply the new configuration"
    show_follow_info
}

# Execute main function
main