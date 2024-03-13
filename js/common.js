// ページ内スクロール
$(function() {
  var headerHeight = 0;
  $('[href^="#"]').click(function(){ // リンクをクリックしたら…
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top-headerHeight;
    if(window.matchMedia('(min-width: 481px)').matches){ 
      $("html, body").animate({scrollTop:position - 20}, 500, "swing"); //pc表示の時は固定表示のヘッダー分下までスクロール
    } else { // それ以外の場合（ifとセット　条件分岐）
      $("html, body").animate({scrollTop:position - 100}, 500, "swing"); //sp表示の時は固定表示のヘッダー分下までスクロール
    }
    return false;
  });
});


// トップへ戻るボタンの表示
$(function() {
  var btn = $('.page-top');
  $(window).on('load scroll', function(){
    if($(this).scrollTop() > 200) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });

// ページトップへアニメーションで戻る
  btn.on('click',function () {
    $('body,html').animate({
      scrollTop: 0
    });
  });
});


// ハンバーガーメニューの設定
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


// アコーディオンの設定
$('.flow-list > li code').click(function(){
  $(this).toggleClass('show');
  $(this).siblings('.acc').toggleClass('show');
});


// fixedメニューの設定
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


// fixedメニューの設定
$(function() {
  $(window).on('load scroll', function(){
    if($(this).scrollTop() > 100) {
      $('.site-header').addClass('hide');
    } else {
      $('.site-header').removeClass('hide');
    }
  });
});

// slick
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










