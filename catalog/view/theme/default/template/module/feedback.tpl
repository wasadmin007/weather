<script src="http://jquery.malsup.com/cycle2/jquery.cycle2.js"></script>
<?php
$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "feedback where status=1");
$feedbacks=$query->rows;

?>
<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">


<div class="cycle-slideshow" 
    data-cycle-fx="scrollHorz" 
    data-cycle-timeout="4000"
    data-cycle-slides="> div"
     style="width:100%; height:100%;">
    

<?php foreach($feedbacks as $feedback){ ?>


<div style="width:100%; height:100%; padding:3px;">
        <p style="padding:5px;">
<img src="catalog/view/theme/default/image/quote1.png" />
        <br /> <?php echo html_entity_decode($feedback['feedback'], ENT_QUOTES, 'UTF-8'); ?> <br />
<img src="catalog/view/theme/default/image/quote2.png" align="right" />
	<p align="center">&nbsp;&nbsp;&nbsp;--By <?php echo $feedback['name']; ?></p>
        </p>
    </div>
<?php } ?>


</div>
<br />
<a href="index.php?route=information/feedback"><img src="catalog/view/theme/default/image/feedback.jpg" width="95%" /></a>
</div></div>