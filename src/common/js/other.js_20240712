// NAVIGATION //////////////////////////////////////////////////////////////////
$(function(){
    $(document).on("click", ".list1", function(){
        if ($(this).hasClass('active')) {
            // メニュー非表示
            $(this).removeClass('active').next('.nav-nest1').slideUp('fast');		      
        }
        else {
            // メニュー表示
            $(this).removeClass('active').addClass('active').next('.nav-nest1').slideDown('fast');
        }
    });
    $(document).on("click", ".list2", function(){
        if ($(this).hasClass('active')) {
            // メニュー非表示
            $(this).removeClass('active').next('.nav-nest2').slideUp('fast');		      
        } 
        else {
            // メニュー表示
            $(this).removeClass('active').addClass('active').next('.nav-nest2').slideDown('fast');
        }
    });

    $(".openNav").on('click',function(){
        $("#Nav").css({"display":"block"});
        $("body").toggleClass("b-hide");
        $("#wrapper").toggleClass("wrap_bg").css({"position":"relative"});
        $("#header, .fixed_navi").toggleClass("wrap_bg").css({"position":"sticky"});
        $("#page").toggleClass("wrap_bg").css({"position":"fixed"});
    });
});

// NAVIGATION HEADER //////////////////////////////////////////////////////////////////
$(function(){
    $("#header .search_box .search").click(function(){ $(this).closest(".search_box").find(".search_list").toggle(); });
    $("#header .switch_btn").click(function(){ $(this).next(".switch_box").toggle(); });
    $("header .notif_bell").click(function(){ $(this).next(".notif_cont").toggle();	});
    $("header .acct_log").click(function(){ $(this).next(".acct_box").toggle();	});
    $(".display_search").click(function(){ $(".header_search").toggle();	});
    $(".display_switch").click(function(){ $(".header_switch").toggle();	});
});

// TAB REDIRECTS //////////////////////////////////////////////////////////////////
//$(function(){
//    $(".tab li:nth-child(1)").click(function() { window.location.href="/user/edit/"; });
//    $(".tab li:nth-child(2)").click(function() { window.location.href="/report/print_list/"; });
//    $(".tab li:nth-child(3)").click(function() { window.location.href="/image/list/"; });
//    $(".user-tab li:nth-child(1)").click(function() { window.location.href="/user/edit/"; });
//    $(".user-tab li:nth-child(2)").click(function() { window.location.href="/report/print_list/"; });
//    $(".user-tab li:nth-child(3)").click(function() { window.location.href="/image/list/"; });
//});

/* Japanese initialisation for the jQuery UI date picker plugin. */
$(function($){
    $.datepicker.regional['ja'] = {
        closeText: '閉じる',
        prevText: '&#x3c;前',
        nextText: '次&#x3e;',
        currentText: '今日',
        monthNames: ['1月','2月','3月','4月','5月','6月',
                                '7月','8月','9月','10月','11月','12月'],
        monthNamesShort: ['1月','2月','3月','4月','5月','6月',
                                '7月','8月','9月','10月','11月','12月'],
        dayNames: ['(日曜日)','(月曜日)','(火曜日)','(水曜日)','(木曜日)','(金曜日)','(土曜日)'],
        dayNamesShort: ['(日)','(月)','(火)','(水)','(木)','(金)','(土)'],
        dayNamesMin: ['日','月','火','水','木','金','土'],
        weekHeader: '週',
        dateFormat: 'yy年mm月 dd日D',
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: '年'};
    $.datepicker.setDefaults($.datepicker.regional['ja']);
});

$( function() {
    $(".date").datepicker();
    $(".date_no-Day").datepicker({ dateFormat: 'yy/mm/dd' });
    $(".date_dayOnly").datepicker({ dateFormat: 'yy/mm/ddD' });
    $(".date_monthOnly").datepicker({ dateFormat: 'yy/mm' });


    //Method which handles string conversion to double digits for dates
    Date.prototype.yyyymmdd = function() {
        var mm = (this.getMonth() + 1).toString();
        var dd = this.getDate().toString();

        return [this.getFullYear(), mm<10 ? '0'+ mm: mm, dd<10 ? '0'+ dd : dd].join('/');
    };
    var currentMonth = new Date();
    $(".prev_month").click(function(){
        var p_firstDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1);
        var p_lastDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 0);
        $('.date_start').val(p_firstDay.yyyymmdd());
        $('.date_end').val(p_lastDay.yyyymmdd());
    });
    $(".this_month").click(function(){
        var t_firstDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
        var t_lastDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0);
        $('.date_start').val(t_firstDay.yyyymmdd());
        $('.date_end').val(t_lastDay.yyyymmdd());
    });
    $(".next_month").click(function(){
        var n_firstDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1);
        var n_lastDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 2, 0);
        $('.date_start').val(n_firstDay.yyyymmdd());
        $('.date_end').val(n_lastDay.yyyymmdd());
    });
});



