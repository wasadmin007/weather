<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_limit; ?></td>
          <td><input type="text" name="imagelinks_limit" value="<?php echo $imagelinks_limit; ?>" size="1" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_position; ?></td>
          <td><select name="imagelinks_position">
              <?php foreach ($positions as $position) { ?>
              <?php if ($imagelinks_position == $position['position']) { ?>
              <option value="<?php echo $position['position']; ?>" selected="selected"><?php echo $position['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="imagelinks_status">
              <?php if ($imagelinks_status) { ?>
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
          <td><input type="text" name="imagelinks_sort_order" value="<?php echo $imagelinks_sort_order; ?>" size="1" /></td>
        </tr>
        <tr>
        <td colspan="6" align="center"> <?php echo $image_instruction; ?> </td>
        </tr>
        
        <?php                                     
          for($i = 0; $i < $imagelinks_limit; $i++) { ?>
	<tr>
            <td><?php echo($entry_image.' '.($i + 1)); ?></td>
            <td valign="top"><input type="hidden" name="<?php echo ('imagelinks_image'.$i); ?>" value="<?php echo isset($imagelinks_image[$i]) ? $imagelinks_image[$i]: '' ; ?>" id="<?php echo 'image'.$i; ?>" />            
            <img src="<?php echo($imagelinks_preview[$i]); ?>" alt="" id="<?php echo 'preview'.$i; ?>" class="image" onclick="image_upload('<?php echo 'image'.$i; ?>', '<?php echo 'preview'.$i; ?>');" /></td>
            <td><?php echo($entry_url.' '.($i + 1)); ?></td>
            <td> <input type="text" size="50" name="<?php echo ('imagelinks_url'.$i); ?>" value="<?php echo (isset($imagelinks_url[$i]) ? $imagelinks_url[$i]: '') ; ?>" id="<?php echo 'url'.$i; ?>" /> </td>
            <td><?php echo($entry_alt.' '.($i + 1)); ?></td>
            <td> <input type="text" size="50" name="<?php echo ('imagelinks_alt'.$i); ?>" value="<?php echo (isset($imagelinks_alt[$i]) ? $imagelinks_alt[$i]: '') ; ?>" id="<?php echo 'alt'.$i; ?>" /> </td>
          </tr>	
        <?php } ?>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript">
<!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<?php echo $footer; ?>
