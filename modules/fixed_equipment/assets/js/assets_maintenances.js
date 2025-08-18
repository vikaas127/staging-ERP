(function(){
	"use strict";
	var fnServerParams = {
		"id": "[name='id']",
		"maintenance_type": "[name='maintenance_type_filter']",
		"from_date": "[name='from_date_filter']",
		"to_date": "[name='to_date_filter']"
	}
	initDataTable('.table-assets_maintenances', admin_url + 'fixed_equipment/assets_maintenances_table', '', '', fnServerParams, [1, 'desc']);
	$('select[name="maintenance_type_filter"], input[name="from_date_filter"], input[name="to_date_filter"]').change(function(){
		$('.table-assets_maintenances').DataTable().ajax.reload()
		.columns.adjust();
	});
	appValidateForm($('#assets_maintenances-form'), {
		'asset_id': 'required',
		'supplier_id': 'required',
		'maintenance_type': 'required',
		'start_date': 'required',
		'title': 'required'
	})

	$("input[data-type='currency']").on({
		keyup: function() {        
			formatCurrency($(this));
		},
		blur: function() { 
			formatCurrency($(this), "blur");
		}
	});

	$(document).on("click","#mass_select_all",function() {
		var favorite = [];
		if($(this).is(':checked')){
			$('.individual').prop('checked', true);
			$.each($(".individual"), function(){ 
				favorite.push($(this).data('id'));
			});
		}else{
			$('.individual').prop('checked', false);
			favorite = [];
		}

		$("input[name='check']").val(favorite);
	});

})(jQuery);

/**
 * add asset
 */
 function add(){
 	"use strict";
 	$('#add_new_assets_maintenances').modal('show');
 	$('#add_new_assets_maintenances .add-title').removeClass('hide');
 	$('#add_new_assets_maintenances .edit-title').addClass('hide');
 	$('#add_new_assets_maintenances input[name="id"]').val('');
 	$('#add_new_assets_maintenances input[type="text"]').val('');
 	$('#add_new_assets_maintenances select').val('').change();
 	$('#add_new_assets_maintenances textarea').val('');
 	$('input[name="cost"]').val('');
 	$('#add_new_assets_maintenances input[type="checkbox"]').prop('checked', false);
 }

/**
 * edit
 */
 function edit(id){
 	"use strict";
 	$('#add_new_assets_maintenances').modal('show');
 	$('#add_new_assets_maintenances .add-title').addClass('hide');
 	$('#add_new_assets_maintenances .edit-title').removeClass('hide');
 	$('#add_new_assets_maintenances input[name="id"]').val(id);
 	var requestURL = admin_url+'fixed_equipment/get_data_assets_maintenances/' + (typeof(id) != 'undefined' ? id : '');
 	requestGetJSON(requestURL).done(function(response) {

 		$('select[name="asset_id"]').val(response.asset_id).change();
 		$('select[name="supplier_id"]').val(response.supplier_id).change();
 		$('select[name="maintenance_type"]').val(response.maintenance_type).change();

 		$('input[name="title"]').val(response.title);
 		$('input[name="start_date"]').val(response.start_date);
 		$('input[name="completion_date"]').val(response.completion_date);
 		$('input[name="cost"]').val(response.cost);
 		$('textarea[name="notes"]').val(response.notes);

 		if(response.warranty_improvement == 1){
 			$('input[name="warranty_improvement"]').prop('checked', true);
 		}
 		else{
 			$('input[name="warranty_improvement"]').prop('checked', false);
 		}
 	}).fail(function(data) {
 		alert_float('danger', 'Error');
 	});
 }

/**
 * format Number
 */
 function formatNumber(n) {
 	"use strict";
 	return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
 }

/**
 * format Currency
 */
 function formatCurrency(input, blur) {
 	"use strict";
 	var input_val = input.val();
 	if (input_val === "") { return; }
 	var original_len = input_val.length;
 	var caret_pos = input.prop("selectionStart");
 	if (input_val.indexOf(".") >= 0) {
 		var decimal_pos = input_val.indexOf(".");
 		var left_side = input_val.substring(0, decimal_pos);
 		var right_side = input_val.substring(decimal_pos);
 		left_side = formatNumber(left_side);

 		right_side = formatNumber(right_side);
 		right_side = right_side.substring(0, 2);
 		input_val = left_side + "." + right_side;

 	} else {
 		input_val = formatNumber(input_val);
 		input_val = input_val;
 	}
 	input.val(input_val);
 	var updated_len = input_val.length;
 	caret_pos = updated_len - original_len + caret_pos;
 	input[0].setSelectionRange(caret_pos, caret_pos);
 }

 function bulk_delete(){
 	"use strict";
 	var print_id = $('input[name="check"]').val();
 	if(print_id != ''){
 		if(confirm($('input[name="are_you_sure_you_want_to_delete_these_items"]').val()) == true){
 			window.location.href = admin_url+"fixed_equipment/delete_all_maintenance/"+encodeURIComponent(print_id);
 		}
 	}
 	else{
 		alert_float('danger', $('input[name="please_select_at_least_one_item_from_the_list"]').val());
 	}
 }

 function checked_add(el){
 	"use strict";
 	var id = $(el).data("id");
 	var id_product = $(el).data("product");
 	if ($(".individual").length == $(".individual:checked").length) {
 		$("#mass_select_all").attr("checked", "checked");
 		var value = $("input[name='check']").val();
 		if(value != ''){
 			value = value + ',' + id;
 		}else{
 			value = id;
 		}
 	} else {
 		$("#mass_select_all").removeAttr("checked");
 		var value = $("input[name='check']").val();
 		var arr_val = value.split(',');
 		if(arr_val.length > 0){
 			$.each( arr_val, function( key, value ) {
 				if(value == id){
 					arr_val.splice(key, 1);
 					value = arr_val.toString();
 					$("input[name='check']").val(value);
 				}
 			});
 		}
 	}
 	if($(el).is(':checked')){
 		var value = $("input[name='check']").val();
 		if(value != ''){
 			value = value + ',' + id;
 		}else{
 			value = id;
 		}
 		$("input[name='check']").val(value);
 	}else{
 		var value = $("input[name='check']").val();
 		var arr_val = value.split(',');
 		if(arr_val.length > 0){
 			$.each( arr_val, function( key, value ) {
 				if(value == id){
 					arr_val.splice(key, 1);
 					value = arr_val.toString();
 					$("input[name='check']").val(value);
 				}
 			});
 		}
 	}
 }
