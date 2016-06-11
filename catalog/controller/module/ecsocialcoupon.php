<?php
class ControllerModuleEcsocialcoupon extends Controller {
	protected function index($setting) {
		static $module = 0;
		$this->language->load('module/ecsocialcoupon');
		$general_setting = $this->config->get("ecsocialcoupon_general");
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/ecsocialcoupon.css')) {
			$this->document->addStyle('catalog/view/theme/'.$this->config->get('config_template').'/stylesheet/ecsocialcoupon.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/ecsocialcoupon.css');
		}
		$this->document->addScript('catalog/view/javascript/ecsocialcoupon/jquery.cookie.js');
		$this->document->addScript('catalog/view/javascript/ecsocialcoupon/common.js');

		$this->data['enable_twitter'] = isset($general_setting['enable_twitter'])?$general_setting["enable_twitter"]:"1";
	    $this->data['enable_google'] = isset($general_setting['enable_google'])?$general_setting["enable_google"]:"1";
	    $this->data['enable_facebook'] = isset($general_setting['enable_facebook'])?$general_setting["enable_facebook"]:"1";
	    $this->data['enable_facebook_share'] = isset($general_setting['enable_facebook_share'])?$general_setting["enable_facebook_share"]:"1";
	    $this->data['enable_twitter_follow'] = isset($general_setting['enable_twitter_follow'])?$general_setting["enable_twitter_follow"]:"1";
	    $this->data['enable_linkedin'] = isset($general_setting['enable_linkedin'])?$general_setting["enable_linkedin"]:"1";

	    $this->data['popup_mode'] = isset($setting['popup_mode'])?$setting["popup_mode"]:0;
	    $this->data['popup_width'] = isset($setting['popup_width'])?$setting["popup_width"]:"50%";

	    $this->data['facebook_app_id'] = isset($general_setting['facebook_app_id'])?$general_setting['facebook_app_id']:"579922788744604";

    	if($this->data['enable_google'])
			$this->document->addScript('//apis.google.com/js/plusone.js?ver=3.5');
		if($this->data['enable_twitter'] || $this->data['enable_twitter_follow'])
			$this->document->addScript('//platform.twitter.com/widgets.js?ver=3.5');
		if($this->data['enable_facebook'])
			$this->document->addScript('//connect.facebook.net/en_US/all.js#xfbml=1&appId='.$this->data['facebook_app_id']);
		if($this->data['enable_linkedin'])
			$this->document->addScript('//platform.linkedin.com/in.js');
		
