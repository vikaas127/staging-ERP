<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo $newsletterData->email_subject; ?>
                </h4>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <table class="table table-bordered">
                            <tr>
                                <th><?php echo _l('mailflow_sent_by'); ?></th>
                                <td><?php echo get_staff_full_name($newsletterData->sent_by); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo _l('mailflow_total_emails_to_send'); ?></th>
                                <td><?php echo $newsletterData->total_emails_to_send; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo _l('mailflow_emails_sent'); ?></th>
                                <td><?php echo $newsletterData->emails_sent; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo _l('mailflow_emails_failed'); ?></th>
                                <td><?php echo $newsletterData->emails_failed; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo _l('mailflow_mails_list'); ?></th>
                                <td><?php echo implode(',', json_decode($newsletterData->email_list)); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo _l('mailflow_created_at'); ?></th>
                                <td><?php echo $newsletterData->created_at; ?></td>
                            </tr>
                        </table>
                        <h4><?php echo _l('mailflow_mail_content'); ?></h4>
                        <?php echo html_entity_decode($newsletterData->email_content); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>
