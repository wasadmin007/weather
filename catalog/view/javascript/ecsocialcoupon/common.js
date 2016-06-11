;(function($){

    var SocialMagic =
    {
      Twitter: function( url, callback )
      {
        $.ajax( {
          type: 'GET',
          dataType: 'jsonp',
          url: '//cdn.api.twitter.com/1/urls/count.json',
          data:
          {
            'url': url
          },
          success: function( data )
          {
            callback( data.count );
          }
        } );
      },
      
      Facebook: function( url, callback )
      {
        $.ajax( {
          type: 'GET',
          dataType: 'json', // Facebook works without JSONP
          url: '//graph.facebook.com/',
          data:
          {
            'ids': url
          },
          success: function( data )
          {
            callback( data[ url ].shares );
          }
        } );
      }
    };

    $.fn.ecSocialcoupon = function( opts ) {
        return this.each(function() { 
                        new  $.ecSocialcoupon( this, opts ); 
                    });
    }
    $.ecSocialcoupon = function( obj, opts ) {
        var defaults = {
            checkout_url : "",
            get_coupon_url: "",
            public_key : "123",
            token: "",
            my_id  : '12345843042989',
            show_coupon_code: false,
            enable_facebook: true,
            enable_facebook_share: true,
            enable_twitter: true,
            enable_twitter_follow: true,
            applied_coupon: false,
            have_cart_items: false,
            expire_date  : 7,
            redirect: true,
            auto_apply_discount: true,
            popup_mode: true,
            class_sharing: ".fb-share-button",
            class_notice_message: ".notice_message",
            class_coupon_code: ".ecsocialcoupon-box",
            ////////callbacks
            callback            : function(attrs) {  },  //this callback is invoked when the image on a slide has completely loaded
        };
        var opts = $.extend(defaults, opts || {});
        var element = obj;
        var sc = $.cookie('socialcoupon');
        var scchecked = $.cookie('scchecked');
        var viralcurrentpageurl = opts.checkout_url;
        var data = { action: "submit", myID: opts.my_id, next:"coupon",coupon:""};


        function show_message(sc){
          
              $(opts.class_notice_message).each(function(){
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
       
        function fb_share(obj) {
            FB.ui( {
                method: 'feed',
                name: $( obj ).data( "title" ),
                link: $( obj ).data( "url" ),
                picture: $( obj ).data( "image" ),
                caption: $( obj ).data( "desc" )
            }, function( response ) {
                if ( typeof(response) !='undefined' && response !== null && typeof response.post_id !== 'undefined' ) {
                    var data = { action: "submit", myID: opts.my_id};
                                setTimeout(function() {
                                    jQuery.ajax({
                                          type: "POST",
                                          url: opts.get_coupon_url,
                                          data:{key: opts.public_key, token: opts.token},
                                          dataType: "json",
                                          success: function(msg){
                                            if(msg["coupon"]){
                                                data.coupon = msg["coupon"];
                                                $.cookie('socialcoupon', data.coupon, { expires: opts.expire_date, path: '/'  });
                                                $.cookie('closed_popup', 1, { expires: ecoptions.expire_date, path: '/'  });
                                                if(opts.show_coupon_code){
                                                  show_message(msg["coupon"]);
                                                }
                                                jQuery.post(viralcurrentpageurl, data, function(response) {
                                                    if(opts.redirect)
                                                        window.location = viralcurrentpageurl;
                                                    if(opts.popup_mode){
                                                      if(opts.show_coupon_code){
                                                        show_message(msg["coupon"]);
                                                      }else{
                                                        $.colorbox.close();
                                                      }
                                                    }
                                                });
                                            }       
                                          }
                                        });
                    }, 5000);  
                }
            } );
          
        }


        function fb_like(){
            FB.Event.subscribe("edge.create", function(href, widget) {
                                var data = { action: "submit", myID: opts.my_id};
                                setTimeout(function() {
                                    jQuery.ajax({
                                          type: "POST",
                                          url: opts.get_coupon_url,
                                          data:{key: opts.public_key, token: opts.token},
                                          dataType: "json",
                                          success: function(msg){
                                            if(msg["coupon"]){
                                                data.coupon = msg["coupon"];
                                                $.cookie('socialcoupon', data.coupon, { expires: opts.expire_date, path: '/'  });
                                                $.cookie('closed_popup', 1, { expires: ecoptions.expire_date, path: '/'  });
                                                if(opts.show_coupon_code){
                                                  show_message(msg["coupon"]);
                                                }
                                                jQuery.post(viralcurrentpageurl, data, function(response) {
                                                    if(opts.redirect)
                                                        window.location = viralcurrentpageurl;
                                                    if(opts.popup_mode){
                                                      if(opts.show_coupon_code){
                                                        show_message(msg["coupon"]);
                                                      }else{
                                                        $.colorbox.close();
                                                      }
                                                    }
                                                        
                                                });
                                            }       
                                          }
                                        });
                                }, 5000);   
                                
            });
        }

        function twitter(){
            twttr.ready(function (twttr) {
                twttr.events.bind("tweet", function(event) { 
                    var data = { action: "submit", myID: opts.my_id, next:"coupon",coupon:""};
                    jQuery.ajax({
                          type: "POST",
                          url: opts.get_coupon_url,
                          data:{key: opts.public_key, token: opts.token},
                          dataType: "json",
                          success: function(msg){
                            if(msg["coupon"]){
                                data.coupon = msg["coupon"];
                                $.cookie('socialcoupon', data.coupon, { expires: opts.expire_date, path: '/'  });
                                $.cookie('closed_popup', 1, { expires: ecoptions.expire_date, path: '/'  });
                                if(opts.show_coupon_code){
                                  show_message(msg["coupon"]);
                                }
                                jQuery.post(viralcurrentpageurl, data, function(response) {
                                    if(opts.redirect)
                                        window.location = viralcurrentpageurl;
                                    if(opts.popup_mode){
                                      if(opts.show_coupon_code){
                                        show_message(msg["coupon"]);
                                      }else{
                                        $.colorbox.close();
                                      }
                                    }
                                });
                            }       
                          }
                    });
                });
            });
        }
        function twitter_follow(){
            twttr.ready(function (twttr) {
                twttr.events.bind('follow', function(event) {
                    var data = { action: "submit", myID: opts.my_id, next:"coupon",coupon:""};
                    jQuery.ajax({
                          type: "POST",
                          url: opts.get_coupon_url,
                          data:{key: opts.public_key, token: opts.token},
                          dataType: "json",
                          success: function(msg){
                            if(msg["coupon"]){
                                data.coupon = msg["coupon"];
                                $.cookie('socialcoupon', data.coupon, { expires: opts.expire_date, path: '/'  });
                                $.cookie('closed_popup', 1, { expires: ecoptions.expire_date, path: '/'  });
                                if(opts.show_coupon_code){
                                  show_message(msg["coupon"]);
                                }
                                jQuery.post(viralcurrentpageurl, data, function(response) {
                                    if(opts.redirect)
                                        window.location = viralcurrentpageurl;
                                    if(opts.popup_mode){
                                      if(opts.show_coupon_code){
                                        show_message(msg["coupon"]);
                                      }else{
                                        $.colorbox.close();
                                      }
                                    }
                                });
                            }       
                          }
                    });
                });
            });
        }
        

        function initSocialCoupon(){
                if(typeof(sc) == 'undefined' || sc=="" || sc == "NULL" || sc == null){
                        var virallocker_use = true;
                        var logged = 0;

                        if(opts.enable_facebook){
                            fb_like();
                        }
                        if(opts.enable_facebook_share){
                            $( opts.class_sharing ).each(function(el){
                                var fb_parent_element = $(this).parent();
                                SocialMagic.Facebook( $( this ).data( "url" ), function( count ) {
                                    if($(fb_parent_element).find(".pluginCountTextDisconnected")){
                                        $(fb_parent_element).find(".pluginCountTextDisconnected").first().text( count );
                                    }
                                    if($(fb_parent_element).find(".pluginCountTextConnected")){
                                        count = parseInt( count ) + 1;
                                        $(fb_parent_element).find(".pluginCountTextConnected").first().text( count );
                                    }
                                    $(fb_parent_element).find(".share_count").first().show();
                                } );
                            })
                            
                            $(opts.class_sharing).click(function(el){
                                fb_share(this);
                            });
                        }
                        if(opts.enable_twitter){
                            twitter();
                        }
                        if(opts.enable_twitter_follow){
                            twitter_follow();
                        }
                        $(opts.class_coupon_code).show();
                }else{

                    if(opts.auto_apply_discount && opts.have_cart_items && !opts.applied_coupon){
                            data.coupon = sc;
                            jQuery.post(viralcurrentpageurl, data, function(response) {
                              if(window.location.href.substr(-2) !== "?r" || window.location.href.substr(-2) !== "&r") {
                                  if(viralcurrentpageurl.indexOf("?") > 0) {
                                    window.location = viralcurrentpageurl + "&r";
                                  } else {
                                    window.location = viralcurrentpageurl + "?r";
                                  }
                                  
                              }
                            });
                    }
                    $(opts.class_coupon_code).hide();

                    if($("#total").length > 0) {
                      $("#total").html(
                        $("#total").html().replace(
                            sc,
                            ''
                        )
                      )
                    }
                    
                    $(opts.class_notice_message).each(function(){
                        var text = $(this).html();
                        var coupon_code = $(this).find(".coupon_code");
                        /*Show coupon code*/
                        if(opts.show_coupon_code){
                          if(coupon_code.length > 0){
                            $(coupon_code).each(function(){
                               $(this).html(sc);
                            })
                          }
                        }else{
                          text = text.replace("%s",sc);

                          $(this).html(text);
                        }
                        $(this).show();
                    })

                    
                    opts.callback(sc);
            }
        }

        $(document).ready(function(){
            initSocialCoupon();
        })
    }
})(jQuery);