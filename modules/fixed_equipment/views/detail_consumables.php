<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row panel_s">
			<div class="panel-body">
				<div class="col-md-12">
					<div class="clearfix">
						<h4 class="heading pull-left">
							<?php echo fe_htmldecode($title); ?>
						</h4>
						<?php 
						if(is_admin() || has_permission('fixed_equipment_consumables', '', 'create')){
							if($allow_checkout){ ?>
								<button class="btn btn-danger pull-right mtop5 mleft10" data-asset_name="<?php echo fe_htmldecode($asset_name) ?>" onclick="check_out(this);"><?php echo _l('fe_checkout'); ?></button>
							<?php }} ?>
							<?php 
							$back_link = admin_url('fixed_equipment/consumables');
							if(isset($redirect) && $redirect != ''){
								$back_link = admin_url('fixed_equipment/'.$redirect);
							}
							?>
							<a href="<?php echo fe_htmldecode($back_link); ?>" class="btn btn-default pull-right mtop5"><?php echo _l('fe_back'); ?></a>
						</div>
						<hr>
					</div>
					<div class="col-md-12">
						<div class="clearfix"></div>
						<table class="table table-detail_consumables scroll-responsive">
							<thead>
								<tr>
									<th>ID</th>
									<th><?php echo  _l('fe_checkout_to'); ?></th>
									<th><?php echo  _l('fe_notes'); ?></th>
									<th><?php echo  _l('fe_checkout_date'); ?></th>
									<?php 
									if(is_admin() || has_permission('fixed_equipment_consumables', '', 'create')){
										?>
										<th><?php echo  _l('fe_checkin_checkout'); ?></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="add_new_consumables" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<span class="edit-title hide"><?php echo _l('fe_edit_consumables'); ?></span>
						<span class="add-title"><?php echo _l('fe_add_consumables'); ?></span>
					</h4>
				</div>
				<?php echo form_open_multipart(admin_url('fixed_equipment/consumables'),array('id'=>'consumables-form', 'onsubmit'=>'return validateForm()')); ?>
				<div class="modal-body">
					<?php $this->load->view('includes/new_consumables_modal'); ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
					<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
				</div>
				<?php echo form_close(); ?>                 
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<div class="modal fade" id="check_in" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<span class="add-title"><?php echo _l('fe_checkin'); ?></span>
					</h4>
				</div>
				<?php echo form_open(admin_url('fixed_equipment/check_in_consumables'),array('id'=>'check_in_consumables-form')); ?>
				<div class="modal-body">
					<input type="hidden" name="id" value="">
					<input type="hidden" name="item_id" value="<?php echo fe_htmldecode($id); ?>">
					<input type="hidden" name="type" value="checkin">
					<input type="hidden" name="status" value="1">
					<div class="row">
						<div class="col-md-12">
							<?php echo render_input('asset_name','fe_accessory_name', '', 'text', array('readonly' => true)); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo render_date_input('checkin_date', 'fe_checkin_date'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo render_textarea('notes','fe_notes'); ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
					<button type="submit" class="btn btn-info"><?php echo _l('fe_checkin'); ?></button>
				</div>
				<?php echo form_close(); ?>                 
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="check_out" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<span class="add-title"><?php echo _l('fe_checkout'); ?></span>
					</h4>
				</div>
				<?php echo form_open(admin_url('fixed_equipment/check_in_consumables'),array('id'=>'check_out_consumables-form')); ?>
				<div class="modal-body">
					<input type="hidden" name="id" value="">
					<input type="hidden" name="item_id" value="<?php echo fe_htmldecode($id) ?>">
					<input type="hidden" name="type" value="checkout">
					<input type="hidden" name="status" value="2">			
					<input type="hidden" name="detailt_page" value="1">		
					<input type="hidden" name="checkout_to" value="user">
					<div class="row">
						<div class="col-md-12">
							<?php echo render_input('asset_name','fe_accessory_name', '', 'text', array('readonly' => true)); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_staffs'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo render_textarea('notes','fe_notes'); ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
					<button type="submit" class="btn btn-info"><?php echo _l('fe_checkout'); ?></button>
				</div>
				<?php echo form_close(); ?>                 
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<input type="hidden" name="parent_id" value="<?php echo fe_htmldecode($id) ?>">
	<?php init_tail(); ?>
</body>
</html>
