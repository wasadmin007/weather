<link rel="stylesheet" type="text/css" href="catalog/view/javascript/lofslidernews/css/style.css" />

<script language="javascript" type="text/javascript" src="catalog/view/javascript/lofslidernews/js/jquery.easing.js"></script>
<script language="javascript" type="text/javascript" src="catalog/view/javascript/lofslidernews/js/script.js"></script>
<script type="text/javascript">
 $(document).ready( function(){	
		$('#lofslidecontent45').lofJSidernews( {interval:<?php echo $slide_duration;?>,
											   direction:'opacity',
											   duration:<?php echo $slide_velocity;?>,
											   easing:'easeInOutSine'} );						
	});

</script>
<!------------------------------------- THE CONTENT ------------------------------------------------->
<div id="lofslidecontent45" class="lof-slidecontent" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
<div class="preload"><div></div></div>
 <!-- MAIN CONTENT --> 
  <div class="lof-main-outer" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
  	<ul class="lof-main-wapper">
	<?php foreach($ccpos_config as $pos){?>
  		<li>
        		<a href="<?php echo $pos['urls'];?>"><img src="<?php echo $pos['img'];?>" title="Newsflash 2" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;"></a>
				<?php if($slide_headline==1){?>				
                 <div class="lof-main-item-desc" style="top:10px; left:0; width:200px">
                <h3><a target="_parent" title="Newsflash 1" href="<?php echo $pos['urls'];?>"><?php echo $pos['h3link'];?></a></h3>
				<p><?php echo $pos['paragraf'];?>
                <a class="readmore" href="<?php echo $pos['urls'];?>">Devamı için tıklayın </a>
                </p>
             </div>
			 <?php } ?>
        </li> 
		<?php } ?>
       
      </ul>  	
  </div>
  <!-- END MAIN CONTENT --> 
    <!-- NAVIGATOR -->
<div class="lof-navigator-wapper">

        <div onclick="return false" href="" class="lof-next">Next</div>
      <div class="lof-navigator-outer">
            <ul class="lof-navigator">
			<?php foreach($ccpos_config as $pos){?>
               <li><div><img src="<?php echo $pos['thumb'];?>" /><h3><?php echo $pos['h2'];?></h3><span><?php echo $pos['span_i'];?></span></div></li>
			   <?php } ?>
           </ul>
      </div>
        <div onclick="return false" href="" class="lof-previous">Previous</div>
 </div> 
  <!----------------- --------------------->
 </div> 
<script type="text/javascript">

</script>

<!------------------------------------- END OF THE CONTENT ------------------------------------------------->



