(function ($, Drupal) {
  Drupal.behaviors.nica_custom_print = {
    attach: function (context, settings) {
      if ($("#container-print").length) {
        $("aside.col-sm-3").hide();
        $("div.table-responsive").addClass('table-bordered');
        $("div.table-bordered").removeClass('table-responsive');
        $("#nicaprint").click(function () {
          $("#container-print").printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            printContainer: true,
            loadCSS: "../css/style.css",
            pageTitle: "Curriculum Vitae | Nica Portal",
            removeInline: false,
            printDelay: 333,
            header: null,
            formValues: true
          });
        });
      }
    }
  }
})(jQuery, Drupal);
