<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php 
			$id = '';
			$title = '';
			$title .= _l('view_manufacturing_order_lable');

			$start_working_hide='';
			$action_hide='';
			$cancel_hide='';

			$waiting_for_another_wo_active='';
			$ready_active='';
			$progress_active='';
			$finished_active='';


			switch ($work_order->status) {
				case 'waiting_for_another_wo':
					$waiting_for_another_wo_active=' active';
					$start_working_hide = '';
					$start_working_status = 'default';
					$action_hide=' hide';
					$pause_hide=' hide';

					break;
				case 'ready':
					$ready_active=' active';
					$start_working_hide = '';
					$start_working_status = 'success';
					$action_hide=' hide';
					$pause_hide=' hide';

					break;
				case 'in_progress':
					$progress_active=' active';
					$start_working_hide = ' hide';
					$start_working_status = 'default';
					$action_hide=' ';
					$pause_hide=' hide';

					break;
				case 'finished':
					$finished_active=' active';
					$start_working_hide = ' hide';
					$start_working_status = 'default';
					$action_hide=' hide';
					$pause_hide=' hide';
					$cancel_hide=' hide';

					break;
				case 'pause':
					$pause_hide=' ';
					$start_working_hide = ' hide';
					$start_working_status = 'default';
					$action_hide=' hide';
					$progress_active=' active';
					break;
					
			
			}


			?>

			<div class="col-md-12" >
				<div class="panel_s">
					
					<div class="panel-body">
						<div class="row mb-5">
							<div class="col-md-9">
								<h4 class="no-margin"><?php echo new_html_entity_decode($header); ?> 
							</div>
							<div class="col-md-3">

								<a href="<?php echo admin_url('manufacturing/view_work_order/'.$next_id.'/'.$manufacturing_order_id); ?>" class=" btn mright5 btn-info pull-right button-text-transform"><?php echo _l('mrp_next'); ?> <i class="fa fa-chevron-circle-right"></i></a>

								<a href="<?php echo admin_url('manufacturing/view_work_order/'.$prev_id.'/'.$manufacturing_order_id); ?>" class=" btn mright5 btn-default pull-right button-text-transform"><i class="fa fa-chevron-circle-left"></i>
									<?php echo _l('mrp_prev'); ?>
								</a>
								<button type="button" class=" btn mright5 btn-default pull-right button-text-transform"><?php echo new_html_entity_decode($pager_value).'/'.new_html_entity_decode($pager_limit); ?> </button>
								
							</div>
						</div>
						<br>
						<hr class="hr-color no-margin">
						<br>

						<!-- action related work order -->
						<div class="row">
						<!--	<div class="col-md-6">

								<?php if(has_permission('manufacturing', '', 'create')&&has_permission('work_order', '', 'create') || has_permission('manufacturing', '', 'edit')&&has_permission('work_order', '', 'edit') ){ ?>

									<?php if(!$check_mo_cancelled){ ?>
									<div class="<?php echo new_html_entity_decode($start_working_hide) ?>">
										<button type="button" class="btn btn-sm btn-<?php echo new_html_entity_decode($start_working_status); ?> pull-left mark_start_working mright5"><?php echo _l('mrp_start_working'); ?></button>
									</div>

									<div class="<?php echo new_html_entity_decode($action_hide) ?>">
										<button type="button" class="btn btn-warning pull-left mark_pause mright5"><?php echo _l('mrp_pause'); ?></button>
										<button type="button" class="btn btn-success pull-left mark_done mright5"><?php echo _l('mrp_done'); ?></button>
									</div>

									<div class="<?php echo new_html_entity_decode($pause_hide) ?>">
										<button type="button" class="btn btn-sm btn-default pull-left mark_start_working mright5"><?php echo _l('mrp_continute_production'); ?></button>
									</div>

									 <button type="button" class="btn btn-default pull-left mark_cancel mright5 <?php echo new_html_entity_decode($cancel_hide) ?>"><?php echo _l('mrp_cancel'); ?></button> 
								<?php } ?>
									
								<?php } ?>
							</div>-->
                           <div class="col-md-6">
    <?php if (has_permission('manufacturing', '', 'create') && has_permission('work_order', '', 'create') 
           || has_permission('manufacturing', '', 'edit') && has_permission('work_order', '', 'edit')) { ?>

        <?php if (!$check_mo_cancelled) { ?>
                 

            <?php 
                $logged_in_staff_id = get_staff_user_id(); 
                $operator_id = isset($work_order_oprater) ? $work_order_oprater->staff_id : null;
                $operator_name = isset($work_order_oprater) ? $work_order_oprater->staff_name : 'Not Assigned';
                $is_assigned_operator = ($logged_in_staff_id == $operator_id);
            ?>

            <div class="<?php echo new_html_entity_decode($start_working_hide) ?>">
                <button type="button" class="btn btn-sm btn-<?php echo new_html_entity_decode($start_working_status); ?> pull-left mark_start_working mright5"
                    <?php if (!$is_assigned_operator) { ?>
                        onclick="alert('Only the assigned operator (<?php echo $operator_name; ?>) can start this work order!'); return false;"
                    <?php } else { ?>
                        onclick="startWorkOrder();"
                    <?php } ?>>
                    <?php echo _l('mrp_start_working'); ?>
                </button>
            </div>

            <div class="<?php echo new_html_entity_decode($action_hide) ?>">
                <button type="button" class="btn btn-warning pull-left mark_pause mright5"><?php echo _l('mrp_pause'); ?></button>
                <button type="button" class="btn btn-success pull-left mark_done mright5"><?php echo _l('mrp_done'); ?></button>
            </div>

            <div class="<?php echo new_html_entity_decode($pause_hide) ?>">
                <button type="button" class="btn btn-sm btn-default pull-left mark_start_working mright5"><?php echo _l('mrp_continute_production'); ?></button>
            </div>

        <?php } ?>

    <?php } ?>
