<div id="fb-root"></div>
<!-- 
  Author: Ajai Verma(info@zxmod.com)
  It is illegal to remove this comment without prior permission from Ajai Verma(info@zxmod.com)
  Removing ads from module is illegal, please purchase the pro version which have no ads and more options.
  https://www.xtendify.com/en/product/148-facebook-like-popup-box-pro-for-opencart-plugin
-->
<script> 
(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.async = true;js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=582659418452360";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

</script>
<div id="fb_hidden_div" style="display:none;"></div>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.bpopup.min.js"></script>
<script>
var html = '<div style="background-color:#fff;width:300px;height:300px;border:1px solid #ddd" id="facebook_popup_like"><div id="fb_popup_close" style="position: absolute;right: -25px;top: -25px;z-index: 9999;cursor: pointer;"><img style="width:45px;" src="catalog/view/theme/default/image/fanclose.png" /></div>';
	html +='<div class="fb-like-box" data-href="https://www.facebook.com/jindruStores" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>';
	html +='<div style="text-align: center;font-weight: bold;"><a href="jindru.com" style="text-decoration:none;color: #38B0E3;">JINDRU.COM</a></div>'	
    html +='</div>';

$(document).ready(function() {
	$('#fb_hidden_div').html(html);
	  var delay_time = 0;
	  var new_d_time = delay_time*1000;
	setTimeout(function () {

		$('#fb_hidden_div').bPopup({
	    	easing: 'easeOutBack',
	     	speed: 450,
	        transition: 'slideDown'
	    });	
		
		 }, new_d_time);
$('#fb_popup_close').click(function(){
	$('#fb_hidden_div').bPopup().close();
});

});

</script>

<!-- 
 Author: Ajai Verma(info@zxmod.com)
 It is illegal to remove this comment without prior notice to Ajai Verma(info@zxmod.com)
-->
