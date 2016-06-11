
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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="form">

        <tr>

          <td width="500px;"><?php echo $entry_enable; ?></td>

          <td><select name="maintenance_module[0][status]">

              <option value="1" <?php if(isset($modules[0]['status']) && $modules[0]['status'] == 1){?> selected="selected" <?php } ?>>Enabled</option>

              <option value="0" <?php if(isset($modules[0]['status']) && $modules[0]['status'] == 0){?> selected="selected" <?php } ?>>Disabled</option>

            </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_show_logo; ?></td>

          <td><select name="maintenance_module[0][logo]">

              <option value="1" <?php if(isset($modules[0]['logo']) && $modules[0]['logo'] == 1){?> selected="selected" <?php } ?>>Enabled</option>

              <option value="0" <?php if(isset($modules[0]['logo']) && $modules[0]['logo'] == 0){?> selected="selected" <?php } ?>>Disabled</option>

            </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_admin_viewable; ?></td>

          <td><select name="maintenance_module[0][admin]">

              <option value="1" <?php if(isset($modules[0]['admin']) && $modules[0]['admin'] == 1){?> selected="selected" <?php } ?>>Enabled</option>

              <option value="0" <?php if(isset($modules[0]['admin']) && $modules[0]['admin'] == 0){?> selected="selected" <?php } ?>>Disabled</option>

            </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_message; ?></td>

          <td><textarea id="message" name="maintenance_module[0][message]"><?php if(isset($modules[0]['message'])){ echo $modules[0]['message'];}  ?></textarea></td>

        </tr>

      </table>
		<input type="hidden" name="maintenance_module[0][position]" value="content_top" />
		<input type="hidden" name="maintenance_module[0][layout_id]" value="0" />
		<input type="hidden" name="maintenance_module[0][sort_order]" value="0" />
    </form>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
CKEDITOR.replace('message', {
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $_SESSION['token']; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $_SESSION['token']; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_SESSION['token']; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_SESSION['token']; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_SESSION['token']; ?>>'
});
//--></script>
</div>
</div>
</div>


<?php echo $footer; ?>