(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  jQuery(function ($) {
    is_redirect_enabled();
    jQuery("#pi_dcw_global_redirect_custom_url").change(function () {
      global_redirect_toggle();
    });

    jQuery("#pi_dcw_global_redirect").change(function () {
      is_redirect_enabled();
    });

    addtocarttext();
  });

  function global_redirect_toggle() {
    var $ = jQuery;

    if ($("#pi_dcw_global_redirect_custom_url").is(":checked")) {
      $("#row_pi_dcw_global_redirect_to_page").fadeOut();
      $("#row_pi_dcw_global_custom_url").fadeIn();
    } else {
      $("#row_pi_dcw_global_custom_url").fadeOut();
      $("#row_pi_dcw_global_redirect_to_page").fadeIn();
    }
  }

  function is_redirect_enabled() {
    if ($("#pi_dcw_global_redirect").is(":checked")) {
      $("#row_pi_dcw_global_redirect_custom_url").fadeIn();
      global_redirect_toggle();
    } else {
      $("#row_pi_dcw_global_redirect_to_page").fadeOut();
      $("#row_pi_dcw_global_custom_url").fadeOut();
      $("#row_pi_dcw_global_redirect_custom_url").fadeOut();
    }
  }

  function addtocarttext() {
    jQuery("#pi_dcw_change_add_to_cart").change(function () {
      if ($("#pi_dcw_change_add_to_cart").is(":checked")) {
        $("#row_pi_dcw_add_to_cart_text").fadeIn();
        $("#row_pi_dcw_read_more_text").fadeIn();
        $("#row_pi_dcw_select_option_text").fadeIn();
      } else {
        $("#row_pi_dcw_add_to_cart_text").fadeOut();
        $("#row_pi_dcw_read_more_text").fadeOut();
        $("#row_pi_dcw_select_option_text").fadeOut();
      }
    });
    jQuery("#pi_dcw_change_add_to_cart").trigger('change');
  }
})(jQuery);
