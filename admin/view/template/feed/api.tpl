<?php echo $header; ?>
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
      <h1><img src="view/image/feed.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-image"><?php echo $tab_image; ?></a></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab-general">
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="api_status">
                <?php if ($api_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_plataform_version; ?></td>
            <td><select name="api_plataform_version">
                <?php foreach ($plataforms as $plataform) { ?>
                <?php if ($api_plataform_version == $plataform['code']) { ?>
                <option value="<?php echo $plataform['code']; ?>" selected="selected"><?php echo $plataform['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $plataform['code']; ?>"><?php echo $plataform['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_encoding; ?></td>
            <td><select name="api_encoding">
                <?php foreach ($encodings as $encoding) { ?>
                <?php if ($api_encoding == $encoding['code']) { ?>
                <option value="<?php echo $encoding['code']; ?>" selected="selected"><?php echo $encoding['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $encoding['code']; ?>"><?php echo $encoding['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_image; ?></td>
            <td><div class="image"><img src="<?php echo $preview; ?>" alt="" id="preview" />
                <input type="hidden" name="api_store_logo" value="<?php echo $api_store_logo; ?>" id="image" />
                <br /><a onclick="image_upload('image', 'preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#preview').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
          </tr>
          <tr>
            <td><?php echo $entry_location; ?></td>
            <td><input type="text" name="api_location" value="<?php echo $api_location; ?>" size="50" /></td>
          </tr>
          <tr>
            <td style="vertical-align: middle;"><img style="vertical-align: middle;" src="http://www.opencartandroid.com/image/xtlogo-octapi.png" alt="" title="" />&nbsp;&nbsp;<?php echo $text_help ;?></td>
            <td><a href="http://www.opencartandroid.com/" target="_blank">Android for OpenCart</a></td>
          </tr>
        </table>
      </div
      <div id="tab-image">
        <table id="images" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_banner; ?></td>
              <td class="right"><?php echo $entry_group; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $image_row = 0; ?>
          <?php foreach ($api_images as $api_image) { ?>
          <tbody id="image-row<?php echo $image_row; ?>">
            <tr>
              <td class="left"><div class="image"><img src="<?php echo $api_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                  <input type="hidden" name="api_images[<?php echo $image_row; ?>][image]" value="<?php echo $api_image['image']; ?>" id="image<?php echo $image_row; ?>" />
                  <br />
                  <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
              <td class="right"><input type="text" name="api_images[<?php echo $image_row; ?>][group]" value="<?php echo $api_image['group']; ?>" size="16" /></td>
              <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $image_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addImage();" class="button"><?php echo $button_add_image; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function image_upload(field, thumb) {
  $('#dialog').remove();
  
  $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
  
  $('#dialog').dialog({
    title: '<?php echo $text_image_manager; ?>',
    close: function (event, ui) {
      if ($('#' + field).attr('value')) {
        $.ajax({
          url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
          dataType: 'text',
          success: function(text) {
            $('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
          }
        });
      }
    },  
    bgiframe: false,
    width: 800,
    height: 400,
    resizable: false,
    modal: false
  });
};
//--></script>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
  html += '  <tr>';
  html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="api_images[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
  html += '    <td class="right"><input type="text" name="api_images[' + image_row + '][group]" value="" size="16" /></td>';
  html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
  html += '  </tr>';
  html += '</tbody>';
  
  $('#images tfoot').before(html);
  
  image_row++;
}
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
$('#vtab-option a').tabs();
//--></script> 
<?php echo $footer; ?>