<?php echo $header;?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="$('#action').val('save_stay');$('#form').submit();" class="button"><?php echo $button_save_stay; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" name="action" id="action" value=""/>
         <div id="tabs" class="htabs">
            <a href="#tab-general-setting"><?php echo $this->language->get("tab_general_setting"); ?></a>
            <a href="#tab-block-position"><?php echo $this->language->get("tab_block_position"); ?></a>
        </div>
         <div id="tab-contents">

            <div id="tab-general-setting">
                <div class="tab-inner">

                  <table class="form">
                      <tr>
                        <td><?php echo $entry_public_key; ?></td>   
                        <td><input type="text" name="ecsocialcoupon_general[public_key]" value="<?php echo isset($general['public_key'])?$general['public_key']:'abc'; ?>" size="40" /> 
                        </td> 
                      </tr>
                      <tr>
                        <td><?php echo $entry_private_key; ?></td>   
                        <td><input type="text" name="ecsocialcoupon_general[private_key]" value="<?php echo isset($general['private_key'])?$general['private_key']:'123'; ?>" size="40" /></td> 
                      </tr>
                       <tr>
                        <td><?php echo $entry_share_website; ?></td>   
                        <td><input type="text" name="ecsocialcoupon_general[share_website]" value="<?php echo isset($general['share_website'])?$general['share_website']:'http://ecomteck.com'; ?>" size="40" /></td> 
                      </tr>
                      <tr>
                        <td><?php echo $entry_coupon; ?></td>   
                        <td><select name="ecsocialcoupon_general[coupon]" size="10">
                            <?php 
                             if(isset($coupons)){
                              foreach($coupons as $coupon){
                                if(isset($general["coupon"]) && $coupon["code"] == $general['coupon']){
                                  ?>
                                  <option value="<?php echo $coupon["code"]; ?>" selected="selected"><?php echo $coupon["name"]." (coupon: ".$coupon["code"].")";?></option>
                                  <?php
                                }else{
                                  ?>
                                   <option value="<?php echo $coupon["code"]; ?>"><?php echo $coupon["name"]." (coupon: ".$coupon["code"].")";?></option>  
                                  <?php
                                }
                              }
                             }
                            ?>
                          </select>
                          &nbsp;
                          <a href="<?php echo $create_coupon; ?>" target="_BLANK"><?php echo $this->language->get("text_create_coupon") ?></a>
                        </td> 
                      </tr>
                       <tr>
                        <td><?php echo $entry_enable_twitter; ?></td>
                        <td><select name="ecsocialcoupon_general[enable_twitter]">
                           <?php if ((isset($general['enable_twitter']) && $general['enable_twitter']) || !isset($general['enable_twitter'])) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                       <tr>
                        <td><?php echo $this->language->get("entry_enable_twitter_follow"); ?></td>
                        <td><select name="ecsocialcoupon_general[enable_twitter_follow]">
                           <?php if ((isset($general['enable_twitter_follow']) && $general['enable_twitter_follow']) || !isset($general['enable_twitter_follow'])) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                       <tr>
                        <td><?php echo $entry_enable_google; ?></td>
                        <td><select name="ecsocialcoupon_general[enable_google]">
                           <?php if ((isset($general['enable_google']) && $general['enable_google']) || !isset($general['enable_google'])) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                       <tr>
                        <td><?php echo $entry_enable_facebook; ?></td>
                        <td><select name="ecsocialcoupon_general[enable_facebook]">
                           <?php if ((isset($general['enable_facebook']) && $general['enable_facebook']) || !isset($general['enable_facebook'])) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $this->language->get("entry_enable_facebook_share"); ?></td>
                        <td><select name="ecsocialcoupon_general[enable_facebook_share]">
                           <?php if ((isset($general['enable_facebook_share']) && $general['enable_facebook_share']) || !isset($general['enable_facebook_share'])) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $this->language->get("entry_enable_linkedin"); ?></td>
                        <td><select name="ecsocialcoupon_general[enable_linkedin]">
                           <?php if ((isset($general['enable_linkedin']) && $general['enable_linkedin']) || !isset($general['enable_linkedin'])) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $this->language->get("entry_show_coupon_code"); ?></td>
                        <td><select name="ecsocialcoupon_general[show_coupon_code]">
                           <?php if ((isset($general['show_coupon_code']) && $general['show_coupon_code']) || !isset($general['show_coupon_code'])) { ?>
                            <option value="1" selected="selected"><?php echo $this->language->get("text_yes"); ?></option>
                            <option value="0"><?php echo $this->language->get("text_no"); ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $this->language->get("text_yes"); ?></option>
                            <option value="0" selected="selected"><?php echo $this->language->get("text_no"); ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $this->language->get("text_expire_date"); ?></td>
                        <td><input type="text" name="ecsocialcoupon_general[expire_date]" value="<?php echo isset($general['expire_date'])?$general['expire_date']:'7'; ?>" size="40" /></td> 
                      </tr>
                       <tr>
                        <td><?php echo $this->language->get("entry_facebook_app_id"); ?></td>
                        <td><input type="text" name="ecsocialcoupon_general[facebook_app_id]" value="<?php echo isset($general['facebook_app_id'])?$general['facebook_app_id']:'579922788744604'; ?>" size="40" /></td> 
                      </tr>
                       <tr>
                        <td><?php echo $this->language->get("entry_twitter_account"); ?></td>
                        <td><input type="text" name="ecsocialcoupon_general[twitter_account]" value="<?php echo isset($general['twitter_account'])?$general['twitter_account']:'ecomteck'; ?>" size="40" /></td> 
                      </tr>
                    </table>
                    <div id="language-general" class="htabs">
                     <?php foreach ($languages as $language) { ?>
                        <a href="#tab-language-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                        <?php } ?>
                      </div>
                      <?php foreach ($languages as $language) { ?>
                      <div id="tab-language-<?php echo $language['language_id']; ?>">
                        <table class="form">
                          <tr>
                            <td><?php echo $entry_tweet_text; ?></td>   
                            <td><input type="text" name="ecsocialcoupon_general[tweet_text][<?php echo $language['language_id']; ?>]" value="<?php echo isset($general['tweet_text'][$language['language_id']])?$general['tweet_text'][$language['language_id']]:'Get more extensions: '; ?>" size="40" /></td> 
                          </tr>
                          
                          <tr>
                            <td><?php echo $this->language->get("entry_share_title"); ?></td>   
                            <td><input type="text" name="ecsocialcoupon_general[share_title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($general['share_title'][$language['language_id']])?$general['share_title'][$language['language_id']]:'Great Staff'; ?>" size="40" /></td> 
                          </tr>
                          <tr>
                            <td><?php echo $this->language->get("entry_share_image"); ?></td>   
                            <td><input type="text" name="ecsocialcoupon_general[share_image][<?php echo $language['language_id']; ?>]" value="<?php echo isset($general['share_image'][$language['language_id']])?$general['share_image'][$language['language_id']]:'http://ecomteck.com/opencart/image/data/logo.png'; ?>" size="71" /></td> 
                          </tr>
                          <tr>
                            <td><?php echo $this->language->get("entry_share_message"); ?></td>   
                            <td><textarea cols="15" rows="5" name="ecsocialcoupon_general[share_message][<?php echo $language['language_id']; ?>]" style="width:400px"><?php echo isset($general['share_message'][$language['language_id']])?$general['share_message'][$language['language_id']]:'We get great staff, the site is good for you!'; ?></textarea></td> 
                          </tr>
                          
                          <tr><td colspan="2"><?php echo $this->language->get("entry_message_setting");?></td></tr>
                          <tr>
                            <td><?php echo $entry_module_message; ?></td>  
                            <td><textarea name="ecsocialcoupon_general[module_message][<?php echo $language['language_id']; ?>]" id="module_message-<?php echo $language['language_id']; ?>"><?php echo isset($general['module_message'][$language['language_id']]) ? $general['module_message'][$language['language_id']] : 'Share or like to get $10 off your order! '; ?></textarea></td> 
                          </tr>
                          <tr>
                            <td><?php echo $entry_notify_message; ?></td>  
                            <td><textarea name="ecsocialcoupon_general[notify_message][<?php echo $language['language_id']; ?>]" id="notify_message-<?php echo $language['language_id']; ?>"><?php echo isset($general['notify_message'][$language['language_id']]) ? $general['notify_message'][$language['language_id']] : 'You get 10% discount!'; ?></textarea></td> 
                          </tr>
                        </table>
                      </div>
                      <?php } ?>
                </div>
            </div>
            <div id="tab-block-position">
                <div class="tab-inner">
                  <div class="vtabs">
                    <?php $module_row = 1; ?>
                    <?php foreach ($modules as $module) { ?>
                    <a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>"><?php echo $tab_block . ' ' . $module_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
                    <?php $module_row++; ?>
                    <?php } ?>
                    <span id="module-add"><?php echo $button_add_new_block; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addModule();" /></span> 
                  </div>
                  <?php $module_row = 1; ?>
                  <?php foreach ($modules as $module) { ?>
                  <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">  
                    <table class="form">
                      <tr>
                        <td><?php echo $entry_layout; ?></td>
                        <td><select name="ecsocialcoupon_module[<?php echo $module_row; ?>][layout_id]">
                            <?php if ($module['layout_id'] == 0) { ?>
                            <option value="0" selected="selected"><?php echo $text_alllayout; ?></option>
                            <?php } else { ?>
                            <option value="0"><?php echo $text_alllayout; ?></option>
                            <?php } ?>
                            <?php foreach ($layouts as $layout) { ?>
                            <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select></td>
                      </tr>
                <tr>
                  <td><?php echo $entry_store; ?></td>
                        <td><div class="scrollbox">
                            <?php $class = 'even'; ?>
                            <div class="<?php echo $class; ?>">
                              <?php if (isset($module['store_id']) && in_array(0, $module['store_id'])) { ?>
                              <input type="checkbox" name="ecsocialcoupon_module[<?php echo $module_row; ?>][store_id][]" value="0" checked="checked" />
                              <?php } else { ?>
                              <input type="checkbox" name="ecsocialcoupon_module[<?php echo $module_row; ?>][store_id][]" value="0" />
                              <?php } ?>
                    <?php echo $text_default; ?>
                            </div>
                            <?php foreach ($stores as $store) { ?>
                            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                            <div class="<?php echo $class; ?>">
                              <?php if (isset($module['store_id']) && in_array($store['store_id'], $module['store_id'])) { ?>
                              <input type="checkbox" name="ecsocialcoupon_module[<?php echo $module_row; ?>][store_id][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                              <?php echo $store['name']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="ecsocialcoupon_module[<?php echo $module_row; ?>][store_id][]" value="<?php echo $store['store_id']; ?>" />
                              <?php echo $store['name']; ?>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_position; ?></td>
                        <td><select name="ecsocialcoupon_module[<?php echo $module_row; ?>][position]">
                                     <?php foreach( $positions as $pos ) { ?>
                                              <?php if ($module['position'] == $pos) { ?>
                                              <option value="<?php echo $pos;?>" selected="selected"><?php echo $this->language->get('text_'.$pos); ?></option>
                                              <?php } else { ?>
                                              <option value="<?php echo $pos;?>"><?php echo $this->language->get('text_'.$pos); ?></option>
                                              <?php } ?>
                                              <?php } ?> 
                                            </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="ecsocialcoupon_module[<?php echo $module_row; ?>][status]">
                            <?php if ($module['status']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="ecsocialcoupon_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                      </tr>
                      <tr>
                        <td><?php echo $this->language->get("entry_popup_mode"); ?></td>
                        <td><select name="ecsocialcoupon_module[<?php echo $module_row; ?>][popup_mode]">
                            <?php if (isset($module['popup_mode']) && $module['popup_mode']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $this->language->get("entry_redirect_to_checkout_cart"); ?></td>
                        <td><select name="ecsocialcoupon_module[<?php echo $module_row; ?>][redirect]">
                            <?php if ($module['redirect']) { ?>
                            <option value="1" selected="selected"><?php echo $this->language->get("text_yes"); ?></option>
                            <option value="0"><?php echo $this->language->get("text_no"); ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $this->language->get("text_yes"); ?></option>
                            <option value="0" selected="selected"><?php echo $this->language->get("text_no"); ?></option>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td><?php echo $this->language->get("entry_popup_width"); ?></td>
                        <td><input type="text" name="ecsocialcoupon_module[<?php echo $module_row; ?>][popup_width]" value="<?php echo isset($module['popup_width'])?$module['popup_width']:'50%'; ?>" size="5" /></td>
                      </tr>
                      <tr>
                        <td><?php echo $this->language->get("entry_module_width_height"); ?></td>
                        <td><input type="text" name="ecsocialcoupon_module[<?php echo $module_row; ?>][module_width]" value="<?php echo isset($module['module_width'])?$module['module_width']:'auto'; ?>" size="10" /> - <input type="text" name="ecsocialcoupon_module[<?php echo $module_row; ?>][module_height]" value="<?php echo isset($module['module_height'])?$module['module_height']:'auto'; ?>" size="10" /></td>
                      </tr>
                     
                    </table>
                  </div>
                  <?php $module_row++; ?>
                  <?php } ?>
                </div>
            </div>
         </div>
        
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
function initTextEditor(){
  <?php foreach ($languages as $language) { ?>
  CKEDITOR.replace('module_message-<?php echo $language['language_id']; ?>', {
    filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
  });
  <?php } ?>
  <?php foreach ($languages as $language) { ?>
  CKEDITOR.replace('notify_message-<?php echo $language['language_id']; ?>', {
    filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
  });
  <?php } ?>
}
initTextEditor();
//--></script> 
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;
function addModule() {
  html ='<div id="tab-module-'+module_row+'" class="vtabs-content"> ';
  html +='         <table class="form">';
  html +='          <tr>';
  html +='            <td><?php echo $entry_layout; ?></td>';
  html +='            <td><select name="ecsocialcoupon_module['+module_row+'][layout_id]">';
  html +='                <option value="0"><?php echo $text_alllayout; ?></option>';
                  <?php foreach ($layouts as $layout) { ?>
  html +='                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
                  <?php } ?>
  html +='              </select></td>';
  html +='          </tr>';
  html +='    <tr>';
  html +='      <td><?php echo $entry_store; ?></td>';
  html +='            <td><div class="scrollbox">';
                  <?php $class = 'even'; ?>
  html +='                <div class="<?php echo $class; ?>">';
  html +='                  <input type="checkbox" name="ecsocialcoupon_module['+module_row+'][store_id][]" value="0" checked="checked"/>';
  html +='        <?php echo $text_default; ?>';
  html +='                </div>';
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html +='                <div class="<?php echo $class; ?>">';
  html +='                  <input type="checkbox" name="ecsocialcoupon_module['+module_row+'][store_id][]" value="<?php echo $store['store_id']; ?>" />';
  html +='                  <?php echo $store['name']; ?>';
  html +='                </div>';
                  <?php } ?>
  html +='              </div></td>';
  html +='    </tr>';
  html +='          <tr>';
  html +='            <td><?php echo $entry_position; ?></td>';
  html +='            <td><select name="ecsocialcoupon_module['+module_row+'][position]">';
                           <?php foreach( $positions as $pos ) { ?>
  html +='                                  <option value="<?php echo $pos;?>"><?php echo $this->language->get('text_'.$pos); ?></option>';
                                    <?php } ?> 
  html +='                                </select></td>';
  html +='          </tr>';
  html +='          <tr>';
  html +='            <td><?php echo $entry_status; ?></td>';
  html +='            <td><select name="ecsocialcoupon_module['+module_row+'][status]">';
  html +='                <option value="1"><?php echo $text_enabled; ?></option>';
  html +='                <option value="0"><?php echo $text_disabled; ?></option>';

  html +='              </select></td>';
  html +='          </tr>';
  html +='          <tr>';
  html +='            <td><?php echo $entry_sort_order; ?></td>';
  html +='            <td><input type="text" name="ecsocialcoupon_module['+module_row+'][sort_order]" value="" size="3" /></td>';
  html +='          </tr>';
  html +='  <tr>';
  html +='                      <td><?php echo $this->language->get("entry_popup_mode"); ?></td>';
  html +='                      <td><select name="ecsocialcoupon_module['+module_row+'][popup_mode]">';
  html +='                          <option value="1"><?php echo $text_enabled; ?></option>';
  html +='                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';

  html +='                        </select></td>';
  html +='                    </tr>';
  html +='  <tr>';
  html +='                      <td><?php echo $this->language->get("entry_redirect_to_checkout_cart"); ?></td>';
  html +='                      <td><select name="ecsocialcoupon_module['+module_row+'][redirect]">';
  html +='                          <option value="1"><?php echo $this->language->get("text_yes"); ?></option>';
  html +='                          <option value="0" selected="selected"><?php echo $this->language->get("text_no"); ?></option>';

  html +='                        </select></td>';
  html +='                    </tr>';
  html +='<tr>';
  html +='                      <td><?php echo $this->language->get("entry_popup_width"); ?></td>';
  html +='                      <td><input type="text" name="ecsocialcoupon_module['+module_row+'][popup_width]" value="50%" size="5" /></td>';
  html +='                    </tr>';
  html +='<tr>';
  html +='                      <td><?php echo $this->language->get("entry_module_width_height"); ?></td>';
  html +='                      <td><input type="text" name="ecsocialcoupon_module['+module_row+'][module_width]" value="auto" size="10" /> - <input type="text" name="ecsocialcoupon_module['+module_row+'][module_height]" value="auto" size="10" /></td>';
  html +='                    </tr>';
  html +='        </table>';
  html +='      </div>';
  
  $('#tab-block-position').find(".tab-inner").first().append(html);
  
  $('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '"><?php echo $tab_block; ?> ' + module_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
  
  $('.vtabs a').tabs();
  $('#language-general a').tabs();
  $('#module-' + module_row).trigger('click');
  initTextEditor();
  module_row++;
}
//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
$('#tabs a').tabs();
//--></script>
<script type="text/javascript"><!--
$('#language-general a').tabs();
//--></script> 
<?php echo $footer; ?>