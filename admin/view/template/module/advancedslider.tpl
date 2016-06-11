<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
  <script type="text/javascript" src="view/javascript/jquery/jquery.upload-1.0.2.js"></script>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
	        <tr>
          <td><?php echo $entry_position; ?></td>
          <td><select name="advancedslider_position">
	      <?php if ($advancedslider_position == 'home') { ?>
              <option value="home" selected="selected"><?php echo $text_home; ?></option>
              <?php } else { ?>
              <option value="home"><?php echo $text_home; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="advancedslider_status">
              <?php if ($advancedslider_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="advancedslider_sort_order" value="<?php echo $advancedslider_sort_order; ?>" size="1" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_slide_size; ?></td>
          <td><input type="text" name="advancedslider_slide_size" value="<?php echo $advancedslider_slide_size; ?>" size="4" /></td>
        </tr>
		 <tr>
          <td><?php echo $entry_slide_height; ?></td>
          <td><input type="text" name="advancedslider_slide_height" value="<?php echo $advancedslider_slide_height; ?>" size="4" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_slide_duration; ?></td>
          <td><input type="text" name="advancedslider_slide_duration" value="<?php echo $advancedslider_slide_duration; ?>" size="5" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_slide_velocity; ?></td>
          <td><input type="text" name="advancedslider_slide_velocity" value="<?php echo $advancedslider_slide_velocity; ?>" size="5" /></td>
        </tr>
		        <tr>
          <td><?php echo $entry_headline; ?></td>
          <td><select name="advancedslider_headline">
              <?php if ($advancedslider_headline) { ?>
              <option value="1" selected="selected"><?php echo $text_headline_on; ?></option>
              <option value="0"><?php echo $text_headline_off; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_headline_on; ?></option>
              <option value="0" selected="selected"><?php echo $text_headline_off; ?></option>
              <?php } ?>
            </select></td>
        </tr>
		          <td><?php echo $text_slide_type; ?></td>
          <td><select name="advancedslider_slide_type">
		  <option value="1">Type 1</option>
		  <option value="2">Type 2</option>
		  <option value="3">Type 3</option>
		  <option value="4">Type 4</option>
		  <option value="5">Type 5</option>
		  <option value="6">Type 6</option>
		  <option value="7">Nivo normal</option>
		  <option value="8">Nivo numbers</option>
		  <option value="9">Nivo thumbs</option>
		  
		  </select></td>
		  <td><img id="sliderpreview" src="view/image/advancedslider/type_<?php echo $advancedslider_slide_type; ?>.jpg"/></td>
        </tr>
      </table>
    </form>
  </div>
<script type="text/javascript">
$('select[name="advancedslider_slide_type"] option[value="<?php echo $advancedslider_slide_type; ?>"]').attr("selected", "selected");
$('select[name="advancedslider_slide_type"]').change(function(){

slidertype=$(this).val();
$("img#sliderpreview").attr('src', 'view/image/advancedslider/type_'+slidertype+'.jpg')


})
</script>

  <!-- pos-->
  <div id="advanced_slider_ayarlar"><fieldset><legend><?php echo $text_ccpos_configuration?></legend>		
		<style type="text/css">
		.advancedslider_hesap {height:auto;width:45%;border: 1px solid silver;float:left;padding:5px;margin:3px;}
		#advanced_slider_ayarlar button {border:1px solid silver;padding:0px;margin-top:5px;}
		#advanced_slider_ayarlar button.sil {float:right;}
		#advanced_slider_ayarlar table {width:100%;}
		#advanced_slider_ayarlar .url ,#advanced_slider_ayarlar .url_3d {width:100%;}
		#advanced_slider_loading {display:none;font-size:150%;}
		#advanced_slider_buttons {margin-bottom:10px;}
		</style>
		<script type="text/javascript">
		eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(4($){6 m={\'\\b\':\'\\\\b\',\'\\t\':\'\\\\t\',\'\\n\':\'\\\\n\',\'\\f\':\'\\\\f\',\'\\r\':\'\\\\r\',\'"\':\'\\\\"\',\'\\\\\':\'\\\\\\\\\'},s={\'j\':4(x){6 a=[\'[\'],b,f,i,l=x.5,v;k(i=0;i<l;i+=1){v=x[i];f=s[7 v];2(f){v=f(v);2(7 v==\'8\'){2(b){a[a.5]=\',\'}a[a.5]=v;b=o}}}a[a.5]=\']\';3 a.p(\'\')},\'E\':4(x){3 q(x)},\'d\':4(x){3"d"},\'w\':4(x){3 F(x)?q(x):\'d\'},\'G\':4(x){2(x){2(x H I){3 s.j(x)}6 a=[\'{\'],b,f,i,v;k(i J x){v=x[i];f=s[7 v];2(f){v=f(v);2(7 v==\'8\'){2(b){a[a.5]=\',\'}a.K(s.8(i),\':\',v);b=o}}}a[a.5]=\'}\';3 a.p(\'\')}3\'d\'},\'8\':4(x){2(/["\\\\\\y-\\z]/.A(x)){x=x.L(/([\\y-\\z\\\\"])/g,4(a,b){6 c=m[b];2(c){3 c}c=b.M();3\'\\\\N\'+O.P(c/e).B(e)+(c%e).B(e)})}3\'"\'+x+\'"\'}};$.Q=4(v){6 f=R(v)?s[7 v]:s[\'w\'];2(f)3 f(v)};$.h=4(v,a){2(a===C)a=$.h.D;2(a&&!/^("(\\\\.|[^"\\\\\\n\\r])*?"|[,:{}\\[\\]0-9.\\-+S-u \\n\\r\\t])+?$/.A(v))3 C;3 T(\'(\'+v+\')\')};$.h.D=U})(V);',58,58,'||if|return|function|length|var|typeof|string|||||null|16|||parseJSON||array|for||||true|join|String||||||number||x00|x1f|test|toString|undefined|safe|boolean|isFinite|object|instanceof|Array|in|push|replace|charCodeAt|u00|Math|floor|toJSON|isNaN|Eaeflnr|eval|false|jQuery'.split('|'),0,{}));
		
		var advancedslider_data = [];
		
		function advanced_slider_hesaplar_hizala(){
			var max_h = 0;
			var max_w = 0;
			$('#advanced_slider_2_hesaplar_giris .advancedslider_hesap').each(function(){
				var h = $(this).height();				
				if(max_h<h) max_h=h;
				var w = $(this).width();				
				if(max_w<w) max_w=w;
			});
			$('#advanced_slider_2_hesaplar_giris .advancedslider_hesap').css('min-height',max_h+'px').css('min-width',max_w+'px');
		}
		function advanced_slider_hesaplar_ekle(){
		number_of_slide = $('.advancedslider_hesap').size()+1;
		
			$('#advanced_slider_2_hesaplar_giris').append(
				'<div class="advancedslider_hesap"><table>'+				
					'<tr><td></td><td><button class="sil">X</button></td></tr>'+					
					'<tr><td><?php echo $entry_aktif;?></td><td><input type="checkbox" class="aktif" checked="checked"></td></tr>'+
					'<tr><td><?php echo $entry_sira;?></td><td><input class="sira" size="2" value="'+number_of_slide+'"></td></tr>'+
					'<tr><td><?php echo $entry_img;?></td><td><input class="img" size="80" ></td></tr>'+
					'<tr><td>Resim Yükle:</td><td><form action="" method="POST" enctype="multipart/form-data" id="formum_'+number_of_slide+'" name="formum_'+number_of_slide+'"><input type="hidden" name="action" value="upload"><input type="hidden" name="actionsira" id="actionsira" value="'+number_of_slide+'"><input type="file" name="uploadimage_'+number_of_slide+'" id="uploadimage_'+number_of_slide+'"></form><br /></td></tr>'+
					'<tr><td><?php echo $entry_h3link;?></td><td><input class="h3link" size="80" ></td></tr>'+
					'<tr><td><?php echo $entry_span_i;?></td><td><input class="span_i" size="80" ></td></tr>'+
					'<tr><td><?php echo $entry_h2;?></td><td><input class="h2" size="80" ></td></tr>'+
					'<tr><td><?php echo $entry_p;?></td><td><input class="paragraf" size="80" ></td></tr>'+
					'<tr><td><?php echo $entry_thumb;?></td><td><input class="thumb" size="80" ></td></tr>'+
					'<tr><td><?php echo $entry_urls;?></td><td><input class="urls" size="80" ></td></tr>'+
				'</table></div>'
			);
			advanced_slider_hesaplar_hizala();
			advanced_slider_hesaplar_kaydet();
		}
		function advanced_slider_hesaplar_yenile(){
			$('#advanced_slider_loading').show();
			$('#advanced_slider_buttons button').attr('disabled','disabled');			
			$('#advanced_slider_2_hesaplar_giris').html('');
			$.post("<?php echo $action_yenile;?>", {}, function(json, textStatus, XMLHttpRequest){
				if(json.result && json.html!=undefined){
					$('#advanced_slider_2_hesaplar_giris').html(json.html);
				}
				if(json.alert!=undefined){
					alert(json.alert);
				}				
				$('#advanced_slider_buttons button').removeAttr('disabled');
				advanced_slider_hesaplar_hizala();
				$('#advanced_slider_loading').hide();
			},"json");			
		}		
		function advanced_slider_hesaplar_kaydet(){

			advancedslider_data = [];
			$('#advanced_slider_buttons button').attr('disabled','disabled');
			$('#advanced_slider_loading').show();
			
			$('#advanced_slider_2_hesaplar_giris .advancedslider_hesap').each(function(){
				var $table = $(this).find('table');
				var data = {
					"firma_isim":$table.find('.firma_isim').val(),
					"aktif":$table.find('.aktif').is(':checked'),
					"sira":$table.find('.sira').val(),
					"img":$table.find('.img').val(),
					"h3link":$table.find('.h3link').val(),
					"span_i":$table.find('.span_i').val(),
					"h2":$table.find('.h2').val(),
					"paragraf":$table.find('.paragraf').val(),
					"thumb":$table.find('.thumb').val(),
					"urls":$table.find('.urls').val(),
				
				};
	
				advancedslider_data[advancedslider_data.length] = data;
			});
			
			$.post( "<?php echo $action_kaydet;?>", {"advancedslider_data":$.toJSON(advancedslider_data)},function(json, textStatus, XMLHttpRequest){
				$('#advanced_slider_loading').hide();
				$('#advanced_slider_buttons button').removeAttr('disabled');
				if(json.result && json.html!=undefined){
					$('#advanced_slider_2_hesaplar_giris').html(json.html);
				}
				alert(json.alert);
				advanced_slider_hesaplar_yenile();
			}, "json" );
		}
						
		$(function(){			
			$('#advanced_slider_hesaplar_yenile').click(advanced_slider_hesaplar_yenile);
			$('#advanced_slider_hesaplar_kaydet').click(advanced_slider_hesaplar_kaydet);
			$('#advanced_slider_hesaplar_ekle').click(advanced_slider_hesaplar_ekle);
			
			$('#advanced_slider_2_hesaplar_giris .advancedslider_hesap .sil').live('click',function(){
				$(this).parents('.advancedslider_hesap').remove();
				advanced_slider_hesaplar_hizala();
			});
			
		advanced_slider_hesaplar_yenile();
		});
		</script>
		<div id="advanced_slider_loading"><?php echo $text_loading;?></div>
		<div id="advanced_slider_buttons">
			<button id="advanced_slider_hesaplar_yenile"><?php echo $button_yenile;?></button>			
			<button id="advanced_slider_hesaplar_kaydet"><?php echo $button_kaydet;?></button>
			<br>
			<button id="advanced_slider_hesaplar_ekle"><?php echo $button_advancedslider_hesap_ekle;?></button>
		</div>
		<div style="font-size:110%;margin:10px auto;"><?php echo $text_info;?></div>
		<div id="advanced_slider_2_hesaplar_giris"></div>		
	</fieldset>
	</div>
  <!--pos-->
  
  
  
	<div style="text-align:center; color:#666666;"> 
		Advanced Slider v<?php echo $advancedslider_version; ?> 
	</div>
</div>
<?php echo $footer; ?>