// USER-DETAIL && USER-NEW TABBING //////////////////////////////////////////////////////////////////
//$(function(){
		
    $(".accor_tab-s").click(function(){
            $(this).toggleClass("accor_show");
            $(this).next(".accor_tab").slideToggle();
    });

    $(".accor_tab li").click(function() {
            var num = $(".accor_tab li").index(this);
            $(".con_box").addClass('disnon');
            $(".con_box").eq(num).removeClass('disnon');
            $(".accor_tab li").removeClass('active');
            $(this).addClass('active');
    });
    
    // 初期表示
    var param = location.hash; /* パラメータ取得 */
    $(".con_box").addClass('disnon');
    $(".accor_tab li").removeClass('active');
    if(param){
        // パラメータ指定時タブ初期表示変更
        var num = param.replace(/#/g, '');
        var num = num - 1;
        var tgt = ".con-box:nth-child("+ num +")";
        $(".con_box").eq(num).removeClass('disnon');
        $(".accor_tab li").eq(num).addClass('active');
    }else{
        $(".con_box:first-child").removeClass("disnon");
        $(".accor_tab li:first-child").addClass('active');
    };
    

	// BASIC INFO - IMAGE TITLE
	// var touchtime = 0;
	// $('dl.img_tit-box dd').on('click', function(e){
	// 	e.preventDefault;
	// 	//DoubleCLick function
	// 	if (touchtime == 0) {
	// 		touchtime = new Date().getTime();
	// 	} else {
	// 		if (((new Date().getTime()) - touchtime) < 800) {
	// 			touchtime = 0;
	// 			$(this).find(".hov_box").toggle();
	// 		} else {
	// 			touchtime = new Date().getTime();
	// 		}
	// 	}
	// });

	// QUESTION POP UP
//	$(".quest").click(function(){
//		$(this).nextAll(".quest_box").toggle();
//	});

	//GAF CHECKBOX
//	$(".med_information input#show_all").click(function(){
//		if($(this).is(":checked")){
//			$(".med_information .med_box4 table tr").show();
//		} else {
//			$(".med_information .med_box4 table tr").hide();			
//			$(".med_information .med_box4 table tr.gaf_act").show();
//		}
//	});
//});


// PAYMENT METHOD SELECT //////////////////////////////////////////////////////////////////
$(function(){
    $(".is" + $("#financial_cat option:selected").val()).show();

    $("#financial_cat").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".hidden").not(".is" + optionValue).hide();
                $(".is" + optionValue).show();
            } else{
                $(".hidden").hide();
            }
        });
    }).change();
});



// REGISTER REDIRECT //////////////////////////////////////////////////////////////////
$(function(){
    $("#register .navigate").click(function() {
        window.location.href="/report/all_list/";
    });
});


// IMAGE LIST REDIRECT //////////////////////////////////////////////////////////////////
// $(function(){
//     $("#image-list .img_list ul").click(function() {
//         window.location.href="/image/list/";
//     });
// });

// NURSE-RECORD1 訪問看護区分 //////////////////////////////////////////////////////////////////
$(function() {
    $(".nurse_record .category input[type=radio]").click(function() {
        var num = $(".nurse_record .category input[type=radio]").index(this);
        $(".record_info").addClass('disnon');
        $(".record_info").eq(num).removeClass('disnon');
        $(".nurse_record .category input[type=radio]").removeClass('select');
        $(this).addClass('select');
    });

    //  DUPLICATE BTN
    $(".nursing .d_right .duplicate.clicked").click(function(){
        $(this).closest(".nursing").find(".msg_duplicated").show();
        $("#wrapper").toggleClass("wrap_msg").css({"position":"relative"});
        $(".cancel, .msg_box-dlt").click(function(){
            $(".msg_box").hide();
            $("#wrapper").css({"position":"unset"}).removeClass("wrap_msg");
        });
    });
});

