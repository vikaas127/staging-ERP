<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
   <div class="content">
      <div class="row">
         
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div>
                     <div class="tab-content">
<h4 class="customer-profile-group-heading"><?php echo _l('segment'); ?></h4>
<?php echo form_open($this->uri->uri_string(),array('class'=>'segment-form','autocomplete'=>'off')); ?>
<div class="row">
<?php echo form_hidden('id',( isset($segment) ? $segment->id : '') ); ?>
<div class="additional"></div>
<div class="col-md-12">
   <div class="horizontal-scrollable-tabs">
         <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
         <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
         <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
               <li role="presentation" class="<?php if(!$this->input->get('tab')){echo 'active';}; ?>">
               <a href="#segment_details" aria-controls="segment_details" role="tab" data-toggle="tab">
                  <?php echo _l( 'details'); ?>
               </a>
            </li>
            <li role="presentation">
               <a href="#segment_filters" aria-controls="segment_filters" role="tab" data-toggle="tab">
                  <?php echo _l( 'filters'); ?>
               </a>
            </li>
            </ul>
         </div>
      </div>
   <div class="tab-content">
      <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="segment_details">
         <div class="row">
            <div class="col-md-12">
              <?php $name = ( isset($segment) ? $segment->name : '');
                  echo render_input('name','name',$name,'text'); ?>
            </div>
            <div class="col-md-12">
              <?php $value = ( isset($segment) ? $segment->category : ''); ?>
              <?php echo render_select('category',$categories,array('id','name'), 'category', $value); ?>
            </div>

            <div class="col-md-12">
            <?php $value = (isset($segment) ? $segment->color : ''); ?>
              <?php echo render_color_picker('color',_l('color'),$value); ?>
            </div>
            <div class="form-group col-md-12">
              <?php
                $selected = (isset($segment) ? $segment->public_segment : ''); 
                ?>
              <label for="public_segment"><?php echo _l('public_segment'); ?></label><br />
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="public_segment" id="public_segment_yes" value="1" <?php if($selected == '1'|| $selected == ''){echo 'checked';} ?>>
                <label for="public_segment_yes"><?php echo _l("yes"); ?></label>
              </div>
              <div class="radio radio-inline radio-primary">
                <input type="radio" name="public_segment" id="public_segment_no" value="0" <?php if($selected == '0'){echo 'checked';} ?>>
                <label for="public_segment_no"><?php echo _l("no"); ?></label>
              </div>
            </div>
            <div class="form-group col-md-12">
              <?php
                $selected = (isset($segment) ? $segment->published : ''); 
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
            <div class="col-md-12">
              <?php
                $description = (isset($segment) ? $segment->description : ''); 
                ?>
              <p class="bold"><?php echo _l('dt_expense_description'); ?></p>
              <?php echo render_textarea('description','',$description,array(),array(),'','tinymce'); ?>
            </div>
         </div>
      </div>
      <?php 
        $types = [];
      ?>
      <div role="tabpanel" class="tab-pane" id="segment_filters">
           <?php $types = [ 
            1 => ['id' => 'name', 'name' => _l('name')],
            2 => ['id' => 'title', 'name' => _l('position')],
            3 => ['id' => 'email', 'name' => _l('email')],
            4 => ['id' => 'website', 'name' => _l('lead_website')],
            5 => ['id' => 'phonenumber', 'name' => _l('phone')],
            7 => ['id' => 'lead_value', 'name' => _l('lead_value')],
            6 => ['id' => 'company', 'name' => _l('company')],
            8 => ['id' => 'address', 'name' => _l('lead_address')],
            9 => ['id' => 'city', 'name' => _l('city')],
            10 => ['id' => 'state', 'name' => _l('state')],
            11 => ['id' => 'country', 'name' => _l('country')],
            12 => ['id' => 'zip_code', 'name' => _l('zip_code')],
            13 => ['id' => 'default_language', 'name' => _l('language')],
            13 => ['id' => 'tag', 'name' => _l('tag')],
          ]; ?>
          <?php $follow_1 = [ 
            1 => ['id' => 'and', 'name' => _l('ma_and')],
            2 => ['id' => 'or', 'name' => _l('ma_or')],
          ]; ?>

            <?php $follow_2 = [ 
              1 => ['id' => 'equals', 'name' => _l('equals')],
              2 => ['id' => 'not_equal', 'name' => _l('not_equal')],
              3 => ['id' => 'greater_than', 'name' => _l('greater_than')],
              4 => ['id' => 'greater_than_or_equal', 'name' => _l('greater_than_or_equal')],
              5 => ['id' => 'less_than', 'name' => _l('less_than')],
              6 => ['id' => 'less_than_or_equal', 'name' => _l('less_than_or_equal')],
              7 => ['id' => 'empty', 'name' => _l('empty')],
              8 => ['id' => 'not_empty', 'name' => _l('not_empty')],
              9 => ['id' => 'like', 'name' => _l('like')],
              10 => ['id' => 'not_like', 'name' => _l('not_like')],
            ]; ?>
            <div class="list_approve">
              <?php if(isset($segment)){ ?>

              <?php foreach($segment->filters as $key => $filter){ ?>
                <div id="item_approve" class="border mtop10 padding-10">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="select-placeholder form-group">
                          <label for="sub_type_1[<?php echo html_entity_decode($key); ?>]"></label>
                          <select name="sub_type_1[<?php echo html_entity_decode($key); ?>]" id="sub_type_1[<?php echo html_entity_decode($key); ?>]" data-index="<?php echo html_entity_decode($key); ?>" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true">
                            <?php foreach($follow_1 as $val){
                                $selected = '';
                             if($val['id'] == $filter['sub_type_1']){
                                $selected = 'selected';
                              }
                              ?>
                              <option value="<?php echo html_entity_decode($val['id']); ?>" <?php echo html_entity_decode($selected); ?>>
                               <?php echo html_entity_decode($val['name']); ?>
                             </option>
                           <?php } ?>
                         </select>
                       </div> 
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="select-placeholder form-group">
                          <label for="type[<?php echo html_entity_decode($key); ?>]"></label>
                          <select name="type[<?php echo html_entity_decode($key); ?>]" id="type[<?php echo html_entity_decode($key); ?>]" data-index="<?php echo html_entity_decode($key); ?>" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true">
                            <?php foreach($types as $val){
                                $selected = '';
                             if($val['id'] == $filter['type']){
                                $selected = 'selected';
                              }
                              ?>
                              <option value="<?php echo html_entity_decode($val['id']); ?>" <?php echo html_entity_decode($selected); ?>>
                               <?php echo html_entity_decode($val['name']); ?>
                             </option>
                           <?php } ?>
                         </select>
                       </div> 
                    </div>
                   <div class="col-md-3">                            
                      <div class="select-placeholder form-group">
                        <label for="sub_type_2[<?php echo html_entity_decode($key); ?>]"></label>
                        <select name="sub_type_2[<?php echo html_entity_decode($key); ?>]" id="sub_type_2[<?php echo html_entity_decode($key); ?>]" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true">
                          <?php foreach($follow_2 as $val){
                                $selected = '';
                           if($val['id'] == $filter['sub_type_2']){
                            $selected = 'selected';
                          }
                          ?>
                          <option value="<?php echo html_entity_decode($val['id']); ?>" <?php echo html_entity_decode($selected); ?>>
                             <?php echo html_entity_decode($val['name']); ?>
                           </option>
                         <?php } ?>
                        </select>
                      </div> 
                   </div>
                   <div class="col-md-3">                            
                      <div class="form-group" app-field-wrapper="name">
                        <label for="value[<?php echo html_entity_decode($key); ?>]" class="control-label"></label>
                        <input type="value" id="value[<?php echo html_entity_decode($key); ?>]" name="value[<?php echo html_entity_decode($key); ?>]" class="form-control" value="<?php echo html_entity_decode($filter['value']); ?>">
                      </div>
                   </div>
                   <div class="col-md-1">
                      <?php if($key != 0){ ?>
                    <button name="add" class="btn remove_vendor_requests btn-danger mtop20" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
                <?php }else{ ?>
                    <button name="add" class="btn new_vendor_requests btn-success mtop20" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                <?php } ?>
                  </div>
                </div>
                </div>
              <?php } ?>
              <?php }else{ ?>
                <div id="item_approve" class="border mtop10 padding-10">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="select-placeholder form-group">
                          <label for="sub_type_1[0]"></label>
                          <select name="sub_type_1[0]" id="sub_type_1[0]" data-index="0" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true">
                            <?php foreach($follow_1 as $val){
                             $selected = '';
                             ?>
                             <option value="<?php echo html_entity_decode($val['id']); ?>">
                               <?php echo html_entity_decode($val['name']); ?>
                             </option>
                           <?php } ?>
                         </select>
                       </div> 
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="select-placeholder form-group">
                          <label for="type[0]"></label>
                          <select name="type[0]" id="type[0]" data-index="0" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true">
                            <?php foreach($types as $val){
                             $selected = '';
                             ?>
                             <option value="<?php echo html_entity_decode($val['id']); ?>">
                               <?php echo html_entity_decode($val['name']); ?>
                             </option>
                           <?php } ?>
                         </select>
                       </div> 
                    </div>
                   <div class="col-md-3" id="div_subtype_0">                            
                      <div class="select-placeholder form-group">
                        <label for="sub_type_2[0]"></label>
                        <select name="sub_type_2[0]" id="sub_type_2[0]" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true">
                          <?php foreach($follow_2 as $val){
                           $selected = '';
                           ?>
                           <option value="<?php echo html_entity_decode($val['id']); ?>">
                             <?php echo html_entity_decode($val['name']); ?>
                           </option>
                         <?php } ?>
                        </select>
                      </div> 
                   </div>
                   <div class="col-md-3">                            
                      <div class="form-group" app-field-wrapper="name">
                        <label for="value[0]" class="control-label"></label>
                        <input type="value" id="value[0]" name="value[0]" class="form-control" value="">
                      </div>
                   </div>
                   <div class="col-md-1">
                      <button name="add" class="btn new_vendor_requests btn-success mtop20" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                  </div>
                </div>
                </div>
              <?php } ?>
            </div>
                  </div>
               </div>
            </div>
            <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
              <button class="btn btn-info only-save" type="submit">
                <?php echo _l( 'submit'); ?>
              </button>
           </div>
            </div>
            <?php echo form_close(); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
     
   </div>
</div>
<?php init_tail(); ?>

</body>
</html>
