<?php  
/*
 * Author: Ajai Verma(info@zxmod.com)
* It is illegal to remove this comment without prior notice to Ajai Verma(info@zxmod.com)
*/
class ControllerModuleFacebookLikeLite extends Controller {
	protected function index($setting) {
		$this->language->load('module/facebook_like_lite');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['facebook_page'] = $this->config->get('facebook_like_lite_page');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/facebook_like_lite.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/facebook_like_lite.tpl';
		} else {
			$this->template = 'default/template/module/facebook_like_lite.tpl';
		}

		$this->render();
	}
}
/*
 * Author: Ajai Verma(info@zxmod.com)
* It is illegal to remove this comment without prior notice to Ajai Verma(info@zxmod.com)
*/
?>