jQuery(document).ready(function() {
	
	// Validation script
    Joomla.submitbutton = function(task){
        if (task == 'payouts.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    };

    jQuery("#payoutsList").on("click", ".js-cf-additionalinfo", function(event){
        event.preventDefault();

        var pid = jQuery(this).data("pid");
        var type = jQuery(this).data("type");
        var title = jQuery(this).data("title");

        var url = "index.php?option=com_crowdfundingfinance&task=payout.getAdditionalInfo&format=raw&id=" + pid + "&type="+type;

        jQuery.ajax({
            url: url,
            type: "GET",
            dataType: "text html",
            beforeSend: function() {
                jQuery("#js-cf-modal-title").text(title);
            }
        }).done(function(response) {

                jQuery("#js-cffinance-payouts-body").html(response);
                jQuery('#js-cffinance-payouts-modal').modal('show');

        });

    });

    jQuery("#js-cffinance-payouts-btn").on("click", function(){
        jQuery('#js-cffinance-payouts-modal').modal('hide');
    });
    
});