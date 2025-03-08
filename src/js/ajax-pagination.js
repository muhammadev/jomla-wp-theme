jQuery(document).ready(function ($) {
  $(document).on("click", ".ajax-link", function (e) {
    e.preventDefault();

    console.log("Ajax link clicked", this);

    const myEvent = new CustomEvent("ajaxPaginationTriggered", {
      detail: {
        url: $(this).attr("href"),
        key: $(this).data("key"),
      },
    });

    document.dispatchEvent(myEvent);
  });
});
