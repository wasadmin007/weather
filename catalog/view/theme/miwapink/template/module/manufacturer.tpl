<style>
<!--
#module_manufacturer .middle {
	padding: 10px 1px 10px 1px;
}
.manufacturer {
	float: none;
    text-align: left;
}
-->
</style>
<div id="module_manufacturer" class="box">
  <div class="top"><img src="catalog/view/theme/default/image/globe.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle" style="text-align: center;">
          <div class="switcher manufacturer">
            <?php foreach ($manufacturers as $manufacturer) { ?>
                  <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
            <div class="selected"><a><img src="<?php echo $manufacturer['image']; ?>" alt="" width="25" height="12" />&nbsp;<?php echo $manufacturer['name']; ?></a></div>
            <?php } ?>
            <?php } ?>
            <?php if ($manufacturer_id == '0') {?>
	            <div class="selected"><a><?php echo $text_select; ?></a></div>
	           <?php } ?>
            <div class="option">
              <?php foreach ($manufacturers as $manufacturer) { ?>
              <a onclick="location = '<?php echo str_replace('&', '&amp;', $manufacturer['href']); ?>'">
              	<img src="<?php echo $manufacturer['image']; ?>" alt="" width="25" height="12"/>&nbsp;&nbsp;<?php echo $manufacturer['name']; ?></a>
              <?php } ?>
            </div>
          </div>
  </div>
  <div class="bottom">&nbsp;</div>
</div>

<script type="text/javascript"><!--
$('.manufacturer').bind('click', function() {
	$(this).find('.option').slideToggle('fast');
});
$('.manufacturer').bind('mouseleave', function() {
	$(this).find('.option').slideUp('fast');
}); 
//--></script>