 <script>
   (function($) {
    "use strict";

  $('input[name="commodity_id"]').val("<?php echo new_html_entity_decode($commodity_item->id) ;?>");



    //inventory stock
    var ProposalServerParams = {
        "warehouse_ft": "[name='warehouse_filter[]']",
        "commodity_ft": "[name='commodity_id']",
        "alert_filter": "[name='alert_filter']",
    };


    var table_inventory_stock = $('table.table-table_inventory_stock');
   var  _table_api = initDataTable(table_inventory_stock, admin_url+'warehouse/table_inventory_stock', [], [], ProposalServerParams, [0, 'desc']);

   $('.table-table_inventory_stock').DataTable().columns([0,1,2,3,6,7,8,9]).visible(false, false);

    $.each(ProposalServerParams, function(i, obj) {
        $('select' + obj).on('change', function() {  
            table_inventory_stock.DataTable().ajax.reload();
        });
    });

    function updateVisibleTotals() {
        let table = $('.table-table_inventory_stock').DataTable();
        let totalInventory = 0;
        let totalRateValue = 0;

        table.rows({ search: 'applied' }).every(function () {
            let data = this.data();

        let inventoryRaw = data[5];
        let rateRaw = data[7];

        let cleanedRateRaw = typeof rateRaw === 'string' ? rateRaw.replace(/,/g, '') : rateRaw;

        let inventory = parseFloat(inventoryRaw);
        let rate = parseFloat(cleanedRateRaw);

        if (isNaN(inventory)) inventory = 0;
        if (isNaN(rate)) rate = 0;

        let product = inventory * rate;

        totalInventory += inventory;
        totalRateValue += product;
    });

    $('#total-inventory-card').text(totalInventory.toLocaleString());
    $('#total-rate-card').text(totalRateValue.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
}

// Update totals only when the table is drawn
$('.table-table_inventory_stock').on('draw.dt', function () {
    updateVisibleTotals();
});




    //expriry date
    var ProposalServerParams = {
        "warehouse_ft": "[name='warehouse_filter[]']",
        "commodity_ft": "[name='commodity_id']",
        "alert_filter": "[name='alert_filter']",
    };
    var table_view_commodity_detail = $('table.table-table_view_commodity_detail');
   var  _table_api = initDataTable(table_view_commodity_detail, admin_url+'warehouse/table_view_commodity_detail', [], [], ProposalServerParams);
    $.each(ProposalServerParams, function(i, obj) {
        $('select' + obj).on('change', function() {  
            table_view_commodity_detail.DataTable().ajax.reload();
        });
    });



//transaction, history
var ProposalServerParams = {
        "warehouse_ft": "[name='warehouse_filter[]']",
        "commodity_ft": "[name='commodity_id']",
        
    };

  var   table_warehouse_history = $('table.table-table_warehouse_history');
   var  _table_api = initDataTable(table_warehouse_history, admin_url+'warehouse/table_warehouse_history', [], [], ProposalServerParams, [0, 'desc']);

   $('.table-table_warehouse_history').DataTable().columns([0]).visible(false, false);

    $.each(ProposalServerParams, function(i, obj) {
        $('select' + obj).on('change', function() {  
            table_warehouse_history.DataTable().ajax.reload();
        });
    });

    init_ajax_search('items','#item_select_print_barcode.ajax-search',undefined,admin_url+'warehouse/wh_commodity_code_search_all');
    
})(jQuery);

</script>