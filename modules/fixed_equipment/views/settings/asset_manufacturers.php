<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div  class="row">
    <?php if(is_admin() || 
    has_permission('fixed_equipment_setting_manufacturer', '', 'create') ||
    has_permission('fixed_equipment_assets', '', 'create') ||
    has_permission('fixed_equipment_licenses', '', 'create') ||
    has_permission('fixed_equipment_accessories', '', 'create') ||
    has_permission('fixed_equipment_consumables', '', 'create') 
){ ?>
   <div class="col-md-12">
    <button class="btn btn-primary" onclick="add(); return false;"><?php echo _l('fe_add'); ?></button>
  </div>
  <div class="clearfix"></div>
  <br>
  <div class="clearfix"></div>
<?php } ?>
<div  class="col-md-12">
  <table class="table table-asset_manufacturers scroll-responsive">
   <thead>
     <tr>
      <th><?php echo _l('id'); ?></th>
      <th><?php echo _l('fe_name'); ?></th>
      <th><?php echo _l('fe_image'); ?></th>
      <th><?php echo _l('fe_url'); ?></th>
      <th><?php echo _l('fe_support_url'); ?></th>
      <th><?php echo _l('fe_support_phone'); ?></th>
      <th><?php echo _l('fe_support_email'); ?></th>
      <th><?php echo _l('fe_asset'); ?></th>
      <th><?php echo _l('fe_license'); ?></th>
      <th><?php echo _l('fe_consumable'); ?></th>
      <th><?php echo _l('fe_accessory'); ?></th>
      <th><?php echo _l('fe_created_at'); ?></th>
    </tr>
  </thead>
  <tbody></tbody>
  <tfoot>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
 </tfoot>
</table>
</div>
</div>

<div class="modal fade" id="add" tabindex="-1" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
     <span class="add-title"><?php echo _l('fe_add_asset_manufacturers'); ?></span>
     <span class="edit-title hide"><?php echo _l('fe_edit_asset_manufacturers'); ?></span>
   </h4>
 </div>
 <?php echo form_open_multipart(admin_url('fixed_equipment/add_asset_manufacturers'),array('id'=>'form_asset_manufacturers')); ?>              
 <div class="modal-body content">
  <?php $this->load->view('settings/includes/asset_manufacturers_modal_content'); ?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
</div>
<?php echo form_close(); ?>                   
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="ic_file_data"></div>

