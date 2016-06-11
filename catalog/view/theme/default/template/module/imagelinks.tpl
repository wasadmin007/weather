<div class="box">
  <div class="top"><img src="catalog/view/theme/default/image/imagelinks.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle">
    <?php if (isset($url)) { ?>
    <table cellpadding="2" cellspacing="0" style="width: 100%;">
    
      <?php for($i = 0; isset($image[$i]); $i++) { ?>
      <tr>
        <td valign="top" style="width:1px">
        <a href="<?php echo str_replace('&', '&amp;', $url[$i]); ?>" target="blanc">
          <img src="<?php echo($image[$i]); ?>" alt="<?php echo $alt[$i]; ?>" />
        
        </a> <hr /></td>                 
      </tr>
      <?php } ?>
    </table>
    <?php } ?>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
