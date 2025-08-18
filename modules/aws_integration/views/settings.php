<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <?php echo e($title); ?>
                    </h4>
                </div>
                <?php echo form_open($this->uri->uri_string(), ['id' => 'awsIntegrationForm']); ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php render_yes_no_option('enable_aws_integration', 'enable_aws_integration'); ?>
                        <div id="credentialsForm" class="tw-mt-4" <?php if (!get_option('enable_aws_integration')) {
                            echo 'style="display:none;"';
                        } ?>>
                            <a class="tw-text-primary-500" href="https://maniss.dev/docs/aws_s3_integration"
                                target="_blank">
                                <?php echo _l('how_to_get_credentials'); ?>
                            </a>
                            <?php
                            echo render_input('settings[aws_access_key_id]', 'aws_access_key_id', get_option('aws_access_key_id'), 'password', [], [], 'tw-mt-4');
                            echo render_input('settings[aws_secret_access_key]', 'aws_secret_access_key', get_option('aws_secret_access_key'), 'password', [], ['class' => 'tw-mt-4']);
                            echo render_input('settings[aws_region]', 'aws_region', get_option('aws_region'), 'text', [], ['class' => 'tw-mt-4']);
                            echo render_input('settings[aws_bucket]', 'aws_bucket', get_option('aws_bucket'), 'text', [], ['class' => 'tw-mt-4']);
                            ?>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary" data-loading-text="<?php echo _l('wait_text'); ?>"
                            data-form="#awsIntegrationForm">
                            <?php echo _l('save'); ?>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        // Form validation
        appValidateForm('#awsIntegrationForm', {
            'settings[aws_access_key_id]': {
                required: true,
            },
            'settings[aws_secret_access_key]': {
                required: true,
            },
            'settings[aws_region]': {
                required: true,
            },
            'settings[aws_bucket]': {
                required: true,
            },
        });

        // If enable aws integration set to NO hide the credentials form
        $('input[name="settings[enable_aws_integration]"]').change(function () {
            var selectedValue = $('input[name="settings[enable_aws_integration]"]:checked').val();

            if (selectedValue == 0) {
                $('#credentialsForm').hide();
            } else {
                $('#credentialsForm').show();
            }
        });
    });
</script>
</body>

</html>