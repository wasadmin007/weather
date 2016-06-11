<?php
if($popup_mode): ?>
 <a id="ecsocialcoupon_popup<?php echo $module;?>" href="#ecsocialcoupon<?php echo $module;?>" style="display:none;">ecsocialcoupon</a>
 <div style="display:none;">
<?php endif; ?>
  <div id="ecsocialcoupon<?php echo $module;?>" class="ecsocialcoupon" style="width:<?php echo $module_width;?>;height:<?php echo $module_height;?>">

    <div class="notice_message" style="display:none;">
      <?php echo $text_notice;?>
      <?php if($show_coupon_code) { ?>
      <p><?php echo $text_your_coupon;?><span class="coupon_code"></span></p>
      <?php } ?>
    </div>

    <?php if(isset($enable_facebook_share) && $enable_facebook_share):?>
    
      <div id="fb-root"></div>
      <script type="text/javascript">
        window.fbAsyncInit = function() {
          // init the FB JS SDK
          FB.init({
            appId      : '<?php echo $facebook_app_id; ?>',                        // App ID from the app dashboard
            channelUrl : '<?php echo $base; ?>', // Channel file for x-domain comms
            status     : true,                                 // Check Facebook Login status
            xfbml      : true                                  // Look for social plugins on the page
          });
      
          // Additional initialization code such as adding Event Listeners goes here
        };
      
      </script>
    <?php endif; ?>

    <?php if($enable_facebook):?>
    <script type="text/javascript">FB.XFBML.parse();</script>
    <?php endif; ?>
    <script type="text/javascript">
    function showCoupon(coupon){
            if($("input[name='coupon']")){
                $("input[name='coupon']").val("");
            }
    }

        var ecoptions = { 
                checkout_url : "<?php echo $checkout_url; ?>",
                get_coupon_url: "<?php echo $get_coupon_url;?>",
                public_key : "<?php echo $public_key;?>",
                token: "<?php echo $token;?>",
                my_id  : '12345843042989',
                show_coupon_code: <?php echo $show_coupon_code?'true':'false'; ?>,
                have_cart_items: <?php echo $have_cart_items?'true':'false'; ?>,
                applied_coupon: <?php echo $applied_coupon?'true':'false'; ?>,
                enable_facebook: <?php echo $enable_facebook?'true':'false';?>,
                enable_twitter: <?php echo $enable_twitter?'true':'false';?>,
                enable_facebook_share: <?php echo $enable_facebook_share?'true':'false';?>,
                enable_twitter_follow: <?php echo $enable_twitter_follow?'true':'false';?>,
                expire_date  : <?php echo $expire_date; ?>,
                class_sharing: ".fb-share-button",
                redirect: <?php echo $redirect?'true':'false';?>,
                popup_mode: <?php echo $popup_mode?'true':'false';?>,
                callback: showCoupon
            };
      function show_message(sc){
          
              $(ecoptions.class_notice_message).each(function(){
                    var text = $(this).html();
                    var coupon_code = $(this).find(".coupon_code");
                    if(coupon_code.length > 0){
                      $(coupon_code).each(function(){
                         $(this).html(sc);
                      })
                    }
                    $(this).show();
              })
              $(".ecsocialcoupon-box").hide();
        }
      var data = { action: "submit", myID: ecoptions.my_id, next:"coupon",coupon:""};
       <?php if($enable_linkedin):?>
      function virallocker_linkedin() {
                  var data = { action: "submit", myID: ecoptions.my_id, next:"coupon",coupon:""};
                   $.ajax({
                                type: "POST",
                                url: ecoptions.get_coupon_url,
                                data:{key: ecoptions.public_key, token: ecoptions.token},
                                dataType: "json",
                                success: function(msg){
                                  if(msg["coupon"]){
                                      data.coupon = msg["coupon"];
                                      $.cookie('socialcoupon', data.coupon, { expires: ecoptions.expire_date, path: '/'  });
                                      $.cookie('closed_popup', 1, { expires: ecoptions.expire_date, path: '/'  });
                                      if(ecoptions.show_coupon_code){
                                        show_message(msg["coupon"]);
                                      }
                                      $.post(ecoptions.checkout_url, data, function(response) {
                                        if(ecoptions.redirect)
                                          window.location = ecoptions.checkout_url;
                                        if(ecoptions.popup_mode){
                                            if(ecoptions.show_coupon_code){
                                              show_message(msg["coupon"]);
                                            }else{
                                              $.colorbox.close();
                                            }
                                          }
                                      });
                                  }       
                                }
                  });
          }
       <?php endif; ?>
    </script>

    <div class="ecsocialcoupon-box notify" style="display:none">
        <div class="message"><?php echo $module_message;?></div>
        <?php if($enable_twitter):?>
        <div class="eccoupon_twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-text="<?php echo $tweet_text; ?>" data-url="<?php echo $share_website;?>"  data-lang="<?php echo $iso_code; ?>" ><?php echo $tweet_text; ?></a></div>
        <?php endif; ?>
        <?php if($enable_google):?>
        <div class="eccoupon_google"><g:plusone size="medium"  callback="virallocker_plusone" href="<?php echo $share_website;?>"></g:plusone></div>
        <?php endif; ?>
        <?php if($enable_facebook):?>
        <div class="eccoupon_facebook"><fb:like id="fbLikeButton" href="<?php echo $share_website;?>" show_faces="true" width="450"></fb:like></div>
        <?php endif; ?>
        <?php if(isset($enable_facebook_share) && $enable_facebook_share):?>
        <div class="eccoupon_facebook">
          <a data-image="<?php echo $share_image; ?>" data-title="<?php echo $share_title; ?>" data-desc="<?php echo $share_message; ?>" data-url="<?php echo $share_website;?>" class="fb-share-button light" href="javascript:;" rel='nofollow' data-type='button_count'><span><?php echo $this->language->get("text_share"); ?></span></a>
          <div class="share_count" style="display:none">
            <span class="pluginCountButton pluginCountNum blueButton"><span class="pluginCountTextConnected"></span><span class="pluginCountTextDisconnected"></span></span>
            <div class="pluginCountButtonNub pluginCountBlueButtonNub"><s></s><i></i></div>
          </div>
          <br class="clr clear"/>
        </div>
        <?php endif; ?>
        <?php if(isset($enable_twitter_follow) && $enable_twitter_follow):?>
          <div class="eccoupon_twitter">
            <a href="https://twitter.com/<?php echo $twitter_account; ?>" class="twitter-follow-button" data-lang="<?php echo $iso_code; ?>"><?php echo $this->language->get("text_follow");?> @<?php echo $twitter_account; ?></a>
          </div>
        <?php endif; ?>
         <?php if(isset($enable_linkedin) && $enable_linkedin):?>
          <div class="eccoupon_linkedin">
            <script type="IN/Share" data-url="<?php echo $share_website;?>" data-onsuccess="virallocker_linkedin" data-counter="right"></script>
          </div>
        <?php endif; ?>
        <div class="clear clr"></div> 
    </div>
  </div>
  <br class="clr clear" style="clear:both"/>
