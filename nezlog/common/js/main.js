/*
 * https://fonts.google.com/specimen/Noto+Sans+JP
 * Thin    100
 * Light   300
 * Regular 400
 * Medium  500
 * Bold    700
 * Black   900
 * ----------------------------------------------- */
window.WebFontConfig = {
  google: {
    families: [
      'Noto+Sans+JP:400,700'
    ]
  },
  active: function () {
    sessionStorage.fonts = true;
  }
};


/*
 *
 * ----------------------------------------------- */
(function () {
  var wf = document.createElement('script');
  wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
  wf.type = 'text/javascript';
  wf.async = true;
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(wf, s);
})();


/*
 *
 * ----------------------------------------------- */
var isMobile = false;

jQuery(document).ready(function ($) {
  var breakpoint = 768;
  updateIsMobile();

  $(window).on('resize load', function () {
    updateIsMobile()
  });

  function updateIsMobile() {
    isMobile = $(window).width() < breakpoint;
  }
});


/*
 *
 * ----------------------------------------------- */
jQuery(document).ready(function ($) {
  if (typeof $.smoothScroll !== 'function') {
    return false;
  }

  var reSmooth = /^#sm-/;
  var id;

  $(window).on('load', function () {
    if (reSmooth.test(location.hash)) {
      id = '#' + location.hash.replace(reSmooth, '');

      var offset = (isMobile) ? -40 : -30;

      var $id = $(id);
      var offsetSm = $id.data('offset-sm');
      var offsetMd = $id.data('offset-md');

      if (isMobile && offsetSm) {
        offset = offsetSm;
      } else if (offsetMd) {
        offset = offsetMd;
      }

      $.smoothScroll({
        scrollTarget: id,
        offset: offset,
        easing: 'easeInOutCubic'
      });
    }
  });
});


/*
 *
 * ----------------------------------------------- */
// jQuery Smooth Scroll - v2.2.0 - 2017-05-05
// https://github.com/kswedberg/jquery-smooth-scroll
// jQuery(document).ready(function ($) {
//   $('[data-sm]').smoothScroll({
//     offset: -10,
//     beforeScroll: function(e) {
//       var scrollTarget = e.scrollTarget;
//
//       if (scrollTarget === '#form-title') {
//         if (isMobile) {
//           e.offset = -20;
//         } else {
//           e.offset = -30;
//         }
//       } else if (scrollTarget === '#section-media') {
//         e.offset = -10;
//       }
//     }
//   });
// });


/*
 *
 * ----------------------------------------------- */
jQuery(document).ready(function ($) {
  $('a.page-top').on('click', function (event) {
    $.smoothScroll({
      easing: 'swing',
      speed: 400
    });

    return false;
  });
});


/*
 *
 * ----------------------------------------------- */
// jQuery Validation Plugin
// https://jqueryvalidation.org/
// $(function () {
//   $('.form-container').validate({
//     rules: {
//       '問い合わせ内容': {
//         required: true
//       }
//     },
//     messages: {
//       '問い合わせ内容': {
//         required: "必須項目です。"
//       }
//     },
//     groups: {
//       username: "郵便番号1 郵便番号2"
//     },
//     errorPlacement: function (error, element) {
//       var $container = element.closest('tr').find('.error-container');
//
//       if (element.attr("name") === "問い合わせ内容") {
//         error.appendTo($container);
//       } else if (element.attr("name") === "郵便番号1" || element.attr("name") === "郵便番号2") {
//         error.appendTo($container);
//       } else {
//         error.insertAfter(element);
//       }
//     },
//     highlight: function (element) {
//       if (!($(element).hasClass('optional') && $(element).is(':blank'))) {
//         $(element).closest('.form-group').addClass('has-error');
//       }
//     },
//     unhighlight: function (element) {
//       if (!($(element).hasClass('optional') && $(element).is(':blank'))) {
//         $(element).closest('.form-group').removeClass('has-error');
//       }
//     }
//   });
//
// });


/*
 *
 * ----------------------------------------------- */
// $.extend($.validator.messages, {
//   required: "必須項目です。",
//   remote: "このフィールドを修正してください。",
//   email: "有効なEメールアドレスを入力してください。",
//   url: "有効なURLを入力してください。",
//   date: "有効な日付を入力してください。",
//   dateISO: "有効な日付（ISO）を入力してください。",
//   number: "有効な数字を入力してください。",
//   digits: "数字のみを入力してください。",
//   creditcard: "有効なクレジットカード番号を入力してください。",
//   equalTo: "同じ値をもう一度入力してください。",
//   extension: "有効な拡張子を含む値を入力してください。",
//   maxlength: $.validator.format("{0} 文字以内で入力してください。"),
//   minlength: $.validator.format("{0} 文字以上で入力してください。"),
//   rangelength: $.validator.format("{0} 文字から {1} 文字までの値を入力してください。"),
//   range: $.validator.format("{0} から {1} までの値を入力してください。"),
//   step: $.validator.format("{0} の倍数を入力してください。"),
//   max: $.validator.format("{0} 以下の値を入力してください。"),
//   min: $.validator.format("{0} 以上の値を入力してください。")
// });


/*
 *
 * ----------------------------------------------- */
//   $.validator.addMethod("custom-email", function (value, element) {
//     var emailArray = value.split('@');
//     // preg_match("/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-z]+(\.[!#%&\-_0-9a-z]+)+$/", "$str") && count($mailaddress_array) ==2
//
//     return this.optional(element) || (/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-z]+(\.[!#%&\-_0-9a-z]+)+$/.test(value) && emailArray.length === 2);
//   }, "正しいメールアドレスを入力して下さい。");


