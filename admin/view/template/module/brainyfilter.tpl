<?php
/**
* BrainyFilter 2.2, April 19, 2014 / brainyfilter.com
* Copyright 2014 Giant Leap Lab / www.giantleaplab.com
* License: Commercial. Reselling of this software or its derivatives is not allowed. You may use this software for one website ONLY including all its subdomains if the top level domain belongs to you and all subdomains are parts of the same OpenCart store.
* Support: support@giantleaplab.com
*/
?>
<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if (count($error_warning)) : ?>
    <?php foreach($error_warning as $err) : ?>
      <div class="warning"><?php echo $err; ?></div>
    <?php endforeach; ?>
  <?php endif; ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
      <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
      <a onclick="applyAction();return false;" href="#" class="button"><?php echo $button_apply; ?></a>
      <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
    </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <input type="hidden" name="action" value="save" />
      <input type="hidden" id="bf-adm-cur-tab" name="attr_setting[active_tab]" value="<?php echo (int)$setting['active_tab']; ?>" />
          <div class="htabs" id="tabs">
           <a href="#tab-position" style="display: inline;" <?php if ($setting['active_tab'] == 0) : ?>class="selected" <?php endif; ?>><?php echo $entry_tab_position; ?></a>
           <a href="#tab-mode" style="display: inline;" <?php if ($setting['active_tab'] == 1) : ?>class="selected" <?php endif; ?>><?php echo $entry_tab_mode; ?></a>
           <a href="#tab-attr" style="display: inline;" <?php if ($setting['active_tab'] == 2) : ?>class="selected" <?php endif; ?>><?php echo $entry_tab_attr; ?></a>
       <a href="#tab-ench" style="display: inline;" <?php if ($setting['active_tab'] == 3) : ?>class="selected" <?php endif; ?>><?php echo $entry_tab_enchancements; ?></a>
          </div>
           <?php $module_row = 0; ?>
            <div id="module-row<?php echo $module_row; ?>">
              <div id="tab-position" style="display: block;">
                   <table class="form" style="width:850px;">
                    <tr>
                      <td><?php echo $entry_layout; ?></td>
                      <td>
                        <select name="brainyfilter_module[<?php echo $module_row; ?>][layout_id]">
                          <?php $curLayout = (isset($modules[$module_row]) && $modules[$module_row]['layout_id']) ? $modules[$module_row]['layout_id'] : $setting['layout_id']; ?>
                          <?php foreach ($layouts as $layout) { ?>
                            <?php if ($layout['layout_id'] == $curLayout) { ?>
                              <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                            <?php } else { ?>
                              <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo $entry_position; ?></td>
                      <td><select name="brainyfilter_module[<?php echo $module_row; ?>][position]">
              <?php $curPosition = (isset($modules[$module_row]) && $modules[$module_row]['position']) ? $modules[$module_row]['position'] : $setting['layout_position']; ?>
                          <?php if ($curPosition == 'content_top') { ?>
                            <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                          <?php } else { ?>
                            <option value="content_top"><?php echo $text_content_top; ?></option>
                          <?php } ?>
                          <?php if ($curPosition == 'content_bottom') { ?>
                            <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                          <?php } else { ?>
                            <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                          <?php } ?>
                          <?php if ($curPosition == 'column_left') { ?>
                            <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                          <?php } else { ?>
                            <option value="column_left"><?php echo $text_column_left; ?></option>
                          <?php } ?>
                          <?php if ($curPosition == 'column_right') { ?>
                            <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                          <?php } else { ?>
                            <option value="column_right"><?php echo $text_column_right; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo $entry_status; ?></td>
                      <td><select name="brainyfilter_module[<?php echo $module_row; ?>][status]">
                          <?php if ($modules[$module_row]['status']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="brainyfilter_module[<?php echo $module_row; ?>][sort_order]" value="<?php if (isset($modules[$module_row]['sort_order'])) { echo $modules[$module_row]['sort_order'];  }?>" size="3" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_block_title; ?></td>
                        <td><input type="text" name="attr_setting[block_title]" value="<?php echo $setting['block_title']; ?>" /></td>
                    </tr>
                    </table>
                </div>
          <?php $module_row++; ?>
            </div>
            <div id="tab-mode" style="display: none;">
        <table class="form" style="width:850px;">
          <tr>
            <th colspan="4" class="bf-admin-header"><?php echo $entry_data_submission; ?> </th>
          </tr>
          <tr>
            <td colspan="4"><?php echo $entry_define; ?> </td>
          </tr>
          <tr>
            <td><label for="auto_on_change"><?php echo $entry_auto_submission; ?></label></td>
            <td colspan="3"> <input type="radio" value="auto" name="attr_setting[submit_type]" onclick="delay($(this))" id="auto_on_change"  <?php if (!isset($setting['submit_type']) || $setting['submit_type'] == 'auto') echo ' checked="checked"'; ?> /></td>
          </tr>
          <tr>
            <td><label for="delay_change"><?php echo $entry_auto_submission_delay; ?></label></td>
            <td> <input type="radio" value="delay" name="attr_setting[submit_type]" onclick="delay($(this))" id="delay_change" <?php if (isset($setting['submit_type']) && $setting['submit_type'] == 'delay') echo ' checked="checked"'; ?> /></td>
            <td colspan="2"> 
              <input type="text" name="attr_setting[submit_delay_time]" id="delay_time" 
                  <?php if ($setting['submit_type'] != 'delay') : ?> readonly="readonly" <?php endif; ?> 
                   value="<?php echo $setting['submit_delay_time']; ?>" size="4" maxlength="4"/>
              <label for="delay_time"><?php echo $entry_auto_time; ?></label>
            </td>
          </tr>
          <tr>
            <td> <label for="button_change"><?php echo $entry_button; ?></label></td>
            <td> <input type="radio" value="button" name="attr_setting[submit_type]" onclick="delay($(this))" id="button_change" <?php if (isset($setting['submit_type']) && $setting['submit_type'] == 'button') echo ' checked="checked"'; ?> /></td>
            <td colspan="2"><input id="fix" type="radio" name="attr_setting[submit_button_type]" value="fix" 
                   <?php if ($setting['submit_type'] != 'button') : ?> disabled="disabled" <?php endif; ?>
                   <?php if ($setting['submit_button_type'] == 'fix') echo " checked='checked'" ?> />
              <label for="fix"><?php echo $entry_fixed; ?></label>
              <div class="bf-suboption">
                <input id="float" type="radio" name="attr_setting[submit_button_type]" value="float"
                  <?php if ($setting['submit_type'] != 'button') : ?> disabled="disabled" <?php endif; ?>
                  <?php if ($setting['submit_button_type'] == 'float') echo " checked='checked'" ?> />
                <label for="float"><?php echo $entry_float; ?></label></td>
            </div>
          </tr>
          <tr>
            <td>
              <?php echo $entry_hide_panel; ?>
            </td>
            <td colspan="3">
              <input id="hide_panel_off" type="radio" name="attr_setting[hide_panel]"
                  value="0" <?php if ($setting['hide_panel'] == '0') echo " checked='checked'" ?> />
              <label for="hide_panel_off"><?php echo $text_no; ?></label>
              <input id="hide_panel_on" type="radio" name="attr_setting[hide_panel]"
                  value="1" <?php if ($setting['hide_panel'] == '1') echo " checked='checked'" ?> />
              <label for="hide_panel_on"><?php echo $text_yes; ?></label>
            </td>
          </tr>
          <tr>
            <th colspan="4" class="bf-admin-header"><?php echo $text_attributes_display; ?></th>
          </tr>
          
          <tr>
            <td>
              <?php echo $entry_price_filter; ?>
            </td>
            <td>
              <input id="price_filter_off" type="radio" name="attr_setting[price_filter]"
                  value="0" <?php if (!isset($setting['price_filter']) || $setting['price_filter'] == '0') echo " checked='checked'" ?> />
              <label for="price_filter_off"><?php echo $text_no; ?></label>
              <input id="price_filter_on" type="radio" name="attr_setting[price_filter]"
                  value="1" <?php if (isset($setting['price_filter']) && $setting['price_filter'] == '1') echo " checked='checked'" ?> />
              <label for="price_filter_on"><?php echo $text_yes; ?></label>
            </td>
             <td colspan="2">
              <input id="sort_price" type="text" size="4"  max="99" maxlength="2" name="attr_setting[sort_price]"   <  value="<?php echo $setting['sort_price']; ?>">
              <label for="sort_price"><?php echo $entry_sort_order; ?></label><br />
              <div class="bf-suboption">
                <input id="collapse_price_on" type="checkbox" name="attr_setting[collapse_price]"
                  value="1" <?php if (isset($setting['collapse_price'])) echo " checked='checked'" ?> />
                <label for="collapse_price_on"><?php echo $entry_collapse; ?></label>
              </div>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_stock_status; ?></td>
            <td><input id="text_stock_status_off" type="radio" name="attr_setting[stock_status]"
                         value="0" <?php if ($setting['stock_status'] == '0') echo " checked='checked'" ?>>
              <label for="text_stock_status_off"><?php echo $text_no; ?></label>
              <input id="text_stock_status_on" type="radio" name="attr_setting[stock_status]"
                   value="1" <?php if ($setting['stock_status'] == '1') echo " checked='checked'" ?>>
              <label for="text_stock_status_on"><?php echo $text_yes; ?></label>
            </td>
            <td>
              <input id="sort_stock" type="text" size="4"  max="99" maxlength="2" name="attr_setting[sort_stock]" value="<?php echo $setting['sort_stock']; ?>">
              <label for="sort_stock"><?php echo $entry_sort_order; ?></label>
              <div class="bf-suboption">
                <input id="collapse_stock_on" type="checkbox" name="attr_setting[collapse_stock]"
                  value="1" <?php if (isset($setting['collapse_stock'])) echo " checked='checked'" ?> />
                <label for="collapse_stock_on"><?php echo $entry_collapse; ?></label></div>
              </div>            
              <div class="bf-suboption">
                <select name="attr_setting[stock_status_id]">
                  <?php foreach ($stock_statuses as $stock_status) { ?>
                  <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
                  <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                 <?php echo $entry_stock_status_id; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_manufacturer; ?></td>
            <td><input id="text_manufacturer_off" type="radio" name="attr_setting[manufacturer]"
                         value="0" <?php if ($setting['manufacturer'] == '0') echo " checked='checked'" ?>>
              <label for="text_manufacturer_off"><?php echo $text_no; ?></label>
              <input id="text_manufacturer_on" type="radio" name="attr_setting[manufacturer]"
                   value="1" <?php if ($setting['manufacturer'] == '1') echo " checked='checked'" ?>>
              <label for="text_manufacture_on"><?php echo $text_yes; ?></label>
            </td>
            <td colspan="2">
              <input id="sort_manufacturer" type="text" size="4"  max="99" maxlength="2" name="attr_setting[sort_manufacturer]"    value="<?php echo $setting['sort_manufacturer']; ?>">
              <label for="sort_manufacturer"><?php echo $entry_sort_order; ?></label>
              <div class="bf-suboption">
                <input id="collapse_manufacturer_on" type="checkbox" name="attr_setting[collapse_manufacturer]"
                  value="1" <?php if (isset($setting['collapse_manufacturer'])) echo " checked='checked'" ?> />
                <label for="collapse_manufacturer_on"><?php echo $entry_collapse; ?></label>
              </div>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_enable_attributes; ?></td>
            <td><input id="text_enable_attr_off" type="radio" name="attr_setting[enable_attr]"
                         value="0" <?php if ($setting['enable_attr'] == '0') echo " checked='checked'" ?>>
              <label for="text_enable_attr_off"><?php echo $text_no; ?></label>
              <input id="text_enable_attr_on" type="radio" name="attr_setting[enable_attr]"
                   value="1" <?php if ($setting['enable_attr'] == '1') echo " checked='checked'" ?> onclick="enableAttr($(this))">
              <label for="text_enable_attr_on"><?php echo $text_yes; ?></label>
            </td>
            <td colspan="2">
              <input id="sort_attr" type="text" size="4"  max="99" maxlength="2" name="attr_setting[sort_attr]"  value="<?php echo $setting['sort_attr']; ?>">
                <label for="sort_attr"><?php echo $entry_sort_order; ?></label>
              <div class="bf-suboption">
                <input id="collapse_attr_on" type="checkbox" name="attr_setting[collapse_attr]" value="1" <?php if (isset($setting['collapse_attr'])) echo " checked='checked'" ?> />
                <label for="collapse_attr_on"><?php echo $entry_collapse; ?></label>
              </div>
            </td>
          </tr>
            <tr>
            <td><?php echo $entry_group_by; ?></td>
            <td colspan="3"><input id="text_attr_group_off" type="radio" name="attr_setting[attr_group]"
                         value="0" <?php if ($setting['attr_group'] == '0') echo " checked='checked'" ?> >
              <label for="text_attr_group_off"><?php echo $text_no; ?></label>
              <input id="text_attr_group_on" type="radio" name="attr_setting[attr_group]"
                   value="1" <?php if ($setting['attr_group'] == '1') echo " checked='checked'" ?>>
              <label for="text_attr_group_on"><?php echo $text_yes; ?></label>

            </td>
            
          </tr>
          <tr>
            <td><?php echo $entry_opencart_filters; ?></td>
            <td><input id="text_opencart_filters_off" type="radio" name="attr_setting[opencart_filters]"
                         value="0" <?php if ($setting['opencart_filters'] == '0') echo " checked='checked'" ?>>
              <label for="text_opencart_filters_off"><?php echo $text_no; ?></label>
              <input id="text_opencart_filters_on" type="radio" name="attr_setting[opencart_filters]"
                   value="1" <?php if ($setting['opencart_filters'] == '1') echo " checked='checked'" ?>>
              <label for="text_opencart_filters_on"><?php echo $text_yes; ?></label>
            </td>
            <td colspan="3">
              <input id="sort_opencart_filters" type="text" size="4"  max="99" maxlength="2" name="attr_setting[sort_opencart_filters]"    value="<?php echo $setting['sort_opencart_filters']; ?>">
              <label for="sort_opencart_filters"><?php echo $entry_sort_order; ?></label>
              <div class="bf-suboption">
                <input id="collapse_opencart_filters_on" type="checkbox" name="attr_setting[collapse_opencart_filters]"
                  value="1" <?php if (isset($setting['collapse_opencart_filters'])) echo " checked='checked'" ?> />
                <label for="collapse_opencart_filters_on"><?php echo $entry_collapse; ?></label>
              </div>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_rating; ?></td>
            <td><input id="text_rating_off" type="radio" name="attr_setting[rating]"
                         value="0" <?php if ($setting['rating'] == '0') echo " checked='checked'" ?>>
              <label for="text_rating_off"><?php echo $text_no; ?></label>
              <input id="text_rating_on" type="radio" name="attr_setting[rating]"
                   value="1" <?php if ($setting['rating'] == '1') echo " checked='checked'" ?>>
              <label for="text_rating_on"><?php echo $text_yes; ?></label>
            </td>
            <td colspan="2">
              <input id="sort_rating" type="text" size="4"  max="99" maxlength="2" name="attr_setting[sort_rating]"    value="<?php echo $setting['sort_rating']; ?>">
              <label for="sort_rating"><?php echo $entry_sort_order; ?></label>
              <div class="bf-suboption">
                <input id="collapse_rating_on" type="checkbox" name="attr_setting[collapse_rating]" value="1" <?php if (isset($setting['collapse_rating'])) echo " checked='checked'" ?> />
                <label for="collapse_rating_on"><?php echo $entry_collapse; ?></label>
              </div>
            </td>
          </tr>
          <?php if(0): ?><tr>
            <td><?php echo $entry_option ?></td>
            <td><input id="text_option_off" type="radio" name="attr_setting[option]"
                         value="0" <?php if ($setting['option'] == '0') echo " checked='checked'" ?>>
              <label for="text_option_off"><?php echo $text_no; ?></label>
              <input id="text_option_on" type="radio" name="attr_setting[option]"
                   value="1" <?php if ($setting['option'] == '1') echo " checked='checked'" ?>>
              <label for="text_option_on"><?php echo $text_yes; ?></label>
            </td>
            <td colspan="2">
              <input id="sort_option" type="text" size="4"  max="99" maxlength="2" name="attr_setting[sort_option]"    value="<?php echo $setting['sort_option']; ?>">
                <label for="sort_option"><?php echo $entry_sort_order; ?></label>
              <input id="collapse_option_on" type="checkbox" name="attr_setting[collapse_option]"
                  value="1" <?php if (isset($setting['collapse_option'])) echo " checked='checked'" ?> />
              <label for="collapse_option_on"><?php echo $entry_collapse; ?></label>
              
                <input id="image_and_label" type="radio" name="attr_setting[image_and_label]"
                      value="2" <?php if ($setting['image_and_label'] == '2') echo " checked='checked'" ?>/>
                <label for="image_and_label"><?php echo $entry_image_and_label; ?></label>
               
                 <input id="option_label" type="radio" name="attr_setting[image_and_label]"
                      value="0" <?php if ($setting['image_and_label'] == '0') echo " checked='checked'" ?> />
                  <label for="option_label"><?php echo $entry_label; ?></label>
                
                  <input id="option_image" type="radio" name="attr_setting[image_and_label]"
                          value="1" <?php if ($setting['image_and_label'] == '1') echo " checked='checked'" ?> />
                   <label for="option_image"><?php echo $entry_image; ?></label>
            </td>
          </tr><?php endif; ?>
          <tr>
            <td><?php echo $entry_product_count; ?></td>
            <td colspan="3"><input id="text_product_count_off" type="radio" name="attr_setting[product_count]"
                         value="0" <?php if ($setting['product_count'] == '0')  echo " checked='checked'" ?>>
              <label for="text_product_count_off"><?php echo $text_no; ?></label>
              <input id="text_product_count_on" type="radio" name="attr_setting[product_count]"
                   value="1" <?php if ($setting['product_count'] == '1') echo " checked='checked'" ?>>
              <label for="text_product_count_on"><?php echo $text_yes; ?></label>
            </td>
          </tr>
           <tr>
            <td><?php echo $entry_sliding; ?></td>
           <td>
              <input id="sliding_filter_off" type="radio" name="attr_setting[sliding]"
                  value="0" <?php if (!isset($setting['sliding']) || $setting['sliding'] == '0') echo " checked='checked'" ?> onclick="delay($(this))"/>
              <label for="sliding_filter_off"><?php echo $text_no; ?></label>
              <input id="sliding_filter_on" type="radio" name="attr_setting[sliding]"
                  value="1" <?php if (isset($setting['sliding']) && $setting['sliding'] == '1') echo " checked='checked'" ?> onclick="delay($(this))"/>
              <label for="sliding_filter_on"><?php echo $text_yes; ?></label>
            </td>
            <td colspan="3"> 
              <input type="text" size="4" name="attr_setting[sliding_opts]" id="sliding_opts" 
                <?php if ($setting['sliding'] != 1) : ?> readonly="readonly" <?php endif; ?> 
                     value="<?php echo $setting['sliding_opts']; ?>"/>
              <label for="sliding_opts"><?php echo $entry_sliding_opts; ?></label>
              <div class="bf-suboption">
                <input type="text" size="4" name="attr_setting[sliding_min]" id="sliding_min" 
                    <?php if ($setting['sliding'] != 1) : ?> readonly="readonly" <?php endif; ?> 
                     value="<?php echo $setting['sliding_min']; ?>"/> 
                <label for="sliding_min"><?php echo $entry_sliding_min; ?></label>
              </div>
            </td>
          </tr>
            <tr>
            <td><?php echo $entry_limit_height; ?></td>
           <td>
              <input id="limit_height_off" type="radio" name="attr_setting[limit_height]"
                  value="0" <?php if (!isset($setting['limit_height']) || $setting['limit_height'] == '0') echo " checked='checked'" ?> onclick="delay($(this))"/>
              <label for="limit_height_off"><?php echo $text_no; ?></label>
              <input id="limit_height_on" type="radio" name="attr_setting[limit_height]"
                  value="1" <?php if (isset($setting['limit_height']) && $setting['limit_height'] == '1') echo " checked='checked'" ?>  onclick="delay($(this))"/>
              <label for="limit_height_on"><?php echo $text_yes; ?></label>
            </td>
            <td colspan="3"> 
                <input type="text" size="4" name="attr_setting[limit_height_opts]" id="limit_height_opts" 
                    <?php if ($setting['limit_height'] != 1) : ?> readonly="readonly" <?php endif; ?> 
                     value="<?php echo $setting['limit_height_opts']; ?>"/> 
                <label for="limit_height_opts"><?php echo $entry_limit_height_opts; ?></label>
            </td>
          </tr>
        </table>
      </div> 
            <div id="tab-attr" style="display: none;">
                    <table class="form" style="width:850px;">
                      <tr>
                          <td><label for="select_all"><?php echo $entry_select_all; ?></label></td>
                          <td colspan="2"><input type="checkbox" name="select_all" id="select_all" onclick="attr(this);enableAttr($(this))"></td>
                      </tr>
                    <?php $attribute_group = false;?>
                    <?php foreach($attributes as $attribute){?>
                      <?php if ($attribute_group !=$attribute['attribute_group']) { ?>
                        <?php $attribute_group = $attribute['attribute_group'];?>
                        <tr>
                          <th colspan="3" class="bf-admin-header">Attribute group: <?php echo $attribute['attribute_group']?></th>
                        </tr>
                    <?php } ?>
                    <tr>
                    <td>
                      <label for="<?php echo $attribute['attribute_id'] ?>"><?php echo $attribute['name'] ?></label>
                    </td>
                    <td>
                      <input class="attributes" type="checkbox" id="<?php echo $attribute['attribute_id'] ?>" name="attr_setting[expanded_attribute_<?php echo $attribute['attribute_id'] ?>]" <?php if (isset($setting['expanded_attribute_' . $attribute['attribute_id']])) echo ' checked="checked"'; ?> onclick="enableAttr($(this))" />
                    </td>
                    <td>
                      <select name="attr_setting[display_attribute_<?php echo $attribute['attribute_id'] ?>]">
                        <option value="checkbox" <?php if (isset($setting['display_attribute_' . $attribute['attribute_id']]) && 'checkbox' == $setting['display_attribute_' . $attribute['attribute_id']]) echo ' selected="selected"'; ?>>
                          checkbox
                        </option>
                        <option value="select" <?php if (isset($setting['display_attribute_' . $attribute['attribute_id']]) && 'select' == $setting['display_attribute_' . $attribute['attribute_id']]) echo '  selected="selected"'; ?>>
                          select
                        </option>
                        <option value="radio" <?php if (isset($setting['display_attribute_' . $attribute['attribute_id']]) && 'radio' == $setting['display_attribute_' . $attribute['attribute_id']]) echo '  selected="selected"'; ?>>
                          radio
                        </option>
                      </select>
                    </td>
                    </tr>
                  <?php }?>
              </table>
            </div>
      <div id="tab-ench" style="display: block;">
        <table class="form" style="width:850px;">
          <tr>
          <tr>
            <td><?php echo $entry_subcats_fix; ?></td>
            <td colspan="3"><input id="text_subcategories_fix_off" type="radio" name="attr_setting[subcategories_fix]"
                       value="0" <?php if ($setting['subcategories_fix'] == '0') echo " checked='checked'" ?>>
              <label for="text_subcategories_fix_off"><?php echo $text_no; ?></label>
              <input id="text_subcategories_fix_on" type="radio" name="attr_setting[subcategories_fix]"
                 value="1" <?php if ($setting['subcategories_fix'] == '1') echo " checked='checked'" ?>>
              <label for="text_subcategories_fix_on"><?php echo $text_yes; ?></label>
            </td>
          </tr>
            
          </tr>
        </table>
      </div>
      </form>
    </div>
  </div>
  <div class="bf-signature"><?php echo $bf_signature; ?></div>
</div>
<script type="text/javascript"><!--
 function attr(checkbox) {
        if (checkbox.checked) {
           jQuery('.attributes').attr('checked','checked');
           return false;
        }else{
          jQuery('.attributes').removeAttr('checked');
          return false;
        }
  }
   
  var f1 = false, f2 = false;
  function enableAttr(checkbox) {
    var checked = true;
    var thisID = checkbox.attr('id');
        if(checkbox.attr("checked") == 'checked' && thisID == 'text_enable_attr_on') { 
      jQuery('#tab-attr input[type=checkbox]:checked').each(function(){
        checked = false;
        return checked;
      });
      if (checked && !f1) { 
        alert('Please also remember to enable particular attributes in the Attributes tab!')
        f1 = true;
      };   
        }
        if (checkbox.attr("checked") == 'checked' && thisID != 'text_enable_attr_on' 
      && jQuery('#text_enable_attr_off').attr('checked')=='checked' && !f2) {
      alert('Please also remember to enable the attributes display in the Filter Behaviour tab');
      f2 = true;
        };
  }
function delay(checkbox) {
        
          if(checkbox.attr("checked") == 'checked') { 
            var thisID = checkbox.attr('id');
           // alert(thisID); 
          }
          if (thisID == 'auto_on_change') {
            jQuery('#fix').attr('disabled', 'disabled');
            jQuery('#float').attr('disabled', 'disabled');
            jQuery('#float').removeAttr("checked")
            jQuery('#fix').removeAttr("checked")
            jQuery('#delay_time').attr('readonly', 'readonly');
             //alert(thisID);
          } else if (thisID == 'delay_change') {
            jQuery('#fix').attr('disabled', 'disabled');
            jQuery('#float').attr('disabled', 'disabled');
            jQuery('#delay_time').removeAttr("readonly");
            jQuery('#float').removeAttr("checked")
            jQuery('#fix').removeAttr("checked")
           
          } else if (thisID == 'button_change') {
            jQuery('#fix').attr('checked', 'checked');
            jQuery('#fix').removeAttr("disabled");
            jQuery('#float').removeAttr("disabled");
            jQuery('#delay_time').attr('readonly', 'readonly');
          }else if (thisID == 'sliding_filter_off') {
            jQuery('#sliding_opts').attr('readonly', 'readonly');
            jQuery('#sliding_min').attr('readonly', 'readonly');
          }else if (thisID == 'sliding_filter_on') {
            jQuery('#sliding_opts').removeAttr("readonly");
            jQuery('#sliding_min').removeAttr("readonly");
            jQuery('#limit_height_on').removeAttr("checked");
            jQuery('#limit_height_off').attr('checked', 'checked');
            jQuery('#limit_height_opts').attr('readonly', 'readonly');
          }else if (thisID == 'limit_height_on') {
            jQuery('#sliding_opts').attr('readonly', 'readonly');
            jQuery('#sliding_min').attr('readonly', 'readonly');
            jQuery('#sliding_filter_on').removeAttr("checked");
            jQuery('#sliding_filter_off').attr('checked','checked');
            jQuery('#limit_height_opts').removeAttr("readonly")
          }else if (thisID == 'limit_height_off') {
            jQuery('#limit_height_opts').attr('readonly', 'readonly');
          }
  }

function applyAction() {
  // set action to "apply" to prevent redirect after submit
  jQuery('[name=action]').val('apply');
  // save current tab
  jQuery('#bf-adm-cur-tab').val(jQuery('#tabs .selected').index('#tabs a'));
  jQuery('#form').submit();
}

jQuery('.htabs a').tabs();

jQuery('#tabs a').eq(jQuery('#bf-adm-cur-tab').val()).trigger('click');

jQuery(document).ready(function(){
  setTimeout(function(){ jQuery('.success').fadeOut(600); }, 5000);
});
//--></script> 
<?php echo $footer; ?>