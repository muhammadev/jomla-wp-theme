jQuery(document).ready(function ($) {
  $(".menu-item-has-children > .menu-toggler").on("click", function (e) {
    e.preventDefault();
    // close all other sub-menus if not the same parent
    $(".menu-item-has-children > .sub-menu")
      .not($(this).parents(".menu-item-has-children").children(".sub-menu"))
      .slideUp();

    // open sub-menu
    $(this).next().slideToggle();
  });

  // on click away close all other sub-menus
  $(document).on("click", function (e) {
    if (!$(e.target).closest(".menu-item-has-children").length) {
      $(".menu-item-has-children .sub-menu").slideUp();
    }
  });

  // responsive menu
  $("#masthead .responsive-menu-sidebar").sidebar({ side: "left" });

  function toggleMenuSidebar() {
    $("#masthead .responsive-menu-sidebar").trigger("sidebar:toggle");
    $("#masthead .responsive-menu-sidebar .backdrop").toggleClass("hidden");
    $("#masthead .responsive-menu-sidebar #close-sidebar").toggleClass(
      "hidden"
    );
  }

  $("#masthead #menu-toggler").on("click", toggleMenuSidebar);
  // close sidebar on backdrop click
  $("#masthead .responsive-menu-sidebar .backdrop").on(
    "click",
    toggleMenuSidebar
  );
  // close sidebar on close button click
  $("#masthead .responsive-menu-sidebar #close-sidebar").on(
    "click",
    toggleMenuSidebar
  );
});
