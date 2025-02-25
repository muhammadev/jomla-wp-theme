jQuery(document).ready(function ($) {
  const isHome = ["/", "/ar", "/ar/"].includes(window.location.pathname);
  if (!isHome) return;

  const $filteredProducts = $("#filtered-products");
  const $productFilterForm = $("#product-filter-form");
  const $clearFilters = $("#clear-filters");

  const formFields = {
    collection: $productFilterForm.find("select[name='collection']"),
    brand: $productFilterForm.find("select[name='brand']"),
    color: $productFilterForm.find("select[name='color']"),
    sale: $productFilterForm.find("input[name='sale']"),
    priceFrom: $productFilterForm.find("input[name='price-from']"),
    priceTo: $productFilterForm.find("input[name='price-to']"),
  };

  formFields.collection.on("change", applyFilters);
  formFields.brand.on("change", applyFilters);
  formFields.color.on("change", applyFilters);
  formFields.sale.on("change", applyFilters);
  formFields.priceFrom.on("input", window.myDebounce(applyFilters, 500));
  formFields.priceTo.on("input", window.myDebounce(applyFilters, 500));

  function hasFilters() {
    return (
      formFields.collection.val() ||
      formFields.brand.val() ||
      formFields.color.val() ||
      formFields.sale.is(":checked") ||
      formFields.priceFrom.val() ||
      formFields.priceTo.val()
    );
  }

  function applyFilters() {
    if (!hasFilters()) {
      $clearFilters.hide();
    } else {
      $clearFilters.show();
    }

    const filter = {
      collection: {
        value: formFields.collection.val(),
        label: formFields.collection.find("option:selected").text(),
      },
      brand: {
        value: formFields.brand.val(),
        label: formFields.brand.find("option:selected").text(),
      },
      color: {
        value: formFields.color.val(),
        label: formFields.color.find("option:selected").text(),
      },
      sale: {
        value: formFields.sale.is(":checked") ? 1 : 0,
        label: formFields.sale.is(":checked") ? "Sale" : "No Sale",
      },
      price_from: {
        value: formFields.priceFrom.val(),
        label: formFields.priceFrom.val(),
      },
      price_to: {
        value: formFields.priceTo.val(),
        label: formFields.priceTo.val(),
      },
    };

    let filterData = {};

    for (const key in filter) {
      if (!!filter[key].value) {
        filterData[key] = filter[key].value;
      }
    }

    filterProducts(filterData);
  }

  function filterProducts(filter) {
    $.ajax({
      url: $productFilterForm.attr("action"),
      method: "POST",
      data: { action: "filter_products", filter_data: filter },
      success: function (response) {
        $filteredProducts.html(response);
      },
      error: function (error) {
        console.error("Error filtering products:", error);
      },
    });
  }

  applyFilters();

  $clearFilters.on("click", function () {
    formFields.collection.val("");
    formFields.brand.val("");
    formFields.sale.prop("checked", false);
    formFields.priceFrom.val("");
    formFields.priceTo.val("");
    formFields.color.val("");

    applyFilters();
  });

  function repositionFilter() {
    if (window.matchMedia("(max-width: 1024px)").matches) {
      // move the filter to the modal
      $("#modal-filter-container").append($productFilterForm);
      $("#inline-filter-container").find($productFilterForm).remove();
    } else {
      // move the filter to the
      $("#inline-filter-container").append($productFilterForm);
      $("#modal-filter-container").find($productFilterForm).remove();
    }
  }

  repositionFilter();

  //   $(window).on("resize", repositionFilter);
});
