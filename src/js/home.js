jQuery(document).ready(function ($) {
  const isRTL = $("html").attr("dir") === "rtl";
  window.isRTL = isRTL;

  $(".featured-products-slider").slick({
    rtl: window.isRTL,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          dots: true,
        },
      },
    ],
    fade: true,
    autoplay: false,
    autoplaySpeed: 5000,
    pauseOnHover: false,
    pauseOnFocus: true,
    pauseOnDotsHover: true,
    pauseOnFocus: true,
  });
});
