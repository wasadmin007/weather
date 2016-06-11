<link rel="stylesheet" type="text/css" href="catalog/view/javascript/lofslidernews/css/style4.css" />

<script language="javascript" type="text/javascript" src="catalog/view/javascript/lofslidernews/js/jquery.easing.js"></script>
<script language="javascript" type="text/javascript" src="catalog/view/javascript/lofslidernews/js/script.js"></script>
<script type="text/javascript">
 $(document).ready( function(){	

		$obj = $('#lofslidecontent45').lofJSidernews( { interval : <?php echo $slide_duration;?>,
											 	easing			: 'easeInOutQuad',
												duration		: <?php echo $slide_velocity;?>,
												auto		 	: true,
												maxItemDisplay  : 3,
												startItem:0,
												navPosition     : 'horizontal', // horizontal
												navigatorHeight : 15,
												navigatorWidth  : 25,
												mainWidth:<?php echo $slide_size;?>} );	
	});
</script>
<!------------------------------------- THE CONTENT ------------------------------------------------->
<div id="lofslidecontent45" class="lof-slidecontent" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
<div class="preload"><div></div></div>
 <!-- MAIN CONTENT --> 
  <div class="lof-main-outer" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
  	<ul class="lof-main-wapper" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;">
	<?php foreach($ccpos_config as $pos){?>
  		<li>
        		<a href="<?php echo $pos['urls'];?>"><img src="<?php echo $pos['img'];?>" title="Newsflash 2" style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;"></a>  
				<?php if($slide_headline==1){?>				
                 <div class="lof-main-item-desc">
                <h3><a target="_parent" title="Newsflash 1" href="<?php echo $pos['urls'];?>"><?php echo $pos['h3link'];?></a> <i><?php echo $pos['span_i'];?></i></h3>
				<h2><?php echo $pos['h2'];?></h2>
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

       
      <div class="lof-navigator-outer">
            <ul class="lof-navigator">
			<?php foreach($ccpos_config as $pos){?>
               <li><span><?php echo $pos['sira'];?></span></li>
			   <?php } ?>
           </ul>
      </div>
        
 </div> 
  <!----------------- --------------------->
 </div> 
<script type="text/javascript">

</script>

<!------------------------------------- END OF THE CONTENT ------------------------------------------------->



