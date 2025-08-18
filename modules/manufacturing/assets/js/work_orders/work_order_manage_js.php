
<script>

	"use strict";

	var InvoiceServerParams={
		"manufacturing_order_filter": "[name='manufacturing_order_filter[]']",
		"products_filter": "[name='products_filter[]']",

		"status_filter": "[name='status_filter[]']",
		"customer_filter": "[name='customer_filter[]']" ,

	};
	var work_order_table = $('.table-work_order_table');
	initDataTable(work_order_table, admin_url+'manufacturing/work_order_table',[0],[0], InvoiceServerParams, [0 ,'desc']);

	var hidden_columns = [0];
	$('.table-work_order_table').DataTable().columns(hidden_columns).visible(false, false);

	$.each(InvoiceServerParams, function(i, obj) {
		$('select' + obj).on('change', function() {  
			console.log("Customer Filter Options Count:", $("[name='customer_filter[]']").find("option").length);
			work_order_table.DataTable().ajax.reload()
			.columns.adjust()
			.responsive.recalc();
		});
	});


</script>