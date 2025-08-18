<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php 
			$id = '';
			$title = '';
			if(isset($work_center)){
				$title .= _l('update_work_center');
				$id    = $work_center->id;
			}else{
				$title .= _l('add_work_center');
			}

			?>

			<?php echo form_open_multipart(admin_url('manufacturing/add_edit_work_center/'.$id), array('id' => 'add_update_work_center','autocomplete'=>'off')); ?>

			<div class="col-md-12" >
				<div class="panel_s">
					
					<div class="panel-body">
						<div class="row mb-5">
							<div class="col-md-5">
								<h4 class="no-margin"><?php echo new_html_entity_decode($title); ?> 
							</div>
							<div class="col-md-7">

								<div class="o_not_full oe_button_box"><button type="button" name="240" class="btn oe_stat_button"><i class="fa fa-fw o_button_icon fa-pie-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="oee" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
									<span class="o_stat_value">0.00</span>
									<span class="o_stat_text"></span>
								</div>%</span><span class="o_stat_text">OEE</span></div></button><button type="button" name="241" class="btn oe_stat_button"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="blocked_time" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
									<span class="o_stat_value">0.00</span>
									<span class="o_stat_text"></span>
								</div> Hours</span><span class="o_stat_text">Lost</span></div></button><button type="button" name="237" class="btn oe_stat_button" context="{'search_default_workcenter_id': id}"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="workcenter_load" class="o_field_widget o_stat_info o_readonly_modifier">
									<span class="o_stat_value">0.00</span>
									<span class="o_stat_text"></span>
								</div> Minutes</span><span class="o_stat_text">Load</span></div></button><button type="button" name="243" class="btn oe_stat_button" context="{'search_default_workcenter_id': id, 'search_default_thisyear': True}"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="performance" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
									<span class="o_stat_value">0</span>
									<span class="o_stat_text"></span>
								</div>%</span><span class="o_stat_text">Performance</span></div></button>
							</div>
								
							</div>
						</div>
						<hr class="hr-color">
							<div class="row">
							    	<div class="col-md-6">
 <!-- Radio buttons -->
<div class="radio radio-inline">
  <input type="radio" name="is_subcontract" value="0" id="in_house" 
    <?php if(isset($work_center) && $work_center->is_subcontract == 0 || !isset($work_center)) echo 'checked'; ?>>
  <label for="in_house"><?php echo _l('in_house'); ?></label>
</div>

<div class="radio radio-inline">
  <input type="radio" name="is_subcontract" value="1" id="subcontract" 
    <?php if(isset($work_center) && $work_center->is_subcontract == 1) echo 'checked'; ?>>
  <label for="subcontract"><?php echo _l('OutSource'); ?></label>
</div>
</div>
	<div class="col-md-6">
  <div class="form-group col-md-6" id="vendor-container">
                          
                          <label for="vendor"><?php echo _l('vendor'); ?></label>
                         <!-- <select name="subcontractor_id" id="vendor" class="selectpicker" <?php if(isset($work_center)){ echo 'disabled'; } ?> onchange="estimate_by_vendor(this); return false;" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                              <option value=""></option>
                              <?php foreach($vendors as $s) { ?>
                              <option value="<?php echo pur_html_entity_decode($s['userid']); ?>" <?php if(isset($work_center) && $work_center->vendor == $s['userid']){ echo 'selected'; }else{ if(isset($ven) && $ven == $s['userid']){ echo 'selected';} } ?>><?php echo pur_html_entity_decode($s['company']); ?></option>
                                <?php } ?>
                          </select>  -->
                          <select name="subcontractor_id" id="vendor" class="selectpicker" 
  <?php if(isset($work_center)){ echo 'disabled'; } ?> 
  onchange="estimate_by_vendor(this); return false;" 
  data-live-search="true" 
  data-width="100%" 
  data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
  
  <option value=""></option>
  
  <?php foreach($vendors as $s) { ?>
    <option value="<?php echo pur_html_entity_decode($s['userid']); ?>"
      <?php 
        if (isset($work_center) && $work_center->subcontractor_id == $s['userid']) {
          echo 'selected';
        } elseif (isset($ven) && $ven == $s['userid']) {
          echo 'selected';
        }
      ?>>
      <?php echo pur_html_entity_decode($s['company']); ?>
    </option>
  <?php } ?>