/*
 * collapse animation for navbar dropdown
 * ----------------------------------------------- */
jQuery(document).ready(function ($) {
  if (isMobile) {
    $('[data-toggle-touch="collapse"]').on('touchstart', function (e) {
      $(this).parent().toggleClass('open-dropdown-xs').children('.collapse').collapse('toggle');

      e.preventDefault();
    });
  } else {
    $('[data-toggle-hover="collapse"]').parent().hover(
      function () {
        var $this = $(this);

        $this.addClass('open').children('.collapse').collapse('show');

        var timer = setInterval(function () {
          if ($this.hasClass('open') && ($this.children('.collapse-child').css('display') === 'none')) {
            $this.children('.collapse').collapse('show');
          }

          if ($this.children('.collapse').is(":visible")) {
            clearTimeout(timer);
          }
        }, 100);
      },
      function () {
        var $this = $(this);

        $this.removeClass('open');

        var timer = setInterval(function () {
          if (!$this.hasClass('open') && ($this.children('.collapse-child').css('display') === 'block')) {
            $this.children('.collapse').collapse('hide');
          }

          if ($this.children('.collapse').is(":hidden")) {
            clearTimeout(timer);
          }
        }, 100);
      }
    );
  }
});


/*
 * Sync multi tab btn for Bootstrap tab.js
 * ---------------------------------------------------*/
jQuery(document).ready(function ($) {
  $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
    var $container = $(e.target).parents('[data-target="tab-container"]');
    $container.find('.active').removeClass('active');
    $container.find('[data-target="' + $(e.target).data('target') + '"]').parent('li').addClass('active');
  });
});


/*
 * Make header fixed after scroll
 * ----------------------------------------------- */
(function () {
  var $win = $(window);
  var $cloneNavContainer = $('<div class="cloned-nav-container"></div>');
  var $nav = $('.navbar-simple');
  var $navCloned = $nav.clone(true);
  var scrolledClass = 'is-scrolled';

  if ($nav.length === 0) {
    return false;
  }

  var formTop = $('#section-form').offset().top - 780;

  $navCloned.find('#navbar').attr('id', 'navbar-cloned');
  $navCloned.find('[data-target="#navbar"]').attr('data-target', '#navbar-cloned');

  $nav.parent().append($cloneNavContainer.append($navCloned));

  $win.on('load scroll', function () {
    var windowTop = $(window).scrollTop();

    if (windowTop < 600 || formTop < windowTop) {
      $cloneNavContainer.removeClass(scrolledClass);
    } else {
      $cloneNavContainer.addClass(scrolledClass);
    }
  });
})();


/*
 * menu-trigger btn
 * Required <div class="cloned-nav-container"></div>
 * ----------------------------------------------- */
(function () {
  var $menuTrigger = $('.menu-trigger');

  $menuTrigger.on('click', function (e) {
    e.preventDefault();
  });

  $.each($menuTrigger, function (i) {
    var $_this = $(this);
    var id = $_this.attr('data-target');

    $(id).on('show.bs.collapse hide.bs.collapse', function (e) {
      if ('#' + $(e.target).attr('id') === id) {
        $_this.toggleClass('active');
      }
    });
  });
})();


/*
 * PC CVエリア（header）
 * ----------------------------------------------- */
jQuery(document).ready(function ($) {
  var $jsHeader = $(".js-header");

  $(window).on('load scroll', function () {
    if (isMobile) {
      return false;
    }

    $jsHeader.each(function () {
      var scroll = $(window).scrollTop();
      var formOffset = $('#form-title').offset().top;

      if (scroll > 500) {
        $jsHeader.addClass('is-scrolled');
      } else {
        $jsHeader.removeClass('is-scrolled');
      }

      if (scroll > 600) {
        $jsHeader.addClass('is-transition');
      } else {
        $jsHeader.removeClass('is-transition');
      }

      if ((formOffset - 400) > scroll && scroll > 700) {
        $jsHeader.addClass('is-show');
      } else {
        $jsHeader.removeClass('is-show');
      }
    });
  });
});


/*
 * SP CVエリア（フッター追従ボタン）
 * ----------------------------------------------- */
jQuery(document).ready(function ($) {
  var $anchor = $('#section-form');

  $(window).on('load scroll', function () {
    if (!$anchor.length) {
      return false;
    }

    $(".js-cv").each(function () {
      var windowTop = $(window).scrollTop();
      var formTop = $anchor.offset().top - 780;

      if (windowTop < 90 || formTop < windowTop) {
        $(this).addClass('is-hidden');
      } else {
        $(this).removeClass('is-hidden');
      }
    });
  });
});


/*
 *
 * ----------------------------------------------- */
jQuery(document).ready(function ($) {
  (function () {
    $('.text-fluffy').text(function () {
      var str = $(this).text().trim();
      var html = '';

      for (i = 0; i < str.length; i++) {
        html += '<span>' + str.charAt(i) + '</span>';
      }

      $(this).empty().html(html);
    });
  })();

  setTimeout(function () {
    $('#text-fluffy-1').addClass('active');
  }, 100);

  setTimeout(function () {
    $('#text-fluffy-2').addClass('active');
  }, 2400);
});



/*
 *
 * ----------------------------------------------- */
// jQuery(document).ready(function ($) {
//   var waypoints = $('#section-concept').waypoint({
//     handler: function (direction) {
//       $(this.element).addClass('active');
//
//       this.destroy();
//     },
//     offset: '25%'
//   });
// });
