import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

jQuery(document).ready(function($) {

    $(function () {
      $('[data-bs-toggle="popover"]').popover().click(function(e) {
        // e.preventDefault();
      });
    });

    $('.shop-menu-item > .nav-link').click(function(e) {
      e.preventDefault();
      $('#offcanvasShop').offcanvas('show');
    });

    $('#offcanvasShop .cat-parent > a').click(function(e) {
      e.preventDefault();
      $(this).next('.children').slideToggle();
    });

    $('#offcanvasShop .cat-parent > a').each(function() {
      var href = $(this).attr('href');
      $(this).next('.children').prepend('<li class="cat-item cat-item-all"><a href="'+ href +'">Ver todo</a></li>');
    });

    var body = $('body');
    var scrolled = false;
    var navbarClasses = $('#main-nav').attr('class');
    var navbarClassesBeforeOffcanvas = navbarClasses;

    // // switch to dark navbar on offcanvas show
    // $('#main-nav .offcanvas').on('show.bs.offcanvas', function () {
    //     navbarClassesBeforeOffcanvas = $('#main-nav').attr('class');
    //     $('#main-nav .navbar').removeClass('navbar-light').addClass('navbar-dark');
    // });

    // // // switch to light navbar on offcanvas hide
    // $('#main-nav .offcanvas').on('hide.bs.offcanvas', function () {
    //     $('#main-nav .navbar').removeClass('navbar-dark').addClass(navbarClassesBeforeOffcanvas);
    // });



    jQuery(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= 25) {
            body.addClass("scrolled");
            scrolled = true;
            // $('#main-nav .navbar').removeClass('navbar-dark').addClass('navbar-light bg-white');
        } else {
            body.removeClass("scrolled");
            scrolled = false;
            // $('#main-nav .navbar').removeClass('navbar-light bg-white').addClass(navbarClasses);
        }

        if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            body.addClass("near-bottom");
        } else {
            body.removeClass("near-bottom");
        }
    });

    $('#navbarNavDropdown').on('show.bs.collapse', function () {
        body.addClass('menu-open');
    });
    $('#navbarNavOffcanvas').on('show.bs.offcanvas', function () {
        body.addClass('menu-open');
    });

    $('#navbarNavDropdown').on('hide.bs.collapse', function () {
        body.removeClass('menu-open');
    });
    $('#navbarNavOffcanvas').on('hide.bs.offcanvas', function () {
        body.removeClass('menu-open');
    });

    
    $('.alert-go-to-my-account button.btn-close').click(() => {
        document.cookie = "account_notice_shown=true; path=/; max-age=" + 60 * 60 * 24 * 30; // Expires in 30 days
    });
    $('.alert-welcome button.btn-close').click(() => {
      document.cookie = "alert_welcome_shown=true; path=/; max-age=" + 60 * 60 * 24 * 7; // Expires in 7 days
    });

    $(".nav-link").on("shown.bs.tab", function (e) {
      var href = $(this).attr("href");
      var offset = $("#wrapper-navbar").height();
      $("html, body").animate(
      {
        scrollTop: $(href).offset().top - offset
      },
      "fast"
      );
    });

});


/* Carruseles */
const prevArrow = '<button class="slick-prev slick-prev-custom" arial-label="Previous" type="button"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20 40C8.9543 40 -2.7141e-06 31.0457 -1.74846e-06 20C-7.8281e-07 8.9543 8.95431 -2.7141e-06 20 -1.74846e-06C31.0457 -7.8281e-07 40 8.9543 40 20C40 31.0457 31.0457 40 20 40ZM16.1206 13.5198C15.7554 13.1055 15.1632 13.1055 14.798 13.5198L9.58704 19.4308C9.22182 19.8451 9.22182 20.5168 9.58704 20.931L14.798 26.8421C15.1632 27.2563 15.7554 27.2563 16.1206 26.8421C16.4858 26.4278 16.4858 25.7561 16.1206 25.3418L12.4912 21.2248L29.6865 21.2248C30.2388 21.2248 30.6865 20.7771 30.6865 20.2248C30.6865 19.6725 30.2388 19.2248 29.6865 19.2248L12.4138 19.2248L16.1206 15.02C16.4858 14.6057 16.4858 13.934 16.1206 13.5198Z" fill="var(--bs-dark)"/></svg></button>';
const nextArrow = '<button class="slick-next slick-next-custom" arial-label="Next" type="button"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20 3.49691e-06C31.0457 5.4282e-06 40 8.95431 40 20C40 31.0457 31.0457 40 20 40C8.9543 40 1.56562e-06 31.0457 3.49691e-06 20C5.4282e-06 8.95431 8.95431 1.56562e-06 20 3.49691e-06ZM23.8794 26.4802C24.2446 26.8945 24.8368 26.8945 25.202 26.4802L30.413 20.5692C30.7782 20.1549 30.7782 19.4833 30.413 19.069L25.202 13.1579C24.8368 12.7437 24.2446 12.7437 23.8794 13.1579C23.5142 13.5722 23.5142 14.2439 23.8794 14.6582L27.5088 18.7752L10.3135 18.7752C9.7612 18.7752 9.31348 19.2229 9.31348 19.7752C9.31348 20.3275 9.76119 20.7752 10.3135 20.7752L27.5862 20.7752L23.8794 24.98C23.5142 25.3943 23.5142 26.066 23.8794 26.4802Z" fill="var(--bs-dark)"/></svg></button>';

