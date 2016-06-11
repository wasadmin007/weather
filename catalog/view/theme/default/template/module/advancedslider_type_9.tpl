<link rel="stylesheet" type="text/css" href="catalog/view/javascript/nivo-slider/nivo-slider.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/nivo-slider/style.css" />

<script language="javascript" type="text/javascript" src="catalog/view/javascript/nivo-slider/jquery.nivo.slider.pack.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider3').nivoSlider({
			controlNavThumbs:true,
			controlNavThumbsFromRel:true,
			animSpeed:<?php echo $slide_velocity;?>, //Slide transition speed
			pauseTime:<?php echo $slide_duration;?>
		});
    });
    </script>
<!------------------------------------- THE CONTENT ------------------------------------------------->
    <div id="wrapper" style="width:<?php echo $slide_size?>px; height:<?php echo $slide_height;?>px; margin-bottom:70px">
    
         <div id="slider-wrapper" class="slider-wrapper" style="width:<?php echo $slide_size?>px; height:<?php echo $slide_height;?>px;">
        
            <div id="slider3" class="nivoSlider" style="width:<?php echo $slide_size?>px; height:<?php echo $slide_height;?>px;">
			
	<?php foreach($ccpos_config as $pos){?>
	
	<a href="<?php echo $pos['urls'];?>"><img src="<?php echo $pos['img']?>" alt=""  rel="<?php echo $pos['thumb']?>"<?php if($slide_headline==1){?> title="#htmlcaption_<?php echo $pos['sira'];?>"<?php } ?> style="width:<?php echo $slide_size;?>px; height:<?php echo $slide_height;?>px;"/></a>
 
		<?php } ?>
		 </div>
		 <?php if($slide_headline==1){?>
		 <?php foreach($ccpos_config as $pos){ ?>
                <div id="htmlcaption_<?php echo $pos['sira'];?>" class="nivo-html-caption" style="width:<?php echo $slide_size;?>px;">
                <h3><a target="_parent" title="Newsflash 1" href="<?php echo $pos['urls'];?>"><?php echo $pos['h3link'];?></a> <i><?php echo $pos['span_i'];?></i></h3>
				<h2><?php echo $pos['h2'];?></h2>
                <p><?php echo $pos['paragraf'];?>
                <a class="readmore" href="<?php echo $pos['urls'];?>">Devamı için tıklayın </a>
                </p>
				</div>
           <?php } } ?>
        
        

    </div>
 
    </div>

<!------------------------------------- END OF THE CONTENT -----------------------------------------------
 -->