// TITLE TOGGLE //////////////////////////////////////////////////////////////////
$(function(){
    // STAFF LIST - SEARCH DROPDOWN
    $(".search_drop").click(function(){
        $(this).toggleClass("active").next().toggle();
    });

    $(".tit_toggle").click(function() {
        if (window.innerWidth < 700) {
            $(this).toggleClass("inactive").nextAll(".child_toggle").toggle();
        }
    });

    // USER DETAIL HISTORY BOX
    $(".tit_toggle2").click(function() {
        $(this).toggleClass("inactive").nextAll(".child_toggle2").slideToggle();
    });
});

// IMAGE DETAILS BACK BTN //////////////////////////////////////////////////////////////////
$(function(){
    $(".prev_page_toggle").click(function() {
        $(this).parents(".new_default").hide();
        $(".img_display").show();
    });
});

// STAFF LIST QUALIFICATIONS //////////////////////////////////////////////////////////////////
$(function(){
    $(".display_quali").click(function(){
        $(".qualifications").show();
    });
});

//$(function(){
//    // RECORD-DETAIL
//    $(".record-navi .save").click(function(){
//        $(this).toggleClass("clicked");
//        $(".msg_box").toggleClass("show_box");
//        $("#wrapper").toggleClass("wrap_msg").css({"position":"relative"});
//        $(".msg_box-cont").text("経過記録メールを送信しますか？"); 
//        $(".msg_box-close").hide();
//        $(".msg_box-send, .msg_box-cancel").show();
//        $(".msg_box-send").click(function(){
//            $(".msg_box-cont").text($(this).text() == '経過記録メールを送信しますか？' ? '経過記録メールを送信しますか？' : '経過記録メールを送信しました');
//            $(".msg_box-close").show();
//            $(".msg_box-send, .msg_box-cancel").hide();	
//        });
//        $(".msg_box-close, .msg_box-cancel").click(function(){
//            $(".msg_box").removeClass("show_box");
//            $(".msg_box").prev(".record-navi .save").removeClass("clicked");	
//            $("#wrapper").css({"position":"unset"}).removeClass("wrap_msg");		
//        });
//    });
//});


// DELETE/SAVE MESSAGE BOX POPUP //////////////////////////////////////////////////////////////////
$(function(){
    // KANTAKI
    $(".trash_act").click(function(){
        $(this).toggleClass("clicked").next(".msg_box").toggleClass("show_box");

        $(".msg_box-cont").text("削除してよろしいですか？"); 
        $(".msg_box-close").hide();
        $(".msg_box-dlt, .msg_box-cancel").show();
        $(".msg_box-dlt").click(function(){
            $(".msg_box-cont").text($(this).text() == '削除してよろしいですか？' ? '削除してよろしいですか？' : '削除しました');
            $(".msg_box-close").show();
            $(".msg_box-dlt, .msg_box-cancel").hide();
        });
        $(".msg_box-close, .msg_box-cancel").click(function(){
            $(".msg_box").removeClass("show_box");
            $(".msg_box").prev(".trash_act").removeClass("clicked");			
        });
    });

    // STAFF DETAILS
    $("#staff-detail .delete").click(function(){
        $(this).next(".msg_box").show();
        $("#wrapper").toggleClass("wrap_msg").css({"position":"relative"});
        $(".msg_box-dlt").click(function(){
            $(".msg_box").hide();
            $("#wrapper").css({"position":"unset"}).removeClass("wrap_msg");
        });
    });

    // OFFICE - HISTORY
    $(".office_navi .save").click(function(){
        $(".msg_box").show();
        $("#wrapper").toggleClass("wrap_msg").css({"position":"relative"});
        $(".msg_box-dlt").click(function(){
            $(".msg_box").hide();
            $("#wrapper").css({"position":"unset"}).removeClass("wrap_msg");
        });
    });

    // INFO - SAVE BTN
    $("#info .save-op").click(function(){
        $(this).closest("#info").find(".msg_saved").show();
        $("#wrapper").toggleClass("wrap_msg").css({"position":"relative"});
        $(".close_part").click(function(){ $("#wrapper").css({"position":"unset"}).removeClass("wrap_msg"); });
    });

    // INFO - CANCEL BTN
    $("#info .cancel").click(function(){
        $(this).closest("#info").find(".msg_cancelled").show();
        $("#wrapper").toggleClass("wrap_msg").css({"position":"relative"});
        $(".msg_box-dlt").click(function(){
            $(".msg_box").hide();
            $("#wrapper").css({"position":"unset"}).removeClass("wrap_msg");
        });
    });
});

