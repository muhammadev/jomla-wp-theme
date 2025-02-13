jQuery(function ($) {
  // if brand page
  if (window.location.pathname.includes("brand")) {
    const tabParam = window.location.search.includes("tab=")
      ? window.location.search.split("tab=")[1].split("&")[0]
      : "products";

    function showTab(tab) {
      $(".brand-videos").hide();
      $(".brand-sale").hide();
      $(".brand-products").hide();
      $(`.brand-${tab}`).show();

      $(".brand-tabs a").removeClass("active");
      $(`.brand-tabs a[href="?tab=${tab}"]`).addClass("active");
    }

    // show the tab based on the tabParam
    showTab(tabParam);

    // show the tab based on the clicked tab
    $(".brand-tabs a").on("click", function (e) {
      e.preventDefault();
      const tab = $(this).attr("href").split("=")[1];
      showTab(tab);

      // update the url with the new tab
      window.history.pushState({}, "", window.location.pathname + "?tab=" + tab);
    });
  }
});

