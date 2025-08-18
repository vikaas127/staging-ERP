<script>
	"use strict";
	
	appValidateForm($("body").find('#add_update_work_center'), {
		'work_center_name': 'required',
	});    
	$(document).ready(function() {
    // Function to toggle vendor field
    function toggleVendorField() {
      var selected = $('input[name="is_subcontract"]:checked').val();
      if (selected == '1') {
        $('#vendor-container').show();
      } else {
        $('#vendor-container').hide();
      }
    }
    

    // Initial call
    toggleVendorField();

    // Trigger on change
    $('input[name="is_subcontract"]').on('change', function() {
      toggleVendorField();
    });
    $('#vendor').on('change', function () {
      var selectedOption = $(this).find('option:selected');
      var vendorName = selectedOption.data('company') || '';
      $('#vendor_name').val(vendorName);
    });
  });
  
	
</script>