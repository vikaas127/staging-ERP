<script>




     var data_color = <?php echo json_encode($data_color ?? []); ?>;
	$(document).ready(function() {
		setTimeout(function(){
		"use strict";  
			
			
		$('.work_instruction').click();

		  }, 1);
		  
		  
		  
	});


    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href"); 

    setTimeout(function () {
   

        if (target === '#scraps' && typeof scrap_tabs !== 'undefined') {
            scrap_tabs.render();
            scrap_tabs.refreshDimensions();
        }
    }, 100); 
});

	var time_trackings,scrap_tabs;
		(function($) {
		"use strict";  


		<?php if(isset($time_tracking_details)){ ?>
			var dataObject_pu = <?php echo json_encode($time_tracking_details) ; ?>;
		<?php }else{?>
			var dataObject_pu = [];
		<?php } ?>

		var hotElement1 = document.getElementById('time_tracking_hs');

		time_trackings = new Handsontable(hotElement1, {
			licenseKey: 'non-commercial-and-evaluation',

			contextMenu: true,
			manualRowMove: true,
			manualColumnMove: true,
			stretchH: 'all',
			autoWrapRow: true,
			rowHeights: 30,
			defaultRowHeight: 100,
			minRows: 10,
			maxRows: <?php echo new_html_entity_decode($rows); ?>,
			width: '100%',

			rowHeaders: true,
			colHeaders: true,
			autoColumnSize: {
				samplingRatio: 23
			},

			filters: true,
			manualRowResize: true,
			manualColumnResize: true,
			allowInsertRow: true,
			allowRemoveRow: true,
			columnHeaderHeight: 40,
			// colWidths:  [20, 20, 20,20],
			rowHeights: 30,
			rowHeaderWidth: [44],
			minSpareRows: 1,
			hiddenColumns: {
				columns: [0],
				indicators: true
			},

			columns: [
			{
				type: 'text',
				data: 'id',
			},
			
			
			{
				data: 'from_date',
				type: 'text',
				
			},
			{
				data: 'to_date',
				type: 'text',
			},
			{
				data: 'duration',
				type: 'numeric',
				numericFormat: {
					pattern: '0,0.00',
				},
			},
			{
				data: 'full_name',
				type: 'text',
			},

			],

			colHeaders: [

			'<?php echo _l('id'); ?>',
			'<?php echo _l('start_date'); ?>',
			'<?php echo _l('end_date'); ?>',
			'<?php echo _l('duration'); ?>',
			'<?php echo _l('staff_name'); ?>',
			],

			data: dataObject_pu,
		});
var scrapData = <?php echo isset($scrap_items) ? json_encode($scrap_items) : '[]'; ?>;
        console.log("Initializing Scrap Tab Data:", scrapData);

        var hotElement2 = document.getElementById('scrap_hqs');

        scrap_tabs = new Handsontable(hotElement2, {
            licenseKey: 'non-commercial-and-evaluation',
            contextMenu: true,
            manualRowMove: true,
            manualColumnMove: true,
            stretchH: 'all',
            autoWrapRow: true,
            rowHeights: 30,
            minRows: 10,
            maxRows: 40,
            width: '100%',
            rowHeaders: true,
            cells: function(row, col, prop) {
                var cellProperties = {};
                if (col > 2) {
                    cellProperties.renderer = firstRowRenderer;
                }
                return cellProperties;
            },
            colHeaders: true,
            autoColumnSize: {
                samplingRatio: 23
            },
            filters: true,
            manualRowResize: true,
            manualColumnResize: true,
            allowInsertRow: true,
            allowRemoveRow: true,
            columnHeaderHeight: 40,
            hiddenColumns: {
                columns: [0],
                indicators: true
            },
            columns: [
                { type: 'text', data: 'id' },
             {
                    type: 'text',
                    data: 'product_id',
                    renderer: customDropdownRenderer,
                    editor: false,
                    chosenOptions: {
                        data: <?php echo json_encode($product_for_hansometable ?? []); ?>
                    }
                },
                {
                    type: 'text',
                    data: 'unit_id',
                    renderer: customDropdownRenderer,
                    editor: false,
                    chosenOptions: {
                        data: <?php echo json_encode($unit_for_hansometable ?? []); ?>
                    }
                },
                { data: 'estimated_quantity', type: 'numeric', numericFormat: { pattern: '0,0.00' } ,readOnly:true},
                { data: 'actual_quantity', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
                { data: 'cost_allocation', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
                { data: 'reason', type: 'text' }
            ],
            colHeaders: [
                '<?php echo _l('id'); ?>',
                '<?php echo _l('product_label'); ?>',
                '<?php echo _l('unit_id'); ?>',
                '<?php echo _l('estimated_Quantity'); ?>',
                '<?php echo _l('actual_Quantity'); ?>',
                '<?php echo _l('cost_Allocation(%)'); ?>',
                '<?php echo _l('comment'); ?>',
            ],
            data: scrapData,
        });

	})(jQuery);


function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
		
		"use strict";
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.background = '#fff';
		if(data_color[row] != undefined){
			td.style.color = data_color[row];
			td.className = 'htRight';

		}
	}

	function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
		"use strict";
		var selectedId;
		var optionsList = cellProperties.chosenOptions.data;

		if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
			Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
			return td;
		}

		var values = (value + "").split("|");
		value = [];
		for (var index = 0; index < optionsList.length; index++) {

			if (values.indexOf(optionsList[index].id + "") > -1) {
				selectedId = optionsList[index].id;
				value.push(optionsList[index].label);
			}
		}
		value = value.join(", ");

		Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
		return td;
	}

	$('.mark_start_working').on('click', function() {
		"use strict";

		var work_order_id = $("input[name='work_order_id']").val();
		var manufacturing_order = $("input[name='manufacturing_order']").val();

		$.get(admin_url + 'manufacturing/mo_mark_as_start_working/' + work_order_id+'/'+manufacturing_order, function (response) {
			alert_float(response.status, response.message);

			location.reload();
		}, 'json');

	});

	$('.mark_pause').on('click', function() {
		"use strict";

		var work_order_id = $("input[name='work_order_id']").val();

		$.get(admin_url + 'manufacturing/mo_mark_as_mark_pause/' + work_order_id, function (response) {
			alert_float(response.status, response.message);

			location.reload();
		}, 'json');

	});

	$('.mark_done').on('click', function() {
		"use strict";

		var work_order_id = $("input[name='work_order_id']").val();
		var manufacturing_order_id = $("input[name='manufacturing_order']").val();

		$.get(admin_url + 'manufacturing/mo_mark_as_mark_done/' + work_order_id+'/'+ manufacturing_order_id, function (response) {
			alert_float(response.status, response.message);

			location.reload();
		}, 'json');

	});
	$('#saveScrapBtn').on('click', function() {
       var scrapData = scrap_tabs.getData(); // Get all data from Handsontable
    var workOrderId = $('#saveScrapBtn').data('work-order-id'); // Get from button attribute
    var manufacturingOrderId = $('#saveScrapBtn').data('manufacturing-order-id'); // Get from button attribute

    console.log("Initializing updateScrapData - Scrap Data:", scrapData);
    console.log("Work Order ID:", workOrderId);
    console.log("Manufacturing Order ID:", manufacturingOrderId);

    if (!scrapData || scrapData.length === 0) {
        alert_float('danger', "No scrap data to update.");
        return;
    }

    if (!workOrderId || !manufacturingOrderId) {
        alert_float('danger', "Missing Work Order or Manufacturing Order ID.");
        return;
    }
 console.log("Initializing scrapEditBtn Tab Data:", scrapData);
        $.post(admin_url + 'manufacturing/update_scrap_data', { scrap_data: scrapData,
            work_order_id: workOrderId,
            manufacturing_order_id: manufacturingOrderId }, function(response) {
            if (response.success) {
                alert_float('success', "Scrap data updated successfully!");
            } else {
                alert_float('danger', "Failed to update scrap data.");
            }
        }, 'json').fail(function() {
            alert_float('danger', "Error in updating scrap data.");
        });
    });



   



</script>
<script>
$(document).ready(function() {
    $("#saveWorkOrderBtn").click(function() {
        var formData = {
            work_order_id: "<?php echo $work_order->id; ?>",
            labour_charges: $("#labour_charges").val(),
            electricity_charges: $("#electricity_charges").val(),
            machinery_charges: $("#machinery_charges").val(),
            other_charges: $("#other_charges").val(),
            labour_charges_description: $("#labour_charges_description").val(),
            electricity_charges_description: $("#electricity_charges_description").val(),
            machinery_charges_description: $("#machinery_charges_description").val(),
            other_charges_description: $("#other_charges_description").val()
        };

        $.ajax({
            url: "<?php echo admin_url('manufacturing/update_work_order'); ?>",
            type: "POST",
            data: formData,
            success: function(response) {
                console.log("AJAX response:", response); // Debugging
                try {
                    var result = JSON.parse(response);
                    if (result.status == "success") {
                        alert("Work Order Updated Successfully!");
                    } else {
                        alert("Error: " + result.message);
                    }
                } catch (e) {
                    alert("Invalid server response! Check console for details.");
                    console.error("Error parsing JSON:", e, response);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
                alert("Error updating Work Order. Check console for details.");
            }
        });
    });
});
</script>

