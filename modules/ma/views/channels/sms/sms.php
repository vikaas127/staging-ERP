<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-6">
      <div class="panel_s">
         <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'sms-form')) ;?>
         <div class="panel-body">
            <h4 class="customer-profile-group-heading"><?php echo html_entity_decode($title); ?></h4>

            <div class="btn-bottom-toolbar text-right">
               <a href="<?php echo admin_url('ma/channels?group=sms'); ?>" class="btn btn-default"><?php echo _l('back'); ?></a>
               <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php $value = (isset($sms) ? $sms->name : ''); ?>
            <?php echo render_input('name','name',$value); ?>
            <?php $value = (isset($sms) ? $sms->category : ''); ?>
            <?php echo render_select('category',$category, array('id', 'name'),'category',$value); ?>
            <?php $sms_template = (isset($sms) ? $sms->sms_template : ''); ?>
               <?php echo render_select('sms_template',$text_messages, array('id', 'name'),'sms_template',$sms_template); ?>
            <div class="form-group">
              <?php
                $selected = (isset($sms) ? $sms->published : ''); 
                ?>
              <label for="published"><?php echo _l('published'); ?></label><br />
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="published" id="published_yes" value="1" <?php if($selected == '1'|| $selected == ''){echo 'checked';} ?>>
                <label for="published_yes"><?php echo _l("yes"); ?></label>
              </div>
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="published" id="published_no" value="0" <?php if($selected == '0'){echo 'checked';} ?>>
                <label for="published_no"><?php echo _l("no"); ?></label>
              </div>
            </div>
            
            <?php $value = (isset($sms) ? $sms->color : ''); ?>
            <?php echo render_color_picker('color',_l('color'),$value); ?>
            <div class="form-group select-placeholder">
               <label for="language" class="control-label"><i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('form_lang_validation_help'); ?>"></i> <?php echo _l('form_lang_validation'); ?></label>
               <select name="language" id="language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                  <option value=""></option>
                  <?php foreach ($languages as $availableLanguage) {
                   ?>
                  <option value="<?php echo html_entity_decode($availableLanguage); ?>"<?php if ((isset($sms) && $sms->language == $availableLanguage) || (!isset($sms) && get_option('active_language') == $availableLanguage)) {
                       echo ' selected';
                   } ?>><?php echo ucfirst($availableLanguage); ?></option>
                  <?php } ?>
               </select>
            </div>
               
               <?php
             $description = (isset($sms) ? $sms->description : ''); 
             ?>
            <p class="bold"><?php echo _l('dt_expense_description'); ?></p>
            <?php echo render_textarea('description','',$description,array(),array(),'','tinymce'); ?>
         </div>
         <?php echo form_close(); ?>
      </div>
      </div>
      <div id="preview_area" class="col-md-6 no-padding build-section">
         <div class="panel_s">
            <div class="panel-body">
               <?php $content = (isset($sms) ? html_entity_decode($sms->content) : ''); ?>
               <?php echo render_textarea('content','content',$content, array('readonly' => true)); ?>
            </div>
         </div>
      </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>

</body>
</html>
