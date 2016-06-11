<?php
class ControllerModuleEcsocialcoupon extends Controller {
	private $error = array();

	public function index() {

		$this->language->load('module/ecsocialcoupon');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$action = isset($this->request->post["action"])?$this->request->post["action"]:"";

			$this->model_setting_setting->editSetting('ecsocialcoupon', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			if($action == "save_stay"){
				$this->redirect($this->url->link('module/ecsocialcoupon', 'token=' . $this->session->data['token'], 'SSL'));
			}else{
				$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
		}
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_header_top'] = $this->language->get('text_header_top');
		$this->data['text_header_bottom'] = $this->language->get('text_header_bottom');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
        $this->data['text_footer_top'] = $this->language->get('text_footer_top');
		$this->data['text_footer_bottom'] = $this->language->get('text_footer_bottom');
        $this->data['text_alllayout'] = $this->language->get('text_alllayout');
		$this->data['text_default'] = $this->language->get('text_default');
		
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');
		$this->data['entry_content'] = $this->language->get('entry_content');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_public_key'] = $this->language->get('entry_public_key');
		$this->data['entry_private_key'] = $this->language->get('entry_private_key');
		$this->data['entry_tweet_text'] = $this->language->get('entry_tweet_text');
		$this->data['entry_share_website'] = $this->language->get('entry_share_website');
		$this->data['entry_module_message'] = $this->language->get('entry_module_message');
		$this->data['entry_notify_message'] = $this->language->get('entry_notify_message');
		$this->data['entry_enable_twitter'] = $this->language->get('entry_enable_twitter');
		$this->data['entry_enable_google'] = $this->language->get('entry_enable_google');
		$this->data['entry_enable_facebook'] = $this->language->get('entry_enable_facebook');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_save_stay'] = $this->language->get('button_save_stay');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_new_block'] = $this->language->get('button_add_new_block');
		$this->data['text_alllayout'] = $this->language->get('text_all_layout');


		
		$this->data['tab_block'] = $this->language->get('tab_block');
		$this->data['token'] = $this->session->data["token"];
		$this->data['positions'] = array( 
										  'content_top',
										  'content_bottom',
										  'column_left',
										  'column_right'
		);
		$this->load->model('localisation/language'); 
   		$languages = $this->model_localisation_language->getLanguages();
		$this->data['languages'] = $languages;

		$this->load->model('sale/coupon');
		$data = array();
		$data["limit"] = 9999;
		$data["start"] = 0;
   		$coupons = $this->model_sale_coupon->getCoupons($data);
		$this->data['coupons'] = $coupons;

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['dimension'])) {
			$this->data['error_dimension'] = $this->error['dimension'];
		} else {
			$this->data['error_dimension'] = array();
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/ecsocialcoupon', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('module/ecsocialcoupon', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['create_coupon'] = $this->url->link('sale/coupon/insert', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();

		if (isset($this->request->post['ecsocialcoupon_module'])) {
			$this->data['modules'] = $this->request->post['ecsocialcoupon_module'];
		} elseif ($this->config->get('ecsocialcoupon_module')) {
			$this->data['modules'] = $this->config->get('ecsocialcoupon_module');
		}
		$this->data["general"] = array();
		if (isset($this->request->post['ecsocialcoupon_general'])) {
			$this->data['general'] = $this->request->post['ecsocialcoupon_general'];
		} elseif ($this->config->get('ecsocialcoupon_general')) {
			$this->data['general'] = $this->config->get('ecsocialcoupon_general');
		}
    	

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->template = 'module/ecsocialcoupon.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/ecsocialcoupon')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['ecsocialcoupon_module'])) {
			/**
			foreach ($this->request->post['ecsocialcoupon_module'] as $key => $value) {
				
			}
			*/
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
