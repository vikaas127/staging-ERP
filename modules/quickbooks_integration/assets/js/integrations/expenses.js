var fnServerParams = {};

(function($) {
	"use strict";

	fnServerParams = {
      "software": '[name="software"]',
      "status": '[name="status"]',
      "from_date": '[name="from_date"]',
      "to_date": '[name="to_date"]',
    };


	$('select[name="status"]').on('change', function() {
	    init_expenses_table();
	});

	$('input[name="from_date"]').on('change', function() {
		init_expenses_table();
	});

	$('input[name="to_date"]').on('change', function() {
		init_expenses_table();
	});

  init_expenses_table();
})(jQuery);

function init_expenses_table() {
"use strict";

 if ($.fn.DataTable.isDataTable('.table-expenses')) {
   $('.table-expenses').DataTable().destroy();
 }
 initDataTable('.table-expenses', admin_url + 'quickbooks_integration/expenses_table', [0], [0], fnServerParams, [0, 'desc']);
}

function manual_sync(invoker){
    "use strict";

    var data = {};
    data.id = $(invoker).data('id');
    data.type = $(invoker).data('type');
    data.software = $(invoker).data('software');

    var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>'; 
      $('#box-loadding').html(html);
      setTimeout(function() {
        $('#box-loadding').html('');
        alert_float('warning', 'The synchronization all process can take a long time to complete');
      }, 60*1000);

    $.post(admin_url + 'quickbooks_integration/manual_sync', data).done(function(response) {
        response = JSON.parse(response);
        if (response.success === true || response.success == 'true') { 
          $('#box-loadding').html('');
          alert_float('success', response.message); 
          init_expenses_table();
        }else{
          $('#box-loadding').html('');
          alert_float('danger', response.message); 
        }
    });
}

function sync_transaction(invoker){
    "use strict";

    var data = {};
    data.type = 'expense';
    data.software = $('input[name="software"]').val();

    var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>'; 
      $('#box-loadding').html(html);
      setTimeout(function() {
        $('#box-loadding').html('');
        alert_float('warning', 'The synchronization all process can take a long time to complete');
      }, 60*1000);

    $.post(admin_url + 'quickbooks_integration/sync_transaction_from_accounting', data).done(function(response) {
        response = JSON.parse(response);
        if (response.success === true || response.success == 'true') { 
          $('#box-loadding').html('');
          alert_float('success', response.message); 
          init_expenses_table();
        }else{
          $('#box-loadding').html('');
          alert_float('danger', response.message); 
        }
    });
}