</select>

                          
                        </div>
                        </div>
</div>

  <input type="hidden" name="vendor_name" id="vendor_name" value="<?php echo isset($work_center) ? html_escape($work_center->vendor_name) : ''; ?>">

						<!-- start tab -->
						<div class="modal-body">
							<div class="tab-content">
								<!-- start general infor -->
								<div class="row">
								    
									<div class="row">
										<div class="col-md-6">
											<?php 
											$work_center_name = isset($work_center) ? $work_center->work_center_name : '';
											$work_center_code = isset($work_center) ? $work_center->work_center_code : '';
											$time_efficiency = isset($work_center) ? $work_center->time_efficiency : 100;
											$capacity = isset($work_center) ? $work_center->capacity : 1;
											$costs_hour = isset($work_center) ? $work_center->costs_hour : 0;
											$oee_target = isset($work_center) ? $work_center->oee_target : 90;
											$time_start = isset($work_center) ? $work_center->time_start : 0;
											$time_stop = isset($work_center) ? $work_center->time_stop : 0;
											$description = isset($work_center) ? $work_center->description : '';
											$working_hour_selected = isset($work_center) ? $work_center->working_hours : '';
											?>

											<?php echo render_input('work_center_name','work_center_name',$work_center_name,'text'); ?>   
										</div>
										<div class="col-md-6">
											<?php echo render_input('work_center_code','work_center_code',$work_center_code,'text'); ?>   
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<?php echo render_select('working_hours',$working_hours,array('id','working_hour_name'),'work_center_working_hours',$working_hour_selected); ?>

										</div>
									</div>
								</div>


								<div class="row">
									<h5 class="h5-color"><?php echo _l('work_center_info'); ?></h5>
									<hr class="hr-color">

									<div class="row">
										<div class="col-md-6">
											<?php echo render_input('time_efficiency','time_efficiency',$time_efficiency,'number'); ?>   
										</div>
										<div class="col-md-6">
											<?php echo render_input('costs_hour','costs_hour',$costs_hour,'number'); ?>   
										</div>
										<div class="col-md-6">
											<?php echo render_input('capacity','work_center_capacity',$capacity,'number'); ?>   
										</div>
										<div class="col-md-6">
											<?php echo render_input('oee_target','oee_target',$oee_target,'number'); ?>   
										</div>
										<div class="col-md-6">
											<?php echo render_input('time_start','work_center_time_start',$time_start,'number'); ?>   
										</div>
										<div class="col-md-6">
											<?php echo render_input('time_stop','time_stop',$time_stop,'number'); ?>   
										</div>
										<div class="col-md-12">
											<p class="bold"><?php echo _l('work_center_description'); ?></p>
											<?php echo render_textarea('description','',$description,array(),array(),'','tinymce'); ?>
										</div>
									</div>
								</div>

							</div>

							<div class="modal-footer">
								<a href="<?php echo admin_url('manufacturing/work_center_manage'); ?>"  class="btn btn-default mr-2 "><?php echo _l('hr_close'); ?></a>
								<?php if(has_permission('manufacturing', '', 'create')&&has_permission('work_centers', '', 'create') || has_permission('manufacturing', '', 'edit') && has_permission('work_centers', '', 'edit')){ ?>
									<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>

								<?php } ?>
							</div>

						</div>
					</div>
				</div>

				<?php echo form_close(); ?>
			</div>
		</div>
		<?php init_tail(); ?>
		<?php 
		require('modules/manufacturing/assets/js/work_centers/add_edit_work_center_js.php');
		?>
	</body>
	</html>
