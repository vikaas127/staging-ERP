<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<?php init_head();?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
              <div class="panel-body">
                <div class="border-right">
                  <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
                  <hr />
                </div>
                <?php echo form_open(admin_url('quickbooks_integration/update_setting')); ?>
                <?php
                    render_yes_no_option('acc_integration_quickbooks_active', 'acc_active');
                    render_yes_no_option('acc_integration_quickbooks_sync_from_system', 'acc_sync_from_system_to_quickbooks');
                    render_yes_no_option('acc_integration_quickbooks_sync_to_system', 'acc_sync_from_quickbooks_to_system');

                    $value = get_option('acc_integration_quickbooks_client_id');
                    $client_id = $this->encryption->decrypt($value);
                    echo render_input('settings[acc_integration_quickbooks_client_id]', 'acc_client_id', $client_id);

                    $value = get_option('acc_integration_quickbooks_client_secret');
                    $client_secret = $this->encryption->decrypt($value);
                    echo render_input('settings[acc_integration_quickbooks_client_secret]', 'acc_client_secret', $client_secret);
                ?>
                <?php if(get_option('acc_integration_quickbooks_active') == 1){ ?>
                  <?php if(get_option('acc_integration_quickbooks_connected') == 1){ ?>
                    <span class="label label-success mbot10"><?php echo _l('connected'); ?></span><br>
                  <?php }else{ ?>
                    <span class="label label-warning mbot10"><?php echo _l('not_connected_yet'); ?></span><br>
                  <?php } ?>
                  <a href="<?php echo admin_url('quickbooks_integration/connect'); ?>" class="btn btn-primary"><?php echo _l('connect'); ?></a>
                <?php } ?>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
                <?php echo form_close(); ?>
              </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail();?>
</body>
</html>