</div>

<script>
function startWorkOrder() {
    // Your existing JavaScript function for starting work order goes here
    console.log("Work order started!"); // Replace this with actual function
}
</script>


							<!-- status -->
							<div class="col-md-6">
								<div class="sw-main sw-theme-arrows pull-right">

									<!-- SmartWizard html -->
									<ul class="nav nav-tabs step-anchor">
										<li class="<?php echo new_html_entity_decode($waiting_for_another_wo_active) ?>"><a href="#"><?php echo _l('waiting_for_another_wo'); ?></a></li>
										<li class="<?php echo new_html_entity_decode($ready_active) ?>"><a href="#"><?php echo _l('ready'); ?></a></li>
										<li class="<?php echo new_html_entity_decode($progress_active) ?>"><a href="#"><?php echo _l('mrp_in_progress'); ?></a></li>
										<li class="<?php echo new_html_entity_decode($finished_active) ?>"><a href="#"><?php echo _l('mrp_finished'); ?></a></li>
									</ul>
								</div>

							</div>
							<!-- status -->

						</div>
						<!-- action related work order -->

						<hr class="">
						

						<!-- start tab -->
						<div class="modal-body">
							<div class="tab-content">
								<!-- start general infor -->
								<?php 

								$id = isset($manufacturing_order) ? $manufacturing_order->id : '';
								$product_id = isset($work_order) ? $work_order->product_id : '';

								$quantity_produced = isset($work_order) ? $work_order->qty_produced : '';
								$qty_production = isset($work_order) ? $work_order->qty_production : '';

								$unit_id = isset($work_order) ? $work_order->unit_id : '';

								$labour_charges = isset($work_order) ? $work_order->labour_charges : '';
								$machinery_charges = isset($work_order) ? $work_order->machinery_charges : '';
								$electricity_charges = isset($work_order) ? $work_order->electricity_charges : '';
								$other_charges = isset($work_order) ? $work_order->other_charges : '';
								$labour_charges_description = isset($work_order) ? $work_order->labour_charges_description : '';
								$machinery_charges_description = isset($work_order) ? $work_order->machinery_charges_description : '';
								$electricity_charges_description = isset($work_order) ? $work_order->electricity_charges_description : '';
								$other_charges_description = isset($work_order) ? $work_order->other_charges_description : '';


								$work_center = isset($work_order) ? $work_order->work_center_id : '';
								$manufacturing_order = isset($work_order) ? $work_order->manufacturing_order_id : '';
								$qty_producing = isset($work_order) ? $work_order->qty_producing : '';
								$work_order_id = isset($work_order) ? $work_order->id : '';
								$date_planned_start = isset($work_order) ? _dt($work_order->date_planned_start) : '';
								$date_planned_finished = isset($work_order) ? _dt($work_order->date_planned_finished) : '';
								$duration_expected = isset($work_order) ? $work_order->duration_expected : '';
								$real_duration = isset($work_order) ? $work_order->real_duration : '';
								$date_start = isset($work_order) ? _dt($work_order->date_start) : '';
								$date_finished = isset($work_order) ? _dt($work_order->date_finished) : '';
                                $operator_name = isset($work_order_oprater) ? $work_order_oprater->staff_name : 'Not Assigned';
                                $operator_id = isset($work_order_oprater) ? $work_order_oprater->staff_id : '';
                                $scrap_items= isset($scrap_items) ? $scrap_items :'';
                                $operation_id = $work_order->routing_detail_id;




								?>
								<div class="row">
									<div class="col-md-6 panel-padding" >
										<input type="hidden" name="manufacturing_order" value="<?php echo new_html_entity_decode($manufacturing_order) ?>">
										<input type="hidden" name="work_order_id" value="<?php echo new_html_entity_decode($work_order_id) ?>">