jQuery('.slick-slider-default').slick({
  dots: false,
  arrows: true,
  infinite: true,
  speed: 100,
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: false,
  adaptiveHeight: true,
  prevArrow: prevArrow,
  nextArrow: nextArrow,
});

jQuery('.slick-top-bar').slick({
  dots: false,
  arrows: false,
  infinite: true,
  speed: 100,
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  prevArrow: prevArrow,
  nextArrow: nextArrow,
  vertical: true,
});

jQuery('.slick-carousel, .wp-block-group.is-style-slick-carousel > .wp-block-group__inner-container, .wp-block-gallery.is-style-slick-carousel').slick({
  dots: true,
  arrows: true,
  infinite: false,
  speed: 100,
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: false,
  prevArrow: prevArrow,
  nextArrow: nextArrow,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

jQuery('.slick-main-categories-carousel').slick({
  dots: false,
  arrows: true,
  infinite: true,
  speed: 100,
  slidesToShow: 2,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 2000,
  prevArrow: prevArrow,
  nextArrow: nextArrow,
  responsive: [
    {
      breakpoint: 9999999,
      settings: "unslick"
    },
    // {
    //   breakpoint: 992,
    //   settings: {
    //     slidesToShow: 2
    //   }
    // },
    {
      breakpoint: 783,
      settings: {
        slidesToShow: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

jQuery('.slick-subcategories-carousel').slick({
  dots: false,
  arrows: true,
  infinite: true,
  speed: 100,
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 2000,
  prevArrow: prevArrow,
  nextArrow: nextArrow,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 783,
      settings: {
        slidesToShow: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

jQuery('.slick-marcas-carousel, .wp-block-group.is-layout-flex.is-style-slick-carousel-logos, .wp-block-group.is-style-slick-carousel-logos > .wp-block-group__inner-container, .wp-block-gallery.is-style-slick-carousel-logos').slick({
  dots: false,
  arrows: false,
  infinite: true,
  variableWidth: true,
  speed: 100,
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: true,
  prevArrow: prevArrow,
  nextArrow: nextArrow,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4
      }
    },
    {
      breakpoint: 782,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    }    
    
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

jQuery('.products-shortcode-carousel ul').slick({
  dots: false,
  arrows: true,
  infinite: false,
  speed: 100,
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  prevArrow: prevArrow,
  nextArrow: nextArrow,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4
      }
    },
    {
      breakpoint: 782,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    }    
    
  ]
});

jQuery('#header-promos').slick({
  dots: false,
  infinite: true,
  speed: 100,
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 4100,
  prevArrow: prevArrow,
  nextArrow: nextArrow
  // responsive: [
  //   {
  //     breakpoint: 9999999,
  //     settings: "unslick"
  //   },
  //   {
  //     breakpoint: 782,
  //     settings: {
  //       slidesToShow: 1,
  //       slidesToScroll: 1
  //     }
  //   }
  // ]
});

// Animate each element with the class 'wp-block-cover'
jQuery('.wp-block-cover, .summary').each(function(index) {
  gsap.from(this, {
    opacity: 0,
    scrollTrigger: this,
    y: 100,
  });
});

// Animate each element with the class 'wp-block-media-text__media'
jQuery('.wp-block-media-text__media').each(function() {
  gsap.from(this, {
    opacity: 0,
    scrollTrigger: this,
    x: -100,
  });
});

ScrollTrigger.batch(".is-layout-grid > *, .product-category, li.product, .subcategory > .card-body", {
  onEnter: elements => {
    gsap.from(elements, {
      opacity: 0,
      y: 60,
      autoAlpha: 0,
      stagger: 0.1,
    });
  },
  once: true
});