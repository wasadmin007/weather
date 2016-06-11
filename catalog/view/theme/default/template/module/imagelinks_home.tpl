<?php if ($image) { ?>
<div class="top">
  <div class="left"></div>
  <div class="right"></div>
  <div class="center">
    <div class="heading"><?php echo($heading_title); ?></div>
  </div>
</div>

<div class="middle">
  <table class="list">
   <?php for($i = 0; isset($image[$i]); $i++) { ?>
      <tr>
        <td valign="top" style="width:1px">
        <a href="<?php echo str_replace('&', '&amp;', $url[$i]); ?>" target="blanc">
          <img src="<?php echo($image[$i]); ?>" alt="<?php echo $alt[$i]; ?>" />
        
        </a> <hr /></td>                 
      </tr>
      <?php } ?>
  </table>
</div>

<div class="bottom">
  <div class="left"></div>
  <div class="right"></div>
  <div class="center"></div>
</div>
<?php } ?>