<div class="work-order-details">
    <p><strong>Operation:</strong> <?php echo $work_order->operation_name; ?></p>
    <p><strong>Assigned Operator:</strong> <?php echo $operator_name; ?></p>
	<?php
	$contactId = isset($work_order->contact_id) ? $work_order->contact_id : null;

	$customerName = ($contactId) ? get_relation_values(get_relation_data('customer', $contactId), 'customer')['name'] : 'Customer Not Found';
	?>

	<!-- Display in frontend -->
	<p><strong>Customer Name:</strong> <?= $customerName ?></p>

    <p><strong>Estimate ID:</strong> <?= isset($work_order->estimate_id) ? $work_order->estimate_id : '' ?></p>

</div>


										<table class="table border table-striped table-margintop" >
											<tbody>
												<tr class="project-overview">
													<td class="bold td-width"><?php echo _l('to_produce'); ?></td>
													<td><b><?php echo mrp_get_product_name($product_id) ; ?></b></td>
												</tr>
												<tr class="project-overview">
													<td class="bold"><?php echo _l('quantity_produced'); ?></td>
													<td><?php echo new_html_entity_decode($quantity_produced.'/'.$qty_production.' ') ; ?><b><?php echo mrp_get_unit_name($unit_id); ?></b></td>
												</tr>

											</tbody>
										</table>
									</div>

								</div>


								<div class="row">
									<h5 class="h5-color"><?php echo _l('work_center_info'); ?></h5>
									<hr class="hr-color">
								</div>

								<div class="row">
									<div class="horizontal-scrollable-tabs preview-tabs-top">
										<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
										<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
										<div class="horizontal-tabs">
											<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
												<li role="presentation" class="">
													<a href="#work_instruction" aria-controls="work_instruction"  class="work_instruction" role="tab" data-toggle="tab">
														<span class="glyphicon glyphicon-align-justify"></span>&nbsp;<?php echo _l('work_instruction'); ?>
													</a>
												</li>
												<li role="presentation" class="">
													<a href="#current_production" aria-controls="current_production" role="tab" data-toggle="tab">
														<span class="fa fa-cogs menu-icon"></span>&nbsp;<?php echo _l('current_production'); ?>
													</a>
												</li>
													<li role="presentation" class="">
													<a href="#scraps" aria-controls="scraps" role="tab" data-toggle="tab">
														<span class="fa fa-cogs menu-icon"></span>&nbsp;<?php echo _l('scrap_tab'); ?>
													</a>
												</li>
												<li role="presentation" class="active">
													<a href="#time_tracking" aria-controls="time_tracking" role="tab" data-toggle="tab">
														<span class="fa fa-balance-scale menu-icon"></span>&nbsp;<?php echo _l('time_tracking'); ?>
													</a>
												</li>
												<li role="presentation" class="">
													<a href="#miscellaneous" aria-controls="miscellaneous" role="tab" data-toggle="tab">
														<span class="fa fa-balance-scale menu-icon"></span>&nbsp;<?php echo _l('miscellaneous'); ?>
													</a>
												</li>
												<li role="presentation" class="">
													<a href="#tasks" onclick="init_rel_tasks_table(<?php echo new_html_entity_decode($work_order->id); ?>,'work_order'); return false;" aria-controls="tasks" role="tab" data-toggle="tab">
														<span class="fa-regular fa-circle-check menu-icon"></span>&nbsp;<?php echo _l('tasks'); ?>
													</a>
												</li>
												<li role="presentation" class="">
													<a href="#costing_tab" aria-controls="costing_tab" role="tab" data-toggle="tab">
														<span class="fa-solid fa-coins"></span>&nbsp;<?php echo _l('costing'); ?>
													</a>
											</li>

											</ul>
										</div>
									</div>
									<br>


									<div class="tab-content active">
										<div role="tabpanel" class="tab-pane " id="work_instruction">
											<div class="row">
												<?php if(count($work_order_file) > 0){ ?>
													<div class="col-md-12 border-right work_order_area">

														<?php	foreach ($work_order_file as $file) {

															?>

															<?php if(!empty($file['external']) && $file['external'] == 'dropbox'){ ?>
																<a href="<?php echo new_html_entity_decode($file['external_link']); ?>" target="_blank" class="btn btn-info mbot20"><i class="fa fa-dropbox" aria-hidden="true"></i> <?php echo _l('open_in_dropbox'); ?></a><br />
															<?php } ?>
															<?php
															$path = MANUFACTURING_OPERATION_ATTACHMENTS_UPLOAD_FOLDER.'/' .$file['rel_id'].'/'.$file['file_name'];
															if(!file_exists($path)){
																continue;
															}
															if(is_image($path)){ ?>
																<img src="<?php echo base_url(OPERATION_ATTACHMENTS.$file['rel_id'].'/'.$file['file_name']); ?>" class="img img-responsive" >
															<?php } else if(!empty($file['external']) && !empty($file['thumbnail_link'])){ ?>
																<img src="<?php echo optimize_dropbox_thumbnail($file['thumbnail_link']); ?>" class="img img-responsive">
															<?php } else if(strpos($file['filetype'],'pdf') !== false && empty($file['external'])){ ?>
																<iframe src="<?php echo base_url(OPERATION_ATTACHMENTS.$file['rel_id'].'/'.$file['file_name']); ?>" height="100%" width="100%" frameborder="0"></iframe>
															<?php } else if(strpos($file['filetype'],'xls') !== false && empty($file['external'])){ ?>
																<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(OPERATION_ATTACHMENTS.$file['rel_id'].'/'.$file['file_name']); ?>' width='100%' height='100%' frameborder='0'>
																</iframe>
															<?php } else if(strpos($file['filetype'],'xlsx') !== false && empty($file['external'])){ ?>
																<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(OPERATION_ATTACHMENTS.$file['rel_id'].'/'.$file['file_name']); ?>' width='100%' height='100%' frameborder='0'>
																</iframe>
															<?php } else if(strpos($file['filetype'],'doc') !== false && empty($file['external'])){ ?>
																<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(OPERATION_ATTACHMENTS.$file['rel_id'].'/'.$file['file_name']); ?>' width='100%' height='100%' frameborder='0'>
																</iframe>
															<?php } else if(strpos($file['filetype'],'docx') !== false && empty($file['external'])){ ?>
																<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(OPERATION_ATTACHMENTS.$file['rel_id'].'/'.$file['file_name']); ?>' width='100%' height='100%' frameborder='0'>
																</iframe>
															<?php } else if(is_html5_video($path)) { ?>
																<video width="100%" height="100%" src="<?php echo site_url('download/preview_video?path='.protected_file_url_by_path($path).'&type='.$file['filetype']); ?>" controls>
																	Your browser does not support the video tag.
																</video>
															<?php } else if(is_markdown_file($path) && $previewMarkdown = markdown_parse_preview($path)) {
																echo new_html_entity_decode($previewMarkdown);
															} else {
																echo '<a href="'.site_url(OPERATION_ATTACHMENTS.$file['rel_id'].'/'.$file['file_name']).'" download>'.$file['file_name'].'</a>';
																echo '<p class="text-muted">'._l('no_preview_available_for_file').'</p>';
															} ?>

														<?php } ?>
													</div>
												<?php } ?>

											</div>

										</div>

										<div role="tabpanel" class="tab-pane " id="current_production">
											<div class="row">
												<div class="col-md-6 panel-padding" >
													<table class="table table-striped table-margintop">
														<tbody>
															<tr class="project-overview">
																<td class="bold" width="40%"><?php echo _l('quantity_in_production'); ?></td>
																<td><?php echo new_html_entity_decode($qty_producing)  ?></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane " id="scraps">
										    
												<div class="form"> 
												<div id="scrap_hqs" class="scrab_tab handsontable htColumnHeaders">
												</div>
												<?php echo form_hidden('scrap_hqs'); ?>
											</div>
											</div>
										<div role="tabpanel" class="tab-pane active" id="time_tracking">
											<div class="row">
												<div class="col-md-6 panel-padding" >
													<table class="table table-striped table-margintop">
														<tbody>
															<tr class="project-overview">
																<td class="bold" width="40%"><?php echo _l('planned_date'); ?></td>
																<td><b><?php echo new_html_entity_decode($date_planned_start) ?></b><?php echo' '?><?php echo _l('mrp_to')?><?php echo' '?><b><?php echo new_html_entity_decode($date_planned_finished)  ?></b></td>
															</tr>
															<tr class="project-overview">
																<td class="bold"><?php echo _l('effective_date'); ?></td>
																<td><b><?php echo new_html_entity_decode($date_start) ?></b><?php echo' '?><?php echo _l('mrp_to')?><?php echo' '?><b><?php echo new_html_entity_decode($date_finished)  ?></b></td>
															</tr>

														</tbody>
													</table>
												</div>
											
												<div class="col-md-6 panel-padding" >
													<table class="table table-striped table-margintop">
														<tbody>
															<tr class="project-overview">
																<td class="bold" width="40%"><?php echo _l('expected_duration'); ?></td>
																<td><b><?php echo new_html_entity_decode($duration_expected)?></b> <?php echo _l('mrp_minutes')  ?></td>
															</tr>
															<tr class="project-overview">
																<td class="bold"><?php echo _l('real_duration'); ?></td>
																<td><b><?php echo new_html_entity_decode($real_duration)?></b> <?php echo _l('mrp_minutes')  ?></td>
															</tr>

														</tbody>
													</table>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-12">
											<div class="form"> 
												<div id="time_tracking_hs" class="product_tab handsontable htColumnHeaders">
												</div>
												<?php echo form_hidden('time_tracking_hs'); ?>
											</div>
													
												</div>
											</div>
											
										</div>

										<div role="tabpanel" class="tab-pane " id="miscellaneous">
											<div class="row">
												<div class="col-md-6 panel-padding" >
													<table class="table table-striped table-margintop">
														<tbody>
															<tr class="project-overview">
																<td class="bold" width="40%"><?php echo _l('work_center'); ?></td>
																<td><?php echo new_html_entity_decode(get_work_center_name($work_center))  ?></td>
															</tr>
															<tr class="project-overview">
																<td class="bold"><?php echo _l('manufacturing_order'); ?></td>
																<td><?php echo new_html_entity_decode(mrp_get_manufacturing_code($manufacturing_order))  ?></td>
															</tr>

														</tbody>
													</table>
												</div>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane " id="tasks">
											<?php init_relation_tasks_table(array('data-new-rel-id'=>$work_order->id,'data-new-rel-type'=>'work_order')); ?>
										</div>
										
										<div role="tabpanel" class="tab-pane " id="costing_tab">
										<div class="row">
    <!-- <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Classification</th>
                <th>Actual Amount</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Labour Charges</td>
                <td>
                    <input type="number" name="labour_charges" id="labour_charges" value="<?php echo $labour_charges; ?>">
                </td>
                <td>
                    <input type="text" name="labour_charges_description" id="labour_charges_description" value="<?php echo $labour_charges_description; ?>">
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Electricity Charges</td>
                <td>
                    <input type="number" name="electricity_charges" id="electricity_charges" value="<?php echo $electricity_charges; ?>">
                </td>
                <td>
                    <input type="text" name="electricity_charges_description" id="electricity_charges_description" value="<?php echo $electricity_charges_description; ?>">
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Machinery Charges</td>
                <td>
                    <input type="number" name="machinery_charges" id="machinery_charges" value="<?php echo $machinery_charges; ?>">
                </td>
                <td>
                    <input type="text" name="machinery_charges_description" id="machinery_charges_description" value="<?php echo $machinery_charges_description; ?>">
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>Other Charges</td>
                <td>
                    <input type="number" name="other_charges" id="other_charges" value="<?php echo $other_charges; ?>">
                </td>
                <td>
                    <input type="text" name="other_charges_description" id="other_charges_description" value="<?php echo $other_charges_description; ?>">
                </td>
            </tr>
        </tbody>
    </table> -->

	<?php
