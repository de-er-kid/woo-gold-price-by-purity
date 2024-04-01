jQuery(document).ready(function($) {
    // Check the initial value of select#jewellery_field
    if ($('#jewellery_field').val() !== '0') {
        $('#_weight').parent().show();
        $('#additional_charges_field').parent().show();
    } else {
        $('#_weight').parent().hide();
        $('#additional_charges_field').parent().hide();
    }

    // Show/hide the additional fields based on the selected option
    $('#jewellery_field').on('change', function() {
        if ($(this).val() !== '0') {
            $('#_weight').parent().show();
            $('#additional_charges_field').parent().show();
        } else {
            $('#_weight').parent().hide();
			$('#_weight').val('');
            $('#additional_charges_field').parent().hide();
			$('#additional_charges_field').val('');
        }
    });
});
