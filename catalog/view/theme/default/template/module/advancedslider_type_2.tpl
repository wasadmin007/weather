<link rel="stylesheet" type="text/css" href="catalog/view/javascript/lofslidernews/css/style.css" />

<script language="javascript" type="text/javascript" src="catalog/view/javascript/lofslidernews/js/jquery.easing.js"></script>
<script language="javascript" type="text/javascript" src="catalog/view/javascript/lofslidernews/js/script.js"></script>
<script type="text/javascript">
 $(document).ready( function(){	
		$('#lofslidecontent45').lofJSidernews( { interval:<?php echo $slide_duration;?>,
											 	easing:'easeInOutQuad',
												duration:<?php echo $slide_velocity;?>,
												mainWidth		: <?php echo $slide_size;?>,
												auto:true } );						
	});

</script>
<style>
	
	ul.lof-main-wapper li {
		position:relative;	
	}
</style>
<!------------------------------------- THE CONTENT ------------------------------------------------->
<div id="lofslidecontent45" class="lof-slidecontent" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
<div class="preload"><div></div></div>
 <!-- MAIN CONTENT --> 
  <div class="lof-main-outer" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
  	<ul class="lof-main-wapper" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
	<?php foreach($ccpos_config as $pos){?>
  		<li style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
        		<a href="<?php echo $pos['urls'];?>"><img src="<?php echo $pos['img']?>" title="Newsflash 2" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;"></a>  
					<?php if($slide_headline==1){?>
                 <div class="lof-main-item-desc" style="left:0; top:200; width:350px">
                <h3><a target="_parent" title="Newsflash 1" href="<?php echo $pos['urls'];?>"><?php echo $pos['h3link'];?></a></h3>
				<p><?php echo $pos['paragraf'];?></p>
             </div>
			 <?php } ?>
        </li> 
		<?php } ?>
       
      </ul>  	
  </div>
  <!-- END MAIN CONTENT --> 
    <!-- NAVIGATOR -->

      <div class="lof-navigator-outer" style="right:-100px;">
            <ul class="lof-navigator">
			<?php foreach($ccpos_config as $pos){?>
               <li>
			   <div style="width:180px;">

			   <img src="<?php echo $pos['thumb'];?>" />
			   
			   <span><?php echo $pos['span_i'];?><?php echo $pos['h2'];?></span>

			   </div>
			   </li>
			   <?php } ?>
           </ul>
      </div>

 
  <!----------------- --------------------->
 </div> 
<script type="text/javascript">

</script>