<?php
if($popup_mode): ?>
 </div>
<?php endif; ?>
<script type="text/javascript">
  
  $("#ecsocialcoupon<?php echo $module;?>").ecSocialcoupon(ecoptions);

  <?php if($enable_google):?>
  function virallocker_plusone(plusone) {
    if (plusone.state == "on") {
        $.ajax({
                  type: "POST",
                  url: ecoptions.get_coupon_url,
                  data:{key: ecoptions.public_key, token: ecoptions.token},
                  dataType: "json",
                  success: function(msg){
                    if(msg["coupon"]){
                        data.coupon = msg["coupon"];
                        $.cookie('socialcoupon', data.coupon, { expires: ecoptions.expire_date, path: '/'  });
                        $.cookie('closed_popup', 1, { expires: ecoptions.expire_date, path: '/'  });
                        if(ecoptions.show_coupon_code){
                          show_message(msg["coupon"]);
                        }
                        $.post(ecoptions.checkout_url, data, function(response) {
                          if(ecoptions.redirect)
                            window.location = ecoptions.checkout_url;
                          if(ecoptions.popup_mode){
                              if(ecoptions.show_coupon_code){
                                show_message(msg["coupon"]);
                              }else{
                                $.colorbox.close();
                              }
                          }
                        });
                    }       
                  }
                });
    }
  }
  <?php endif; ?>

 <?php if($popup_mode): ?>
 $(document).ready(function(){
     var closed_popup =  $.cookie('closed_popup');
     if(typeof(closed_popup) == 'undefined' || closed_popup=="" || closed_popup == "NULL" || closed_popup == null || closed_popup == 0){
          $("#ecsocialcoupon_popup<?php echo $module;?>").colorbox({title:"<?php echo $this->language->get("text_social_coupon_heading");?>", inline:true, width:"<?php echo (isset($popup_width) && $popup_width !='auto')?$popup_width:'50%';?>",overlayClose: true, opacity: 0.5});
          $("#ecsocialcoupon_popup<?php echo $module;?>").click();
          
    }
  });
 <?php endif; ?>
</script>