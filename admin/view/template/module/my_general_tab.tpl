<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div><!-- breadcrumb -->
		
	<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>	
	
	<div class="box">
		<div class="heading">
		  <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
		  <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
		</div><!-- end of .heading -->

		<div class = "content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class = "form">
				<tr>
					<td colspan="2"><?php echo $description; ?></td>
				</tr>
				<tr>
					<td><?php echo $entry_quantity; ?><br /><span class = "help"><?php // echo $entry_quantity_description; ?></span></td>
					<td>
					<?php echo $yes; ?>&nbsp;<input type="radio" name="mygt_use[0]" value="y" <?php echo $mygt_use[0] == 'y' ? 'checked="checked"' : ''; ?> />
					<?php echo $no; ?>&nbsp;<input type="radio" name="mygt_use[0]" value="n" <?php echo $mygt_use[0] == 'n' ? 'checked="checked"' : ''; ?> />
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_manufacturer; ?><br /><span class = "help"><?php // echo $entry_manufacturer_description; ?></span></td>
					<td>
					<?php echo $yes; ?>&nbsp;<input type="radio" name="mygt_use[1]" value="y" <?php echo $mygt_use[1] == 'y' ? 'checked="checked"' : ''; ?> />
					<?php echo $no; ?>&nbsp;<input type="radio" name="mygt_use[1]" value="n" <?php echo $mygt_use[1] == 'n' ? 'checked="checked"' : ''; ?> />
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_categories; ?><br /><span class = "help"><?php // echo $entry_categories_description; ?></span></td>
					<td>
					<?php echo $yes; ?>&nbsp;<input type="radio" name="mygt_use[2]" value="y" <?php echo $mygt_use[2] == 'y' ? 'checked="checked"' : ''; ?> />
					<?php echo $no; ?>&nbsp;<input type="radio" name="mygt_use[2]" value="n" <?php echo $mygt_use[2] == 'n' ? 'checked="checked"' : ''; ?> />
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_downloads; ?><br /><span class = "help"><?php // echo $entry_downloads_description; ?></span></td>
					<td>
					<?php echo $yes; ?>&nbsp;<input type="radio" name="mygt_use[3]" value="y" <?php echo $mygt_use[3] == 'y' ? 'checked="checked"' : ''; ?> />
					<?php echo $no; ?>&nbsp;<input type="radio" name="mygt_use[3]" value="n" <?php echo $mygt_use[3] == 'n' ? 'checked="checked"' : ''; ?> />
					</td>
				</tr>
			</table>
			</form>
		</div> <!-- end of .content -->
		
	</div><!-- end of .box -->
<div align="center">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - make a donation!">
<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>
</div>

</div> <!-- end of #content -->