// DATA JS //////////////////////////////////////////////////////////////////
$(function(){
    $(".display_data").click(function() {
        $(".data_updated").toggle();
    });

    // Listen for click on toggle checkbox
    $('#select-all1').click(function(event) {   
        if(this.checked) {
            // Iterate each checkbox
            $('td:nth-child(1) input').each(function() {
                this.checked = true;
            });
        } else {
            $('td:nth-child(1) input').each(function() {
                this.checked = false; 
            });
        }
    });
    $('#select-all2').click(function(event) {   
        if(this.checked) {
            // Iterate each checkbox
            $('td:nth-child(2) input').each(function() {
                this.checked = true;
            });
        } else {
            $('td:nth-child(2) input').each(function() {
                this.checked = false; 
            });
        }
    });
});

// NURSE-RECORD2 JS //////////////////////////////////////////////////////////////////
$(function(){
    $("#nurse-record2 .problem dt").click(function() {
        $(this).toggleClass("active").next("dd").slideToggle();
    });
});

// USER SCHEDULE JS //////////////////////////////////////////////////////////////////
$(function(){
    $(".rec_toggle").click(function() {
        $(this).toggleClass("recorded");
    });

    $(".rec_link").click(function() {
        if(!$(this).hasClass("recorded")) {
            $(this).addClass("recorded");
        }
        else {
            window.location.href="024_kantaki.html";
        }
    });

    $(".display_edit").click(function(){
        $(".common_part1").show();
        $("body").addClass("b-hide");
    });
    $(".display_code1").click(function(){
        $(this).parents(".new_default").hide();
        $(".kantaki_code").show();
    });
    $(".display_code2").click(function(){
        $(this).parents(".new_default").hide();
        $(".patrol_code").show();
    });
    $(".display_period").click(function(){
        $(this).parents(".new_default").hide();
        $(".period_setting").show();
    });
    $(".display_rate").click(function(){
        $(this).parents(".new_default").hide();
        $(".daily_rate").show();
    });
    $(".display_rate1").click(function(){
        $(".daily_rate").show();
    });
    $(".display_add_period").click(function(){
        $(".add_period").show();
    });

    // DISPLAY COST WITHOUT HIDING PARENT DIV
    $(".display_cost1").click(function(){
        $(".actual_cost").show();
    });

    //  CONFIRM BTN
    // $(".schedule_info .btn.confirm").click(function(){
    //     $(this).closest(".schedule_info").find(".msg_confirmed").show();
    //     $("#wrapper").toggleClass("wrap_msg").css({"position":"relative"});
    //     $(".cancel, .msg_box-exec").click(function(){
    //         $(".msg_box").hide();
    //         $("#wrapper").css({"position":"unset"}).removeClass("wrap_msg");
    //     });
    // });
});

// PHOTO VIEW BOX - KANTAKI //////////////////////////////////////////////////////////////////
$(function(){
    $(".receipt_box .up_view").click(function(){
        $(".view_box").show(function(){
            $(".close_part").click(function(){ $(this).parents(".view_box").hide(); });
        });
    });
});

