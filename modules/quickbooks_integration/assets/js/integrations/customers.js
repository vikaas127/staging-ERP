var fnServerParams;
(function($) {
    "use strict";

  fnServerParams = {
      "status": '[name="status"]',
      "software": '[name="software"]',
    };

  init_customers_table();

  $('select[name="status"]').on('change', function() {
    init_customers_table();
  });

})(jQuery);

function init_customers_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-customers')) {
     $('.table-customers').DataTable().destroy();
  }
  initDataTable('.table-customers', admin_url + 'quickbooks_integration/customers_table', [0], [0], fnServerParams, [0, 'asc']);
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
          init_customers_table();
        }else{
          $('#box-loadding').html('');
          alert_float('danger', response.message); 
        }
    });
}


function sync_transaction(invoker){
    "use strict";

    var data = {};
    data.type = 'customer';
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
          init_customers_table();
        }else{
          $('#box-loadding').html('');
          alert_float('danger', response.message); 
        }
    });
}