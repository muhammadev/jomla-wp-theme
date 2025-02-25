import "viewerjs/dist/viewer.css";
import "../sass/style.scss";

import Viewer from "viewerjs";
import "./utils";
import "./brand-utils";
import "./home-filter";
import "./home";

// add loading class to body when there's a request
jQuery(document).ajaxStart(function () {
  jQuery("body").addClass("loading");
});

jQuery(document).ajaxStop(function () {
  jQuery("body").removeClass("loading");
});

jQuery(document).ready(function ($) {
  const isRTL = $("html").attr("dir") === "rtl";
  window.isRTL = isRTL;

  function initMainSlider(container) {
    // unslick all sliders
    const mainSliders = $(".main-slider-container .main-slider");
    mainSliders.each(function () {
      if ($(this).hasClass("slick-initialized")) $(this).slick("unslick");
    });

    // Hide all main sliders and show only the active one
    $(".main-slider-container").hide();
    $(container).show();

    $(container)
      .find(".main-slider")
      .slick({
        rtl: isRTL,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: false,
        asNavFor: `.nav-slider[data-index="${$(container).data("index")}"]`, // Sync with Nav Slider
      });

    // Initialize Viewer.js for the current viewer container
    const viewerContainer = $(container).find("#viewer")[0];
    if (viewerContainer) {
      const mainSliderViewer = new Viewer(viewerContainer, {
        movable: true,
        title: false,
        navbar: true,
        toolbar: true,
      });

      // Handle image click for Viewer.js
      $(container)
        .find(".main-slider")
        .on("click", "img", function () {
          const dataIndex = $(this).attr("data-index");

          if (dataIndex !== undefined) {
            mainSliderViewer.view(dataIndex);
          }
        });
    }
  }

  function initNavSlider(activeNavSlider, activeMainSlider) {
    // unslick all sliders
    const navSliders = $(".nav-slider");
    navSliders.each(function () {
      if ($(this).hasClass("slick-initialized")) $(this).slick("unslick");
    });

    // Hide all nav sliders and show only the active one
    $(".nav-slider").hide();
    $(activeNavSlider).show();

    // Initialize the active nav slider
    $(activeNavSlider).slick({
      rtl: isRTL,
      slidesToShow: 1,
      slidesToScroll: 1,
      asNavFor: $(activeMainSlider), // Sync with Main Slider
      dots: true,
      arrows: false,
      focusOnSelect: true,
      centerMode: true,
      infinite: true,
    });
  }

  // Show only the active color's elements and initialize its sliders
  function activateColor(index, colorName) {
    // Reinitialize sliders for the active color
    initMainSlider(`.main-slider-container[data-index="${index}"]`);
    initNavSlider(
      `.nav-slider[data-index="${index}"]`,
      `.main-slider-container[data-index="${index}"] .main-slider`
    );

    $("#active_color").text(colorName);
  }

  // Event listener for color-option buttons
  $(".color-option").on("click", function () {
    const colorIndex = $(this).data("index");
    const colorName = $(this).attr("title");
    activateColor(colorIndex, colorName);

    // Set the active color
    $(".color-option").removeClass("active");
    $(this).addClass("active");
  });

  // Initialize the first color by default
  const defaultColorIndex = $(".color-option").first().data("index");
  activateColor(defaultColorIndex);

  // initialize the product archive slider
  const productArchiveSlider = $(".product-archive-slider");
  productArchiveSlider.slick({
    rtl: isRTL,
    slidesToShow: 4,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
    arrows: true,
  });

  $(".modal-trigger").on("click", function () {
    const modalId = $(this).data("modal");

    $(`#${modalId}`).fadeIn({
      duration: 300,
    });

    $("body").addClass("overflow-hidden");

    $(document).on("click", `#${modalId}`, function (e) {
      if (e.target.closest(".modal-content") === null) {
        $(`#${modalId}`).fadeOut({
          duration: 300,
        });
        $("body").removeClass("overflow-hidden");
        $(document).off(`click`, `#${modalId}`);
      }
    });
  });

  $(".close").on("click", function () {
    $(this).closest(".modal").fadeOut({
      duration: 300,
    });
    $("body").removeClass("overflow-hidden");
  });
});
