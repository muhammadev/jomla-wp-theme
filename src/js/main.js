import "viewerjs/dist/viewer.css";
import "../sass/style.scss";

import Viewer from "viewerjs";

jQuery(document).ready(function ($) {
  const isRTL = $("html").attr("dir") === "rtl";

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
    console.log(container, viewerContainer);
    const mainSliderViewer = new Viewer(viewerContainer, {
      movable: false,
      title: false,
      navbar: true,
      toolbar: false,
    });

    // Handle image click for Viewer.js
    $(container)
      .find(".main-slider")
      .on("click", "img", function () {
        const dataIndex = $(this).attr("data-index");
        console.log(dataIndex);

        if (dataIndex !== undefined) {
          mainSliderViewer.view(dataIndex);
        }
      });
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
    console.log(colorName);
    
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
});
