<link rel="stylesheet" type="text/css" href="catalog/view/javascript/lofslidernews/css/style.css" />

<script language="javascript" type="text/javascript" src="catalog/view/javascript/lofslidernews/js/jquery.easing.js"></script>
<script language="javascript" type="text/javascript" src="catalog/view/javascript/lofslidernews/js/script.js"></script>
<script type="text/javascript">
 $(document).ready( function(){	
		$('#lofslidecontent45').lofJSidernews( { interval: <?php echo $slide_duration;?>,
											 	easing:'easeInOutQuad',
												duration: <?php echo $slide_velocity;?>,
												mainWidth		: <?php echo $slide_size?>,
												auto:true } );						
	});

</script>
<style>
	.lof-snleft  .lof-main-outer{
		float:none;
	}
	/* move the main wapper to the right side */
	.lof-snleft .lof-main-wapper{
		margin-left:auto;
		margin-right:inherit;
		clear:both;
		height:<?php echo $slide_height;?>px;
	}
	/* move the navigator to the left  side */
	.lof-snleft .lof-navigator-outer{
		left:0;
		top:0;
		right:inherit;
		
	}
	
	ul.lof-main-wapper li {
		position:relative;	
	}
	.lof-snleft .lof-navigator .active{
		background:url(images/arrow-bg2.gif) center right no-repeat;
	}
	.lof-snleft .lof-navigator li div{
		margin-left:inherit;
		margin-right:18px;
	}
	
	.lof-snleft .lof-navigator li.active div{
		margin-left:inherit;
		margin-right:18px;
		background:url(images/grad-bg2.gif)
		
	}
</style>
<!------------------------------------- THE CONTENT ------------------------------------------------->
<div id="lofslidecontent45" class="lof-slidecontent  lof-snleft" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
<div class="preload"><div></div></div>
 <!-- MAIN CONTENT --> 
  <div class="lof-main-outer" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
  	<ul class="lof-main-wapper" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
	<?php foreach($ccpos_config as $pos){?>
  		<li style="width:<?php echo $slide_size?>px; height:<?php echo $slide_height;?>px;">
        		<a href="<?php echo $pos['urls']?>"><img src="<?php echo $pos['img'];?>" title="Newsflash 2" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;"></a>   
				<?php if($slide_headline==1){?>				
                 <div class="lof-main-item-desc" style="left:200px; top:200px; width:370px">
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

      <div class="lof-navigator-outer" style="width:200px;">
            <ul class="lof-navigator" style="width:200px;">
			<?php foreach($ccpos_config as $pos){?>
               <li style="width:200px;">
			   <div style="width:180px;">

			   <img src="<?php echo $pos['thumb'];?>" /><br/>
			   			   			   <span><?php echo $pos['h2'];?><span>
			   <span><?php echo $pos['span_i'];?></span>

			   </div>
			   </li>
			   <?php } ?>
           </ul>
      </div>

 
  <!----------------- --------------------->
 </div> 
<script type="text/javascript">

</script>

