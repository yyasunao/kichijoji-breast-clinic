$(document).ready(function($) {
  // ページ読み込み時にURLに#が含まれている場合にアンカーにスクロール
  if (window.location.hash) {
    var target = $(window.location.hash);
    if (target.length) {
      // デバイスの横幅に応じたオフセットを設定
      var offset = $(window).width() <= 480 ? 120 : 40;

      $('html, body').animate({
        scrollTop: target.offset().top - offset
      }, 300, function() {
        // アンカーを削除するためにhistory.replaceStateを使用
        history.replaceState(null, null, window.location.pathname);
      });
    }
  }

  $('a[href*="#"]').on('click', function(event) {
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
      && location.hostname == this.hostname
    ) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        event.preventDefault();

        // デバイスの横幅に応じたオフセットを設定
        var offset = $(window).width() <= 480 ? 120 : 40;

        $('html, body').animate({
          scrollTop: target.offset().top - offset
        }, 300, function() {
          // アンカーを削除するためにhistory.replaceStateを使用
          history.replaceState(null, null, window.location.pathname);
        });
        return false;
      }
    }
  });
});






// $(function() {
//   var headerHeight = 0;
//   $('[href^="#"]').click(function(){
//     var href= $(this).attr("href");
//     var target = $(href == "#" || href == "" ? 'html' : href);
//     var position = target.offset().top-headerHeight;
//     if(window.matchMedia('(min-width: 481px)').matches){ 
//       $("html, body").animate({scrollTop:position - 20}, 500, "swing");
//     } else {
//       $("html, body").animate({scrollTop:position - 100}, 500, "swing");
//     }
//     return false;
//   });
// });

// jQuery(function(){

//   let headerHeight = $('#masthead').outerHeight();
//   let speed = 600;

//   jQuery('a[href^="#"]').click(function() {
//     let href= $(this).attr("href");
//     let target = $(href == "#" || href == "" ? 'html' : href);
//     let position = target.offset().top - headerHeight;
//     $('html, body').stop().animate({scrollTop:position}, speed, "swing");
//     return false;
//   });

// });

$(function() {
  var btn = $('.page-top');
  $(window).on('load scroll', function(){
    if($(this).scrollTop() > 200) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });

  btn.on('click',function () {
    $('body,html').animate({
      scrollTop: 0
    });
  });
});


$(function(){
  $('.menu-btn').on('click', function() {
    $(this).toggleClass('show');
    $(this).next().toggleClass('show');
  });

  $('.menu a').on('click', function() {
    $('.menu-header-menu-container').removeClass('show');
    $('.menu-btn').removeClass('show');
  });
});


$('.flow-list > li span').click(function(){
  $(this).toggleClass('show');
  $(this).siblings('.acc').slideToggle(500);
});


$(function() {
  var btn = $('.fixed-menu');
  $(window).on('load scroll', function(){
    if($(this).scrollTop() > 200) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });
});


$(function() {
  $(window).on('load scroll', function(){
    if($(this).scrollTop() > 100) {
      $('.site-header').addClass('hide');
    } else {
      $('.site-header').removeClass('hide');
    }
  });
});


$(function () {
  $('.slide').slick({
    autoplay: true,
    arrows: false,
    centerMode: true,
    centerPadding: '20%',
    responsive: [
        {
          breakpoint: 480,
          settings: {
          slidesToShow: 1,
          centerPadding: '0',
          },
        },
      ],
  });
});


$(function(){
  $('.ark-block-faq__q').on('click', function() {
    $(this).toggleClass('show');
    $(this).next().slideToggle('500');
  });
});


// $(function () {
//   $("[data-screen='complete']").parents('.entry-content').find('.first-blk').addClass('test');
// });


