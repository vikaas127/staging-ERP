<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
	    
	    
	    <div id="wrapper">
    <div class="content">
        <?php echo form_open_multipart(
            (!isset($tab['update_url'])
            ? $this->uri->uri_string() . '?group=' . $tab['slug'] . ($this->input->get('tab') ? '&active_tab=' . $this->input->get('tab') : '')
            : $tab['update_url']),
            ['id' => 'settings-form', 'class' => isset($tab['update_url']) ? 'custom-update-url' : '']
        );
        ?>
        <div class="row">
            <!-- Existing Content -->

            <!-- Add the PWA Install Button Here -->
            <div class="col-md-12">
                <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
                    Install the PWA
                </h4>
                <button id="installPwaButton" class="btn btn-primary" style="display: none;">
                    Install App
                </button>
            </div>
        </div>
        <?php echo form_close(); ?>

        <!-- PWA Install Script -->
        <script>
        let deferredPrompt;

        // Listen for the 'beforeinstallprompt' event
        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the default mini-infobar from appearing
            e.preventDefault();
            // Save the event for later use
            deferredPrompt = e;

            // Make the install button visible
            const installButton = document.getElementById('installPwaButton');
            installButton.style.display = 'block';

            // Add a click event listener to the install button
            installButton.addEventListener('click', () => {
                // Show the installation prompt
                deferredPrompt.prompt();
                // Wait for the user's response
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    // Clear the deferred prompt
                    deferredPrompt = null;
                });
            });
        });
        </script>
    </div>
</div>

		<?php $company_logo = get_option('company_logo'); ?>
		<?php $company_logo_dark = get_option('company_logo_dark'); ?>
		<?php if($company_logo != ''){ ?>
			<div class="row">
				<div class="col-md-9">
					<img src="<?php echo base_url('uploads/company/'.$company_logo); ?>" class="img img-responsive">
				</div>
				<?php if(has_permission('settings','','delete')){ ?>
					<div class="col-md-3 text-right">
						<a href="<?php echo admin_url('settings/remove_company_logo'); ?>" data-toggle="tooltip" title="<?php echo _l('settings_general_company_remove_logo_tooltip'); ?>" class="_delete text-danger"><i class="fa fa-remove"></i></a>
					</div>
				<?php } ?>
			</div>
			<div class="clearfix"></div>
		<?php } else { ?>
			<div class="form-group">
				<label for="company_logo" class="control-label"><?php echo _l('settings_general_company_logo'); ?></label>
				<input type="file" name="company_logo" class="form-control" value="" data-toggle="tooltip" title="<?php echo _l('settings_general_company_logo_tooltip'); ?>">
			</div>
		<?php } ?>
		<hr />
		<?php if($company_logo_dark != ''){ ?>
			<div class="row">
				<div class="col-md-9">
					<img src="<?php echo base_url('uploads/company/'.$company_logo_dark); ?>" class="img img-responsive">
				</div>
				<?php if(has_permission('settings','','delete')){ ?>
					<div class="col-md-3 text-right">
						<a href="<?php echo admin_url('settings/remove_company_logo/dark'); ?>" data-toggle="tooltip" title="<?php echo _l('settings_general_company_remove_logo_tooltip'); ?>" class="_delete text-danger"><i class="fa fa-remove"></i></a>
					</div>
				<?php } ?>
			</div>
			<div class="clearfix"></div>
		<?php } else { ?>
			<div class="form-group">
				<label for="company_logo_dark" class="control-label"><?php echo _l('company_logo_dark'); ?></label>
				<input type="file" name="company_logo_dark" class="form-control" value="" data-toggle="tooltip" title="<?php echo _l('settings_general_company_logo_tooltip'); ?>">
			</div>
		<?php } ?>
		<hr />
		<?php $favicon = get_option('favicon'); ?>
		<?php if($favicon != ''){ ?>
			<div class="form-group favicon">
				<div class="row">
					<div class="col-md-9">
						<img src="<?php echo base_url('uploads/company/'.$favicon); ?>" class="img img-responsive">
					</div>
					<?php if(has_permission('settings','','delete')){ ?>
						<div class="col-md-3 text-right">
							<a href="<?php echo admin_url('settings/remove_fv'); ?>" class="_delete text-danger"><i class="fa fa-remove"></i></a>
						</div>
					<?php } ?>
				</div>
				<div class="clearfix"></div>
			</div>
		<?php } else { ?>
			<div class="form-group favicon_upload">
				<label for="favicon" class="control-label"><?php echo _l('settings_general_favicon'); ?></label>
				<input type="file" name="favicon" class="form-control">
			</div>
		<?php } ?>
		<hr />
		<?php $attrs = (get_option('companyname') != '' ? array() : array('autofocus'=>true)); ?>
		<?php echo render_input('settings[companyname]','settings_general_company_name',get_option('companyname'),'text',$attrs); ?>
		<hr />
		<?php echo render_input('settings[main_domain]','settings_general_company_main_domain',get_option('main_domain')); ?>
		<hr />
		<?php render_yes_no_option('rtl_support_admin','settings_rtl_support_admin'); ?>
		<hr />
		<?php render_yes_no_option('rtl_support_client','settings_rtl_support_client'); ?>
		<hr />
		<?php echo render_input('settings[allowed_files]','settings_allowed_upload_file_types',get_option('allowed_files')); ?>
	</div>
</div>