$is_editable = $work_order->status == 'in_progress';
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Classification</th>
            <th>Actual Amount</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $fields = ['labour_charges', 'electricity_charges', 'machinery_charges', 'other_charges'];
        $descriptions = ['labour_charges_description', 'electricity_charges_description', 'machinery_charges_description', 'other_charges_description'];
        foreach ($fields as $index => $field) { ?>
            <tr>
                <td><?php echo ($index + 1); ?></td>
                <td><?php echo ucfirst(str_replace('_', ' ', $field)); ?></td>
                <td>
                    <input type="number" class="editable-field form-control" name="<?php echo $field; ?>" id="<?php echo $field; ?>"
                        value="<?php echo new_html_entity_decode($work_order->$field); ?>"
                        <?php echo !$is_editable ? 'readonly' : ''; ?>>
                </td>
           
				<td>
    <input type="text" class="editable-field form-control" name="<?php echo $descriptions[$index]; ?>" id="<?php echo $descriptions[$index]; ?>"
        value="<?php echo new_html_entity_decode($work_order->{$descriptions[$index]}); ?>"
        <?php echo !$is_editable ? 'readonly' : ''; ?>>
</td>

            </tr>
        <?php } ?>
    </tbody>
</table>

<?php if ($is_editable) { ?>
    <button class="btn btn-success" id="saveWorkOrderBtn">Save</button>
<?php } ?>








					</div>
										</div>
									</div>
								</div>

							</div>

							<div class="modal-footer">
							    <button id="saveScrapBtn" class="btn btn-primary" style="display: none;"
 data-work-order-id="<?php echo $operation_id ; ?>"
        data-manufacturing-order-id="<?php echo $manufacturing_order; ?>"
onclick="updateScrapData()">
        <?php echo _l('update_scrap'); ?>
    </button>
								<a href="<?php echo admin_url('manufacturing/work_order_manage'); ?>"  class="btn btn-default mr-2 "><?php echo _l('close'); ?></a>
								<a href="<?php echo admin_url('manufacturing/view_manufacturing_order/'.$manufacturing_order_id); ?>"  class="btn btn-info mr-2 "><?php echo _l('manufacturing_order'); ?></a>
						

							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
		
		<?php init_tail(); ?>
		<?php 
		require('modules/manufacturing/assets/js/work_orders/view_work_order_js.php');
		?>
	</body>
	</html>