		if($this->data['popup_mode']){
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
		}


		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
         	$this->data['base'] = $this->config->get('config_ssl');
         	$this->data['base'] = empty($this->data['base'])?HTTPS_CATALOG:$this->data['base'];
	    } else {
	        $this->data['base'] = $this->config->get('config_url');
	        $this->data['base'] = empty($this->data['base'])?HTTP_CATALOG:$this->data['base'];
	    }
	    $lang_id = $this->config->get('config_language_id');

	    
	    $public_key = isset($general_setting["public_key"])?$general_setting["public_key"]:"";
	    $private_key = isset($general_setting["private_key"])?$general_setting["private_key"]:"";
		$token = $this->genToken($public_key,$private_key);

		$this->data['show_coupon_code'] = isset($general_setting['show_coupon_code'])?$general_setting["show_coupon_code"]:0;
		$this->data['expire_date'] = isset($general_setting['expire_date'])?$general_setting['expire_date']:7;
		$this->data['redirect'] = isset($general_setting['redirect'])?$general_setting['redirect']:0;
		$this->data["public_key"] = $public_key;
		$this->data["private_key"] = $private_key;
		$this->data["token"] = $token;
	    $this->data["route"] = isset($this->request->get["route"])?$this->request->get["route"]:"";
	    $this->data['applied_coupon'] = false;
	    if(isset($this->session->data['coupon']) && $this->session->data['coupon']){
	    	$this->data['applied_coupon'] = true;
	    }
	   	$products = $this->cart->getProducts();
	   	$this->data['have_cart_items'] = (count($products) > 0)?true:false;
	    $this->data['get_coupon_url'] = $this->data['base']."index.php?route=module/ecsocialcoupon/coupon";//$this->url->link('module/ecsocialcoupon/coupon', '', 'SSL');
	    $this->data['checkout_url'] = $this->url->link('checkout/cart', '', 'SSL');

	    $this->data['module_message'] = isset($general_setting['module_message'][$lang_id])?$general_setting["module_message"][$lang_id]:"";
	    $this->data['module_message'] = html_entity_decode($this->data['module_message'], ENT_QUOTES, 'UTF-8');
	    $this->data['module_message'] = str_replace(array("&lt;","&gt;"),array("<",">"),$this->data['module_message']);

	    $this->data['share_website'] = isset($setting['share_website'])?$setting['share_website']:"";
	    $this->data['share_website'] = (empty($this->data['share_website']) && isset($general_setting['share_website']))?$general_setting['share_website']:"http://ecomteck.com";
	    $this->data['share_title'] = isset($general_setting['share_title'][$lang_id])?$general_setting["share_title"][$lang_id]:"Get popular extensions";
	    $this->data['share_title'] = strip_tags($this->data['share_title']);
	    $this->data['share_message'] = isset($general_setting['share_message'][$lang_id])?$general_setting["share_message"][$lang_id]:"More great opencart extensions";
	    $this->data['share_message'] = html_entity_decode($this->data['share_message'], ENT_QUOTES, 'UTF-8');
	    $this->data['share_message'] = strip_tags($this->data['share_message']);
	    $this->data['share_image'] = isset($general_setting['share_image'][$lang_id])?$general_setting["share_image"][$lang_id]:"";

	    $this->data['twitter_account'] = isset($general_setting['twitter_account'])?$general_setting["twitter_account"]:"ecomteck";
	    $this->data['tweet_text'] = isset($general_setting['tweet_text'][$lang_id])?$general_setting["tweet_text"][$lang_id]:"";
	    $this->data['tweet_text'] = html_entity_decode($this->data['tweet_text'], ENT_QUOTES, 'UTF-8');
	    $this->data['tweet_text'] = str_replace(array("&lt;","&gt;"),array("<",">"),$this->data['tweet_text']);
	    $this->data['iso_code'] = "en";

	    $this->data['text_notice'] = isset($general_setting['notify_message'][$lang_id])?$general_setting["notify_message"][$lang_id]:$this->language->get("text_notice");
	    $this->data['text_notice'] = html_entity_decode($this->data['text_notice'], ENT_QUOTES, 'UTF-8');
	    $this->data['text_notice'] = str_replace(array("&lt;","&gt;"),array("<",">"),$this->data['text_notice']);

	    $this->data['module_width'] = isset($setting['module_width'])?$setting['module_width']:'auto';
	    $this->data['module_width'] = $this->data['module_width']!='auto'?(int)$this->data['module_width'].'px':'auto';
	    $this->data['module_height'] = isset($setting['module_height'])?$setting['module_height']:'auto';
	    $this->data['module_height'] = $this->data['module_height']!='auto'?(int)$this->data['module_height'].'px':'auto';


	    
	    $this->data['text_your_coupon'] = $this->language->get("text_your_coupon");

		$this->data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ecsocialcoupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ecsocialcoupon.tpl';
		} else {
			$this->template = 'default/template/module/ecsocialcoupon.tpl';
		}

		$this->render();
	}
	public function coupon(){
		$response = array();
		$setting = $this->config->get("ecsocialcoupon_general");
		$key = isset($this->request->post["key"])?$this->request->post["key"]:"";
		$token = isset($this->request->post["token"])?$this->request->post["token"]:"";
		if(empty($key) || empty($token))
			return $this->response->setOutput(json_encode($response));
		
		$private_key = isset($setting["private_key"])?$setting["private_key"]:"";
		$check_token = $this->genToken($key,$private_key);
		if($token == $check_token){
			$response["coupon"] = isset($setting["coupon"])?$setting["coupon"]:"";
		}
		$this->response->setOutput(json_encode($response));
	}
	private function genToken($public_key, $private_key){
		$token = "";
		if(!empty($public_key) && !empty($private_key))
		  $token = md5($public_key ."-". $private_key);

		return $token;
	}
}
?>