// ROOT && ROOT-TABLE && SCHEDULE JS //////////////////////////////////////////////////////////////////
$(function(){
	// CALENDAR TEXT SIZE ADJUST
	$(".txt_size li").click(function(){
	    $(".small_txt, .large_txt").toggleClass("active");
	});

	// CALENDAR SCHEDULE PARTS
	$(".calendar_data .sched_parts > ul li").click(function(){
	    $(this).toggleClass("open").find("ul").slideToggle();
	});

	// root.html TABLE HEADER
	$(".w_toggle").click(function(){
            $(this).find("p").toggleClass("width-a").fadeOut(100,function(){
                $(this).text($(this).text() == '+' ? '通い介護➃' : '+').fadeIn(100);
            })
            $(this).parents("table").find("td:nth-child(8)").toggleClass("width-a");
	});
	$(".w_toggle1").click(function(){
            $(this).find("p").toggleClass("width-a").fadeOut(100,function(){
                $(this).text($(this).text() == '+' ? '通い介護①' : '+').fadeIn(100);
            })
            $(this).parents("table").find("td:nth-child(11)").toggleClass("width-a");
	});

	// DISPLAY PART ON CLICK
	$(".display_part").click(function(){
            $(this).nextAll(".displayed_part").show();
            $("body").addClass("b-hide");
	});
	$(".display_dets").click(function(){
            $(this).nextAll(".sched_details").show();
	});
	$(".d_modal").click(function(){
            $(this).next(".dupli_modal").show();
	});
	$(".display_rr").click(function(){
            $(".register_root").show();
	});

	// CLOSE PART ON CLOSE BTN CLICK
	$(".close_part").click(function(){
            $(this).parent(".cancel_act").hide();
            $(".user_lists").hide();
            $("body").removeClass("b-hide");
            $(".user_search").removeClass("scale");
	});
	$(".select_close").click(function(){
            $(this).parent(".cancel_act").hide();
	});

	// CLOSE ON CANCEL BTN CLICK
	$(".cancel").click(function(){
            $(this).parents(".cancel_act").hide();
            $(".user_lists").hide();
            $("body").removeClass("b-hide");
            $(".user_search").removeClass("scale");
	});
	$(".dupli_modal .cancel").click(function(){
            $(".user_selection").hide();
	});

	// root-table.html TABLE HEADER ~ TEXTAREA TOGGLE
	var touchtime = 0;
	$('.planner').on('click', function(e){
            e.preventDefault;
            //DoubleCLick function
            if (touchtime == 0) {
                touchtime = new Date().getTime();
            } else {
                if (((new Date().getTime()) - touchtime) < 800) {
                    touchtime = 0;
                    $(this).find("input").toggleClass("display_plnr");
                    if ($(this).find("input").hasClass("display_plnr")) {
                        $(this).find("input").prop("disabled",true).css("user-select","none");
                    }
                    else { $(this).find("input").prop("disabled",false).css("user-select","initial"); }
                } else {
                    touchtime = new Date().getTime();
                }
            }
	});
	$(".memo_txt").click(function(){
	    $(this).find("input").toggleClass("style_change");
	});


	// root-table.html USER SELECT
	$(".data6-2.tenmins, .data6-2.hovered").click(function(){
	    $(".data6-2.tenmins").toggleClass("h_active");
	    $(".data6-2.hovered").css({"display":"none!important"});
	});

	// DISPLAY EMPLOYEE CHOICE ON CLICK
	$(".display_choice").click(function(){
            if (window.innerWidth > 700) {
                $(".employees").css("left", $(this).offset().left).show();
            }
            else {
                $(".employees").css({"left":"0", "right":"0", "margin":"auto"}).show();
            }
	});
	$(".display_employee").click(function(){
            $(".employees").css({"left":"0", "right":"0", "top":"50%", "transform":"translateY(-50%)", "margin":"auto"}).show();
            $("body").addClass("b-hide");
	});


	// DISPLAY COMMON ROOT_COMMUTE PARTS ON CLICK
	$(".display_part1").click(function(){
            $(".common_part1").show();
            $("body").addClass("b-hide");
	});
	$(".display_part2").click(function(){
            $(".common_part2").show();
            $("body").addClass("b-hide");
	});

	// DUPLICATE PARTS
	$(".duplicate0").click(function(){
            $(this).parents(".new_default").hide();
            $(this).parents("td").find(".duplicated").show();
            $(this).parents(".new_default").next(".duplicated").show();
            $("body").addClass("b-hide");
	});
	$(".duplicate1").click(function(){
            $(this).parents(".new_default").hide();
            $(".duplicated1").show();
            $("body").addClass("b-hide");
	});
	$(".duplicate2").click(function(){
            $(this).parents(".new_default").hide();
            $(".duplicated2").show();
            $("body").addClass("b-hide");
	});
	$(".duplicate3").click(function(){
            $(this).parents(".new_default").hide();
            $(".duplicated3").show();
            $("body").addClass("b-hide");
	});
	$(".duplicate4").click(function(){
            $(this).parents(".new_default").hide();
            $(".duplicated4").show();
            $("body").addClass("b-hide");
	});
	$(".duplicate5").click(function(){
            $(this).parents(".new_default").hide();
            $(".duplicated5").show();
            $("body").addClass("b-hide");
	});

	// root.html && root-table.html SEARCH BTN CLICK
	$(".user_search").click(function(){
            $(".user_lists").slideToggle();
	});

	$(".display_user").click(function(){
            $(".user_selection").show();
            $("body").addClass("b-hide");
	});

	// root-table.html 7.ルート種類 ON CLICK
	$(".display_type").click(function(){
            $(this).parents(".new_default").hide();
            $(".root_type").show();
	});

	// schedule.html SEARCH BTN CLICK on 実費 && 実費編集
	$(".display_cost").click(function(){
        //  $(this).parents(".new_default").hide();
        // $(".actual_cost").show();
	});
});


