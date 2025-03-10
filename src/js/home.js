jQuery(document).ready(function ($) {
  const isRTL = $("html").attr("dir") === "rtl";
  window.isRTL = isRTL;

  const productSlider = $(".featured-products-slider");

  if (productSlider.length) {
    productSlider.slick({
      rtl: isRTL,
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
      autoplay: true,
      autoplaySpeed: 5000,
      pauseOnHover: false,
      pauseOnFocus: true,
      pauseOnDotsHover: true,
    });
  }
});
