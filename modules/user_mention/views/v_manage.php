<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();?>


<div id="wrapper" >

    <div class="content">

        <div class="row">


            <div class="col-md-12">

                <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">

                    <?php echo $title ?>

                </h4>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12"  >

                <?php echo form_open( admin_url('user_mention/manage_save') ) ?>

                    <div class="panel_s">

                        <div class="panel-body">

                            <?php $user_mention_send_email = get_option('user_mention_send_email'); ?>
                            <?php $user_mention_send_notification = get_option('user_mention_send_notification'); ?>

                            <div class="form-group">

                                <div class="checkbox checkbox-primary">

                                    <input type="checkbox" <?php echo !empty( $user_mention_send_notification ) && $user_mention_send_notification == 1 ? 'checked' : '' ?> id="user_mention_send_notification" name="user_mention_send_notification" value="1" >

                                    <label for="user_mention_send_notification"><?php echo _l('user_mention_send_notification') ?></label>

                                </div>

                            </div>


                            <div class="form-group">

                                <div class="checkbox checkbox-primary">

                                    <input type="checkbox" <?php echo !empty( $user_mention_send_email ) && $user_mention_send_email == 1 ? 'checked' : '' ?> id="user_mention_send_email" name="user_mention_send_email" value="1">

                                    <label for="user_mention_send_email"><?php echo _l('user_mention_send_email') ?></label>

                                </div>

                            </div>

                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary"><?php echo _l('submit')?></button>
                        </div>

                    </div>

                <?php echo form_close();?>


            </div>


        </div>



    </div>

</div>




<?php init_tail(); ?>



</body>


</html>