// INSURANCE && USER NEW && USER DETAIL JS //////////////////////////////////////////////////////////////////

$(function(){
    
    // 事業所検索モーダル
    $(".office_search").click(function(){
        var tgtId = $(this).data("tgt_id");
        $("#tgt-id").val("tgt-id" + tgtId);
        $("#tgt-name1").val("tgt-name1_" + tgtId);
        $("#tgt-name2").val("tgt-name2_" + tgtId);
        $(".cont_office").show();
    });
    $(".cont_office .close").click(function(){
        $(".cont_office").hide();
    });

    // スタッフ検索モーダル
    $(".staff_search").click(function(){
        $(".cont_staff").show();
    });
    $(".cont_staff .close").click(function(){
        $(".cont_staff").hide();
    });
    $(".staff2_search").click(function(){
        $(".cont_staff2").show();
    });
    $(".cont_staff2 .close").click(function(){
        $(".cont_staff2").hide();
    });
    $(".staff3_search").click(function(){
        $(".cont_staff3").show();
    });
    $(".cont_staff3 .close").click(function(){
        $(".cont_staff3").hide();
    });
    $(".staff4_search").click(function(){
        $(".cont_staff4").show();
    });
    $(".cont_staff4 .close").click(function(){
        $(".cont_staff4").hide();
    });
    $(".staff5_search").click(function(){
        $(".cont_staff5").show();
    });
    $(".cont_staff5 .close").click(function(){
        $(".cont_staff5").hide();
    });
    // 利用者検索モーダル
    $(".user_search").click(function(){
        $(".cont_user").show();
    });
    $(".cont_user .close").click(function(){
        $(".cont_user").hide();
    });
    $(".user2_search").click(function(){
        $(".cont_user2").show();
    });
    $(".cont_user2 .close").click(function(){
        $(".cont_user2").hide();
    });

    $(".basic_info .tr4 input.name").click(function(){
  //          $(".cont_user-dup").show();
    });
});


// CSV TAB SWITCHING //////////////////////////////////////////////////////////////////
$(function() {
    var hideElements = function(){
        $(".target_data input[type=radio]").each(function(key,val){
            var $el = $(this);
            if ($el.hasClass('select')) {
                $el.parents('dl').find("input[type=checkbox]").prop('disabled', false);
                $el.parents('dl').find(":checkbox:lt(4)").prop('checked', true);
            }else{
                $el.parents('dl').find("input[type=checkbox]").prop('disabled', true);
                $el.parents('dl').find(":checkbox:lt(4)").prop('checked', false);
            }
        });
    }
    hideElements();
    $(".target_data input[type=radio]").click(function() {
            var num = $(".target_data input[type=radio]").index(this);
            $(".sub_info").addClass('disnon');
            $(".sub_info").eq(num).removeClass('disnon');
            $(".target_data input[type=radio]").removeClass('select');
            $(this).addClass('select');
        hideElements();
    });
});

// NOTICE //////////////////////////////////////////////////////////////////
$(function(){
	$(".notice .notice_list dt").click(function(){
	    $(this).toggleClass("open").next(".notice .notice_list dd").slideToggle();
	});
});


//  CHECKBOX SELECT ALL //////////////////////////////////////////////////////////////////
$(function(){
    // Listen for click on toggle checkbox
    $('#select-all').click(function(event) {   
        if(this.checked) {
            // Iterate each checkbox
            $('.check_user').each(function() {
                this.checked = true;       
                //$(".disabled_list").addClass("active_list");        
            });
        } else {
            $('.check_user').each(function() {
                this.checked = false;             
                //$(".disabled_list").removeClass("active_list");        
            });
        }
    });
    $(".check_user").click(function(){
        //$(this).closest("tr").find(".disabled_list").toggleClass("active_list");
        if($(this).prop("checked") == false){
           $('#select-all').prop('checked', false);
        }
    });
});


