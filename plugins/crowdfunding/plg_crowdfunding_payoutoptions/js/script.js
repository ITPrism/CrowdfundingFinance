jQuery(document).ready(function() {

    // Initialize the form
    jQuery("#js-cfpayoutoptions-form").on("submit", function(event) {
        event.preventDefault();

        var url = jQuery(this).attr("action");
        var fields = jQuery(this).serializeJSON();

        // Set the token.
        var token = jQuery("#js-cfextras-form-token").serializeJSON();
        jQuery.extend(fields, token);

        jQuery.ajax({
            url: url,
            type: "POST",
            data: fields,
            dataType: "text json",
            beforeSend: function() {
                jQuery("#js-cfpayoutoptions-ajax-loader").show();
            }
        }).done(function(response) {

                if(!response.success) {
                    PrismUIHelper.displayMessageFailure(response.title, response.text);
                } else {
                    PrismUIHelper.displayMessageSuccess(response.title, response.text);
                }

                // Hide ajax loader.
                jQuery("#js-cfpayoutoptions-ajax-loader").hide();

            }
        );

    });

});