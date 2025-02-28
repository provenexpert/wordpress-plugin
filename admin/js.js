jQuery(document).ready(function($) {
  $( 'body.settings_page_provenExpert h1' ).each( function () {
    let button = document.createElement( 'a' );
    button.className = 'review-hint-button page-title-action';
    button.href = provenExpertJsVars.review_url;
    button.innerHTML = provenExpertJsVars.title_rate_us;
    button.target = '_blank';
    this.after( button );
  } )

  // save to hide transient-messages via ajax-request
  $('div[data-dismissible] button.notice-dismiss').on('click',
    function (event) {
      event.preventDefault();
      let $this = $(this);
      let attr_value, option_name, dismissible_length, data;
      attr_value = $this.closest('div[data-dismissible]').attr('data-dismissible').split('-');

      // Remove the dismissible length from the attribute value and rejoin the array.
      dismissible_length = attr_value.pop();
      option_name = attr_value.join('-');
      data = {
        'action': 'provenexpert_dismiss_admin_notice',
        'option_name': option_name,
        'dismissible_length': dismissible_length,
        'nonce': provenExpertJsVars.dismiss_nonce
      };

      // run ajax request to save this setting
      $.post(provenExpertJsVars.ajax_url, data);
      $this.closest('div[data-dismissible]').hide('slow');
    }
  );

  /**
   * Initiate color picker on load of page.
   */
  if ( $.isFunction( jQuery.fn.wpColorPicker ) ) {
    $( 'input.provenexpert-color-picker' ).wpColorPicker();
  }

  /**
   * Initiale color picker if AJAX-request in classic widget page trigger events.
   */
  $(document).on( 'widget-added', function() {
    $( 'input.provenexpert-color-picker' ).wpColorPicker();
  });
  $(document).on( 'widget-updated', function() {
    $( 'input.provenexpert-color-picker' ).wpColorPicker();
  });
});