// COMMON BTN CLICK //////////////////////////////////////////////////////////////////
$(function(){
    $(".add_sub_btn").click(function(){
        var ol_wrap = $(this).closest(".add_sub").find("ol");
        var new_list = "";
        // new_list += '<li><select class="default"><option disabled hidden selected>選択してください</option></select><p class="list_delete l_delete1">Delete</p></li>';
        new_list += '<li><select><option disabled hidden>選択してください</option><option name="upAry[dwsj_unique_id]" value=""></option></select><p class="list_delete l_delete1">Delete</p><p><input type="text" name="upAry[start_date]" class="master_date date_no-Day date_start" style="width:100px;" value=""><small>～</small><input type="text" name="upAry[end_date]" class="master_date date_no-Day date_end" style="width:100px;" value=""></p></li>';
        $(new_list).appendTo(ol_wrap);
    });
    $(".add_details").click(function(){
        var ul_wrap = $(this).closest(".service_dets").find(".service_list");
        var new_list = "";
        new_list += '<ul><li>&nbsp;</li><li><input class="service_time" type="text" name="t_from"><small>～</small><input class="service_time" type="text" name="t_to"></li><li><select><option selected hidden disabled>&nbsp;</option><option>服薬</option></select></li><li><select><option selected hidden disabled>&nbsp;</option><option>通い介護②</option></select></li><li><p class="list_delete l_delete2">Delete</p></li></ul>';
        $(new_list).appendTo(ul_wrap);
    });
    $(".new_default").on('click','.l_delete1',function(event){
        event.preventDefault();
        $(this).closest('li').remove();
        return false;
    });
    $(".new_default").on('click','.l_delete2',function(event){
        event.preventDefault();
        $(this).closest('ul').remove();
        return false;
    });
    $(".new_default").on('click','.l_delete3',function(event){
        event.preventDefault();
        $(this).closest('tr').remove();
        return false;
    });
    $(".addCost").click(function(){
        var tbody = $(this).closest(".add_sub").find("tbody");
        var new_tr = "";
        new_tr += '<tr><td class="type"><b class="sm">種類</b><select><option selected hidden disabled>&nbsp;</option><option>食事朝</option><option>食事夕</option><option>自費</option></select></td><td class="item"><b class="sm">項目名称</b><select><option selected hidden disabled>&nbsp;</option><option>朝食代(刻み食・ミキサー食)</option><option>夕食</option><option>訪看サービス自費(交通費含む・1時間未満)</option></select></td><td class="price"><b class="sm">単価最大7桁</b><input type="text" name="単価"></td><td class="tax"><b class="sm">消費税<br>区分</b><select><option selected hidden disabled>&nbsp;</option><option>税込</option><option>税込</option><option>税込</option></select></td><td class="sales_tax"><input type="text" name="単価"><span>%</span></td><td class="d_cate"><b class="sm">控除区分</b><select><option selected hidden disabled>&nbsp;</option><option>控除対象外</option><option>控除対象外</option><option>控除対象外</option></select></td><td><p class="list_delete l_delete3">Delete</p></td></tr>';
        $(new_tr).appendTo(tbody);
    });
});




// UNKNOWN PARTS JS //////////////////////////////////////////////////////////////////
$(function(){

    //  期間設定アラート
    $(".duration").click(function(){
        $(".duration_alert").toggle();
    });
});

// PAGETOP //////////////////////////////////////////////////////////////////
$(function() {
    var topBtn = $('#page');	
    topBtn.hide();
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            topBtn.fadeIn();
        } else {
            topBtn.fadeOut();
        }
    });
    //ƒXƒNƒ[ƒ‹‚µ‚Äƒgƒbƒv
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});

// YOUTUBE ////////////////////////////////////////////////////////////////////
$(function(){
    $('.youtube iframe').parents('.youtube').css('padding-top','56.25%'); 
});


//PHONE LINK /////////////////////////////////////////////////////////////////
$(function(){
var ua = navigator.userAgent.toLowerCase();
var isMobile = /iphone/.test(ua)||/android(.+)?mobile/.test(ua);

if (!isMobile) {
    $('a[href^="tel:"]').on('click', function(e) {
        e.preventDefault();
    });
}
});

