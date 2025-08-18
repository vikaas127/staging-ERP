<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 

$manu_name = '';
$manu_url = '';
$manu_support_url = '';
$manu_support_phone = '';
$manu_support_email = '';

$depreciation_name = '';
$depreciation_term = '';

if(isset($model)){
  $data_manu = $this->fixed_equipment_model->get_asset_manufacturers($model->manufacturer);
  if($data_manu){
    $manu_name = $data_manu->name;
    $manu_url = $data_manu->url;
    $manu_support_url = $data_manu->support_url;
    $manu_support_phone = $data_manu->support_phone;
    $manu_support_email = $data_manu->support_email;
  }

  $data_depreciation = $this->fixed_equipment_model->get_depreciations($model->depreciation);
  if($data_depreciation){
    $depreciation_name = $data_depreciation->name;
    $depreciation_term = $data_depreciation->term;
  }

}

?>
<div id="wrapper">
  <div class="content">
    <div class="row panel">

      <div class="col-md-9">
        <h4>
          <br>
          <?php echo fe_htmldecode($title); ?>
          <hr>          
        </h4>

        <input type="hidden" name="id" value="<?php echo fe_htmldecode($model->id); ?>">


        <table class="table table-view_model scroll-responsive">
         <thead>
           <tr>
            <th>ID</th>
            <th><?php echo  _l('fe_asset_name'); ?></th>
            <th><?php echo  _l('fe_image'); ?></th>
            <th><?php echo  _l('fe_serial'); ?></th>
            <th><?php echo  _l('fe_model'); ?></th>
            <th><?php echo  _l('fe_model_no'); ?></th>
            <th><?php echo  _l('fe_category'); ?></th>
            <th><?php echo  _l('fe_status'); ?></th>
            <th><?php echo  _l('fe_checkout_to'); ?></th>
            <th><?php echo  _l('fe_location'); ?></th>
            <th><?php echo  _l('fe_default_location'); ?></th>
            <th><?php echo  _l('fe_manufacturer'); ?></th>
            <th><?php echo  _l('fe_supplier'); ?></th>
            <th><?php echo  _l('fe_purchase_date'); ?></th>
            <th><?php echo  _l('fe_purchase_cost'); ?></th>
            <th><?php echo  _l('fe_order_number'); ?></th>
            <th><?php echo  _l('fe_warranty'); ?></th>
            <th><?php echo  _l('fe_warranty_expires'); ?></th>
            <th><?php echo  _l('fe_notes'); ?></th>
            <th><?php echo  _l('fe_checkouts'); ?></th>
            <th><?php echo  _l('fe_checkins'); ?></th>
            <th><?php echo  _l('fe_requests'); ?></th>
            <th><?php echo  _l('fe_created_at'); ?></th>
            <th><?php echo  _l('fe_updated_at'); ?></th>
            <th><?php echo  _l('fe_checkout_date'); ?></th>
            <th><?php echo  _l('fe_expected_checkin_date'); ?></th>
            <th><?php echo  _l('fe_last_audit'); ?></th>
            <th><?php echo  _l('fe_next_audit_date'); ?></th>
            <?php 
            $custom_fields = get_custom_fields('fixed_equipment');
            foreach($custom_fields as $field){ ?>
              <th><?php echo  fe_htmldecode($field['name']); ?></th>
            <?php } ?>
          </tr>
        </thead>
        <tbody></tbody>
      </table>


    </div>
    <div class="col-md-3">
      <img class="img img-responsive pull-left thumbnail mtop15" src="<?php echo fe_htmldecode($this->fixed_equipment_model->get_image_items($model->id, 'models'));  ?>">
      <table class="table table-striped">
        <tbody>
          <tr>
            <td><?php echo '<strong>'._l('fe_manufacturer').': </strong>'.$manu_name; ?></td>
          </tr>
          <tr>
            <td><strong><i class="fa fa-globe"></i></strong> <a href="<?php echo fe_htmldecode($manu_url); ?>"><?php echo fe_htmldecode($manu_url); ?></a></td>
          </tr>
          <tr>
            <td><strong><i class="fa fa-life-ring"></i></strong> <a href="<?php echo fe_htmldecode($manu_support_url); ?>"><?php echo fe_htmldecode($manu_support_url); ?></a></td>
          </tr>
          <tr>
            <td><strong><i class="fa fa-phone"></i></strong> <a href="tel:<?php echo fe_htmldecode($manu_support_phone); ?>"><?php echo fe_htmldecode($manu_support_phone); ?></a></td>
          </tr>
          <tr>
            <td><strong><i class="fa fa-envelope"></i></strong> <a href="mailto:<?php echo fe_htmldecode($manu_support_email); ?>"><?php echo fe_htmldecode($manu_support_email); ?></a></td>
          </tr>
          <tr>
            <td><?php echo '<strong>'._l('fe_model_no').': </strong>'.$model->model_no; ?></td>
          </tr>
          <tr>
            <td><?php echo '<strong>'._l('fe_depreciation').': </strong>'.$depreciation_name.' ('.$depreciation_term.' '._l('months').')'; ?></td>
          </tr>
          <tr>
            <td><strong>EOL: </strong><?php echo (is_numeric($model->eol) ? $model->eol.' '._l('months') : ''); ?></td>
          </tr>
          <tr>
            <td><?php echo '<strong>'._l('fe_notes').': </strong>'.$model->note; ?></td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>
</div>

<div class="modal fade" id="add_new_assets" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
         <span class="edit-title hide"><?php echo _l('fe_edit_asset'); ?></span>
         <span class="add-title"><?php echo _l('fe_add_asset'); ?></span>
       </h4>
     </div>
     <?php echo form_open_multipart(admin_url('fixed_equipment/add_assets'),array('id'=>'assets-form', 'onsubmit'=>'return validateForm()')); ?>
     <div class="modal-body">
      <?php $this->load->view('includes/new_asset_modal'); ?>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
    </div>
    <?php echo form_close(); ?>                 
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
<?php init_tail(); ?>
</body>
</html>


