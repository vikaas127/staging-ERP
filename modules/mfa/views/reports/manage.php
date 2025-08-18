<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" >
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
	                 <div class="row">
	                     <div class="col-md-4 border-right">
	                      <h4 class="no-margin font-medium"><i class="fa fa-balance-scale" aria-hidden="true"></i> <?php echo _l('mfa_report_by_table'); ?></h4>
	                      <hr />

	                      <p><a href="#" class="font-medium" onclick="init_report(this,'history_login_table_rp'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('history_login_table_rp'); ?></a></p>
	                      <hr class="hr-10" />

	                      <p><a href="#" class="font-medium" onclick="init_report(this,'history_send_code_table_rp'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('history_send_code_table_rp'); ?></a></p>

	                  	 </div>
		                 <div class="col-md-4 border-right">
		                    <h4 class="no-margin font-medium"><i class="fa fa-area-chart" aria-hidden="true"></i> <?php echo _l('charts_based_report'); ?></h4>
		                    <hr />

		                    <p><a href="#" class="font-medium" onclick="init_report(this,'login_per_month_rp'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('login_per_month_rp'); ?></a></p>
		                    <hr class="hr-10" />

		                 </div>
		                 <div class="col-md-4">
		                 	<div class="form-group hide" id="report-time">
		                        <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
		                        <select class="selectpicker" name="months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
		                           <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
		                           <option value="this_month"><?php echo _l('this_month'); ?></option>
		                           <option value="1"><?php echo _l('last_month'); ?></option>
		                           <option value="this_year"><?php echo _l('this_year'); ?></option>
		                           <option value="last_year"><?php echo _l('last_year'); ?></option>
		                           <option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
		                           <option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
		                           <option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
		                           <option value="custom"><?php echo _l('period_datepicker'); ?></option>
		                        </select>
		                     </div>
		                     <div id="date-range" class="hide mbot15">
		                        <div class="row">
		                           <div class="col-md-6">
		                              <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
		                              <div class="input-group date">
		                                 <input type="text" class="form-control datepicker" id="report-from" name="report-from">
		                                 <div class="input-group-addon">
		                                    <i class="fa fa-calendar calendar-icon"></i>
		                                 </div>
		                              </div>
		                           </div>
		                           <div class="col-md-6">
		                              <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
		                              <div class="input-group date">
		                                 <input type="text" class="form-control datepicker" disabled="disabled" id="report-to" name="report-to">
		                                 <div class="input-group-addon">
		                                    <i class="fa fa-calendar calendar-icon"></i>
		                                 </div>
		                              </div>
		                           </div>
		                        </div>
		                     </div>
		                     <?php $current_year = date('Y');
	                              $y0 = (int)$current_year;
	                              $y1 = (int)$current_year - 1;
	                              $y2 = (int)$current_year - 2;
	                              $y3 = (int)$current_year - 3;
	                           ?>
		                     <div class="form-group hide" id="year_requisition">
		                        <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
		                        <select  name="year_requisition" id="year_requisition"  class="selectpicker"  data-width="100%" data-none-selected-text="<?php echo _l('filter_by').' '._l('year'); ?>">
		                              <option value="<?php echo html_entity_decode($y0); ?>" <?php echo 'selected' ?>><?php echo _l('year').' '. html_entity_decode($y0) ; ?></option>
		                              <option value="<?php echo html_entity_decode($y1); ?>"><?php echo _l('hira_year').' '. html_entity_decode($y1) ; ?></option>
		                              <option value="<?php echo html_entity_decode($y2); ?>"><?php echo _l('hira_year').' '. html_entity_decode($y2) ; ?></option>
		                              <option value="<?php echo html_entity_decode($y3); ?>"><?php echo _l('hira_year').' '. html_entity_decode($y3) ; ?></option>

		                        </select>
		                     </div>

		                     <div class="form-group hide" id="login_status_div">
		                        <label for="login_status"><?php echo _l('login_status'); ?></label><br />
		                        <select  name="login_status" id="login_status"  class="selectpicker"  data-width="100%" >
		                              <option value="all"><?php echo _l('mfa_all'); ?></option>
		                              <option value="success"><?php echo _l('mfa_success'); ?></option>
		                              <option value="fail"><?php echo _l('mfa_fail'); ?></option>
		                        </select>
		                     </div>

		                 </div>
	                </div>
                	
			        <div class="row">
			          	<div class="col-md-12" id="container1" ></div>
			          	<div class="col-md-12" id="container2" ></div>
			        </div> 
			        <hr class="mbot5">
			        <div class="row">
		                <div class="col-md-6" id="container4" ></div>
			          	<div class="col-md-6" id="container3" ></div>
			        </div>

			        <div id="report" class="hide">
			        	
			          	<div class="col-md-12">
		               		<?php $this->load->view('reports/history_login_table_rp'); ?>
			          	</div> 

			          	<div class="col-md-12">
		               		<?php $this->load->view('reports/history_send_code_table_rp'); ?>
			          	</div>

			          	<div class="col-md-12">
		               		<?php $this->load->view('reports/login_per_month_rp'); ?>
			          	</div> 
			          	
		            </div>
	            </div>      
              </div>
           </div>
        </div>
     </div>
  </div>
</div>

<div class="modal fade" id="detail_rp_modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><span class="type-detail"></span></h4>
			</div>
			<div class="modal-body">
				<div class="col-md-12 list_item_rp">
					
				</div>
			</div>
		</div>
	</div>
</div>


<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/mfa/assets/js/mfa_report_js.php';?>