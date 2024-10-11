//▼TYPE1用
$(function() {
    $('.slick_type1').slick({
		dots:true,
	});
});
$(function() {
    $('.slick_type_fade').slick({
		fade:true,
		dots:true,
		arrows: false,
		autoplay:true,
		infinite: true,
		autoplaySpeed: 3500,
		pauseOnFocus: false,
		pauseOnHover: false,
		pauseOnDotsHover: false,
	});
});




//▼TYPE2用
$(function() {
    $('.slick_type2').slick({
          infinite: true,
          dots:true,
          slidesToShow: 3,
          slidesToScroll:1,
          responsive: [{
               breakpoint: 700,
                    settings: {
                         slidesToShow: 3,
                         slidesToScroll:1,
               }
          },{
               breakpoint: 480,
                    settings: {
                         slidesToShow: 2,
                         slidesToScroll:1,
                    }
               }
          ]
     });
});

//▼TYPE3用
$(function() {
	$('.slick_type3').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		fade: true,
		asNavFor:'.slick_type3-nav'
	});
	$('.slick_type3-nav').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		asNavFor:'.slick_type3',
		dots: true,
		centerMode: true,
		focusOnSelect: true,
	});
});

//▼TYPE4用
$(function() {
	$('.slick_type4').slick({
          infinite: true,
          dots:true,
          slidesToShow: 1,
          centerMode: true, //要素を中央寄せ
          centerPadding:'25.70%', //両サイドの見えている部分のサイズ
          autoplay:true, //自動再生
          responsive: [{
               breakpoint: 480,
                    settings: {
                         centerMode: false,
               }
          }]
     });
});
$(function() {
	$('.slick_type4-test').slick({
          infinite: true,
          dots:true,
          centerMode: true, //要素を中央寄せ
          autoplay:true, //自動再生
          responsive: [{
               breakpoint: 480,
                    settings: {
                         centerMode: false,
               }
          }]
     });
});

//▼TYPE5用
$(function() {
	$('.slick_type5').slick({
		autoplay:true,
		autoplaySpeed:2000,
		dots:true,
		pauseOnHover:true,
		breakpoint:730,
	});
});