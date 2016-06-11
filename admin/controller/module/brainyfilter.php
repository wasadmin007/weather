<?php
/**
* BrainyFilter 2.2, April 19, 2014 / brainyfilter.com
* Copyright 2014 Giant Leap Lab / www.giantleaplab.com
* License: Commercial. Reselling of this software or its derivatives is not allowed. You may use this software for one website ONLY including all its subdomains if the top level domain belongs to you and all subdomains are parts of the same OpenCart store.
* Support: support@giantleaplab.com
*/
class ControllerModuleBrainyFilter extends Controller {
	private $error = array(); 
	
	const SUBMIT_TYPE_AUTO    = "auto";
	const SUBMIT_TYPE_DELAY   = "delay";
	const SUBMIT_TYPE_BUTTON  = "button";
	const SUBMIT_BUTTON_FLOAT = "float";
	const SUBMIT_BUTTON_FIXED = "fixed";
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		// default settings
		$this->data['setting'] = array(
			'active_tab'         	=> 0,
			'submit_type'        	=> self::SUBMIT_TYPE_BUTTON,
			'submit_button_type' 	=> self::SUBMIT_BUTTON_FLOAT,
			'submit_delay_time'  	=> 1000,
			'hide_panel'		 	=> 1,
			'price_filter'       	=> 1,
			'attr_group'         	=> 0,
			'enable_attr'        	=> 1,
			'opencart_filters' 	 	=> 0,
			'manufacturer'       	=> 1,
			'stock_status'       	=> 1,
			'rating'  		     	=> 0,
			'option'  		     	=> 0,
			'sliding'       	 	=> 0,
			'sliding_opts'     	 	=> 4,
			'limit_height'    	 	=> 0,
			'limit_height_opts' 	=> 144,
			'sliding_min'     	 	=> 2,
			'product_count'      	=> 1,
			'subcategories_fix'  	=> 0,
			'layout_id'          	=> 3, // set Category layout by default
			'layout_position'    	=> 'column_left',
			'block_title'        	=> 'Brainy Filter',
			'sort_attr'          	=> 4,
			'sort_price'         	=> 1,
			'sort_stock'         	=> 2,
			'sort_manufacturer'  	=> 3,
			'sort_opencart_filters' => 5,
			'sort_rating' 			=> 6,
			'sort_option' 			=> 7,
			'image_and_label'		=> 2,
		);
	}
	
	/**
	 * Index action
	 * 
	 * @return void
	 */
	public function index() {   
		$this->load->language('module/brainyfilter');
		$this->_setupLanguage();

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/brainyfilter.css');

		$this->load->model('setting/setting');
		/* $vqmodeIsOn find in brainyfilter.xml */

		if (!isset($vqmodeIsOn))  {
			$this->error['vqmod'] = $this->language->get('error_vqmod');
		}
		

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validate()) {
			$this->model_setting_setting->editSetting('brainyfilter', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			if ($this->request->post['action'] == 'save') {			
				$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->redirect($this->url->link('module/brainyfilter', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		// apply module settings
		if (isset($this->request->post['attr_setting'])) 
		{
			$this->_applySettings($this->request->post['attr_setting']);
		} 
		elseif ($this->config->get('attr_setting')) 
		{
			$this->_applySettings($this->config->get('attr_setting'));
		}
		
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['error_warning'] = $this->error;

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
			'href'      => $this->url->link('module/brainyfilter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/brainyfilter', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();

		if (isset($this->request->post['brainyfilter_module'])) {
			$this->data['modules'] = $this->request->post['brainyfilter_module'];
		} elseif ($this->config->get('brainyfilter_module')) { 
			$this->data['modules'] = $this->config->get('brainyfilter_module');
		}	

		$this->load->model('localisation/stock_status');
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		if (isset($this->request->post['attr_setting']['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->request->post['attr_setting']['stock_status_id'];
    	} elseif (isset($this->data['setting']['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->data['setting']['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}

        $this->load->model('catalog/attribute');

		$total_attributes = $this->model_catalog_attribute->getTotalAttributes();
		$this->data['attributes'] = $this->model_catalog_attribute->getAttributes(array('start' => 0, 'limit' => $total_attributes));
    
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
                
                
              
		$this->template = 'module/brainyfilter.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	/**
	 * Data validation
	 * The method validates the given POST data
	 *
	 * @return boolean
	 */
	private function _validate() {
		if (!$this->user->hasPermission('modify', 'module/brainyfilter')) {
			$this->error[] = $this->language->get('bf_error_permission');
		}
		
		$postData = $this->request->post['attr_setting'];
		if ($postData['submit_type'] == self::SUBMIT_TYPE_DELAY) 
		{
			if (!is_numeric($postData['submit_delay_time'])) 
			{
				$this->error[] = $this->language->get('bf_error_submit_delay');
			}
		}
		
		if (!count($this->error)) {
			
			return true;
		} else {
			
			return false;
		}	
	}
	
	/**
	 * Apply Settings
	 *
	 * @param array $data Associative array of settings which should be changed
	 * @return void 
	 */
	private function _applySettings($data)
	{
		if (!is_array($data)) {
			
			return;
		}
		foreach ($data as $key => $value) {
			$this->data['setting'][$key] = $value;
		}
	}
	
	/**
	 * Set Up Language variables
	 * 
	 * @return void
	 */
	private function _setupLanguage()
	{
		$this->data['bf_signature']          = $this->language->get('bf_signature');
		
		$this->data['text_enabled']          = $this->language->get('text_enabled');
		$this->data['text_disabled']         = $this->language->get('text_disabled');
		$this->data['text_content_top']      = $this->language->get('text_content_top');
		$this->data['text_content_bottom']   = $this->language->get('text_content_bottom');		
		$this->data['text_column_left']      = $this->language->get('text_column_left');
		$this->data['text_column_right']     = $this->language->get('text_column_right');
		$this->data['text_yes']              = $this->language->get('text_yes');
		$this->data['text_no']               = $this->language->get('text_no');
		$this->data['text_attributes_display'] = $this->language->get('text_attributes_display');

		$this->data['entry_description']     = $this->language->get('entry_description');
		$this->data['entry_layout']          = $this->language->get('entry_layout');
		$this->data['entry_position']        = $this->language->get('entry_position');
		$this->data['entry_status']          = $this->language->get('entry_status');
		$this->data['entry_sort_order']      = $this->language->get('entry_sort_order');
		$this->data['entry_select_all']      = $this->language->get('entry_select_all');
		$this->data['entry_group_by']        = $this->language->get('entry_group_by');
		$this->data['entry_attr_mode']       = $this->language->get('entry_attr_mode');
		$this->data['entry_data_submission'] = $this->language->get('entry_data_submission');
		$this->data['entry_define']          = $this->language->get('entry_define');
		$this->data['entry_button']          = $this->language->get('entry_button');	
		$this->data['entry_button_description']  = $this->language->get('entry_button_description');	
		$this->data['entry_tab_position']    = $this->language->get('entry_tab_position');	
		$this->data['entry_tab_mode']        = $this->language->get('entry_tab_mode');	
		$this->data['entry_tab_attr']        = $this->language->get('entry_tab_attr');	
		$this->data['entry_tab_enchancements'] = $this->language->get('entry_tab_enchancements');	
		$this->data['entry_auto_submission'] = $this->language->get('entry_auto_submission');	
		$this->data['entry_auto_time']       = $this->language->get('entry_auto_time');	
		$this->data['entry_manufacturer']    = $this->language->get('entry_manufacturer');
		$this->data['entry_rating']    		 = $this->language->get('entry_rating');
		$this->data['entry_option']    		 = $this->language->get('entry_option');
		$this->data['entry_hide_panel']      = $this->language->get('entry_hide_panel');
		$this->data['entry_price_filter']    = $this->language->get('entry_price_filter');
		$this->data['entry_fixed']    		 = $this->language->get('entry_fixed');
		$this->data['entry_float']    		 = $this->language->get('entry_float');
		$this->data['entry_auto_submission_delay'] = $this->language->get('entry_auto_submission_delay');
		$this->data['entry_sliding']    	 = $this->language->get('entry_sliding');
		$this->data['entry_sliding_opts']    = $this->language->get('entry_sliding_opts');
		$this->data['entry_sliding_min']     = $this->language->get('entry_sliding_min');
		$this->data['entry_subcats_fix']     = $this->language->get('entry_subcats_fix');
		$this->data['entry_stock_status']    = $this->language->get('entry_stock_status');
		$this->data['entry_product_count']   = $this->language->get('entry_product_count');
		$this->data['entry_block_title']     = $this->language->get('entry_block_title');
		$this->data['entry_enable_attributes'] = $this->language->get('entry_enable_attributes');
		$this->data['entry_opencart_filters'] = $this->language->get('entry_opencart_filters');
		$this->data['entry_label']    		 = $this->language->get('entry_label');
		$this->data['entry_image']    		 = $this->language->get('entry_image');
		$this->data['entry_image_and_label'] = $this->language->get('entry_image_and_label');
		$this->data['entry_stock_status_id'] = $this->language->get('entry_stock_status_id');
		$this->data['entry_collapse'] 		 = $this->language->get('entry_collapse');
		$this->data['entry_limit_height'] 		 = $this->language->get('entry_limit_height');
		$this->data['entry_limit_height_opts'] 		 = $this->language->get('entry_limit_height_opts');
		
		$this->data['button_save']           = $this->language->get('button_save');
		$this->data['button_apply']          = $this->language->get('button_apply');
		$this->data['button_cancel']         = $this->language->get('button_cancel');
		$this->data['button_add_module']     = $this->language->get('button_add_module');
		$this->data['button_remove']         = $this->language->get('button_remove');
		$this->data['tab_module']            = $this->language->get('tab_module');
		
	}
}
?>