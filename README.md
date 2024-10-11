Vue 3 tutorial

## 🌀 Logos
- **PHP:** <code>7.4</code><br/>
- **Eslint:** <code>7.x</code><br/>
- **PHPCS:** <code>latest</code><br/>

## 🚀 Quickstart
### PHPCS
- Packages: https://github.com/PHP-CS-Fixer/PHP-CS-Fixer
  - Installation & Usage:<br>
    Documentation: https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/installation.rst <br>
    Require: PHP needs to be a minimum version of PHP 7.4.
    Using Composer (global) <br>
    composer require friendsofphp/php-cs-fixer <br>
    Create file .php-cs-fixer.dist.php <br>
    ```
    <?php
    
        $finder = (new PhpCsFixer\Finder())
          ->in(__DIR__);
    
        return (new PhpCsFixer\Config())
           ->setRules([
               '@PSR12' => true,
               'concat_space' => ['spacing' => 'one'],
               'no_unused_imports' => true,
               'return_assignment' => true,
               'no_unneeded_control_parentheses' => [
                   'statements' => [
                       'break', 'clone', 'continue', 'echo_print', 'negative_instanceof',
                       'others', 'return', 'switch_case', 'yield', 'yield_from'
                   ]
               ]
           ])
           ->setFinder($finder);
    ```
    <br>
    Run <br>
    
    ```
      ./vendor/bin/php-cs-fixer fix ./api -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./batch -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./common/dialog -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./common/parts -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./common/php -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./place -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./record -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./report -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./sample -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./schedule -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./system -vvv --using-cache=no --show-progress=dots &&
      ./vendor/bin/php-cs-fixer fix ./user -vvv --using-cache=no --show-progress=dots
    
    ```
  
    Rules <br>
    
    https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/ruleSets/index.rst <br>

    Usage <br>
    
    https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/usage.rst
### ESLINT
  - Packages: https://eslint.org/
    - Installation & Usage:<br>
      Documentation: https://eslint.org/docs/v8.x/use/getting-started <br>
      Create a config file to separate with customer’s configuration
      Create file : <code>src/site/eslint-project-config.js</code>
      ```
      module.exports = {
        root: true,
        env: {
          browser: true,
        },
        extends: [
          'standard',
          'plugin:prettier/recommended',
        ],
        plugins: [
           'prettier'
        ],
        rules: {
          'no-console': 'off',
          'no-var': 'error',
          'no-unused-vars': 'off',
          'camelcase': 'off',
          "quotes": [2, "single"],
          "curly": [2, "all"],
        },
        // Ignore library directories or other files
        ignorePatterns: [
          'node_modules/',  // Ignore node_modules
          'vendor/',        // Ignore vendor directory
          '*.min.js',       // Ignore minified files
          'lib/**',         // Ignore specific library folders
          '*jquery*',       // Ignore all files that contain "jquery" in their filename
        ],
      }
      
      after that run : npm install
      ```
    - Init:
    <code>./node_modules/.bin/eslint --init</code> <br><br>
      √ How would you like to use ESLint? · syntax<br>
      √ What type of modules does your project use? · esm<br>
      √ Which framework does your project use? · none<br>
      √ Does your project use TypeScript? · No<br>
      √ Where does your code run? · browser<br>
      √ What format do you want your config file to be in? · JavaScript<br>
      Local ESLint installation not found.<br>
      The config that you've selected requires the following dependencies: eslint@latest<br>
      √ Would you like to install them now with npm? · No<br>
    <br>
    Run in local to fix all issue with : <br>
    
    ```
       (all) ./node_modules/.bin/eslint ./ --config eslint-project-config.js  --no-eslintrc --fix
       (custom) ./node_modules/.bin/eslint ./resources-app/js/ --config eslint-project-config.js  --no-eslintrc --fix
    ```

