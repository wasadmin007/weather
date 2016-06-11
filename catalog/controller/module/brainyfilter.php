<?php  
/**
* BrainyFilter 2.2, April 19, 2014 / brainyfilter.com
* Copyright 2014 Giant Leap Lab / www.giantleaplab.com
* License: Commercial. Reselling of this software or its derivatives is not allowed. You may use this software for one website ONLY including all its subdomains if the top level domain belongs to you and all subdomains are parts of the same OpenCart store.
* Support: support@giantleaplab.com
*/
class ControllerModuleBrainyFilter extends Controller {
	public function index($setting) {
		
		$this->language->load('module/brainyfilter');
		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart'
		);
		 if (preg_match('/(iPhone|iPod|iPad|Android)/', $_SERVER['HTTP_USER_AGENT'])) {
			$this->document->addScript('catalog/view/javascript/jquery.ui.touch-punch.min.js');
		}
		$this->document->addScript('catalog/view/javascript/brainyfilter.js');
		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/brainyfilter.css')) {
        	$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template'). '/stylesheet/brainyfilter.css');
    	}else{
    		$this->document->addStyle('catalog/view/theme/default/stylesheet/brainyfilter.css');
    	}
		

		$settings = $this->config->get('attr_setting');

		$this->load->model('module/brainyfilter');
		$this->data['path'] = (isset($this->request->get['path'])) ? $this->request->get['path'] : "";

		$data = $this->_prepareFilterInitialData();
		if ($settings['price_filter']) {
			$minMax = $this->model_module_brainyfilter->getMinMaxCategoryPrice($data);
			$min = floor($this->currency->format($minMax['min'], '', '', false));
			$max = ceil($this->currency->format($minMax['max'], '', '', false));
		}else{
			$min = 0;
			$max = 0;
		}
		$this->data['selected_attr'] = isset($this->request->get['attribute_value']) 
				? $this->request->get['attribute_value']
				: array();
		$this->data['selected_manufacturer'] = isset($this->request->get['manufacturer']) 
				? $this->request->get['manufacturer']
				: array();
		$this->data['selected_statuses'] = isset($this->request->get['stock_status'])
				? $this->request->get['stock_status']
				: array();
		$this->data['selected_rating'] = isset($this->request->get['bfrating'])
				? $this->request->get['bfrating']
				: array();
		$this->data['selected_option'] = isset($this->request->get['bfoption'])
				? $this->request->get['bfoption']
				: array();
		$this->data['selected_filter'] = isset($this->request->get['filter'])
				? explode(',',$this->request->get['filter'])
				: array();
		$this->data['heading_title']        = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		$this->data['text_filter_price']    = $this->language->get('text_filter_price');
		$this->data['default_value_select'] = $this->language->get('default_value_select');
		$this->data['lang_price']           = $this->language->get('price_header');
		$this->data['lang_submit']          = $this->language->get('submit');
		$this->data['lang_manufacturers']   = $this->language->get('manufacturers');
		$this->data['lang_stock_status']    = $this->language->get('stock_status');
		$this->data['lang_rating']   		= $this->language->get('rating');
		$this->data['lang_option']   		= $this->language->get('option');
		$this->data['min_max']          	= $this->language->get('min_max');
		$this->data['reset']          		= $this->language->get('reset');
		$this->data['lang_show_more'] 		= $this->language->get('entry_show_more');
		$this->data['lang_show_less'] 		= $this->language->get('entry_show_less');
		$this->data['lang_vqmod_error']     = $this->language->get('entry_vqmod_error');
		$this->data['priceMin']             = $min ? $min : 0;
		$this->data['priceMax']             = $max ? $max : 0;
		$this->data['rating']				= $settings['rating'];
		$this->data['lowerlimit']           = isset($this->request->get['lower']) ? $this->request->get['lower'] : $this->data['priceMin'];
		$this->data['upperlimit']           = isset($this->request->get['higher']) ? $this->request->get['higher'] : $this->data['priceMax'];
		$this->data['attr_groups']          = $this->model_module_brainyfilter->getAttributes($data);
		$this->data['manufacturers'] 		= false;
		$this->data['stock_statuses']       = false;
		$this->data['options']      		= false;

		$this->data['collapse_price']		= isset($settings['collapse_price'])? $settings['collapse_price'] : 0;
		$this->data['collapse_stock']		= isset($settings['collapse_stock'])? $settings['collapse_stock'] : 0;
		$this->data['collapse_manufacturer']= isset($settings['collapse_manufacturer'])? $settings['collapse_manufacturer'] : 0;
		$this->data['collapse_attr']		= isset($settings['collapse_attr'])? $settings['collapse_attr'] : 0;
		$this->data['collapse_opencart_filters']= isset($settings['collapse_opencart_filters'])? $settings['collapse_opencart_filters'] : 0;
		$this->data['collapse_rating']		= isset($settings['collapse_rating'])? $settings['collapse_rating'] : 0;
		$this->data['limit_height']			= $settings['limit_height'];
		$this->data['limit_height_opts']	= $settings['limit_height_opts'];
		
		//$this->data['collapse_option']		= isset($settings['collapse_option'])? $settings['collapse_option'] : 0;
		
		$this->data['enable_attr'] 			= $settings['enable_attr'];
		$this->data['sliding'] 				= $settings['sliding'];
		$this->data['slidingOpts'] 			= $settings['sliding_opts'];
		$this->data['slidingMin'] 			= $settings['sliding_min'];
		$this->data['sort_price'] 			= !empty($settings['sort_price']) ? $settings['sort_price'] : 100;
		$this->data['sort_attr'] 			= !empty($settings['sort_attr']) ? $settings['sort_attr'] : 100;
		$this->data['sort_stock'] 			= !empty($settings['sort_stock']) ? $settings['sort_stock'] : 100;
		$this->data['sort_manufacturer']    = !empty($settings['sort_manufacturer']) ? $settings['sort_manufacturer'] : 100;
		$this->data['sort_opencart_filters']= !empty($settings['sort_opencart_filters']) ? $settings['sort_opencart_filters'] : 100;
		$this->data['sort_rating']			= !empty($settings['sort_rating']) ? $settings['sort_rating'] : 100;
		$this->data['sort_option']			= !empty($settings['sort_option']) ? $settings['sort_option'] : 100;
		$this->data['count']  = array($this->data['sort_price'], $this->data['sort_attr'], $this->data['sort_stock'], $this->data['sort_manufacturer'], $this->data['sort_opencart_filters'], $this->data['sort_rating'], $this->data['sort_option']);
		sort($this->data['count']);
		$this->data['count'] = array_unique($this->data['count']);
		if ($settings['manufacturer']) {
			$this->data['manufacturers']    = $this->model_module_brainyfilter->getManufacturers($data);
		}
		if ($settings['stock_status']) {
			$this->data['stock_statuses']   = $this->model_module_brainyfilter->getStockStatuses();
		}
		/*if ($settings['option']) {
			$this->data['options']   = $this->model_module_brainyfilter->getOptions($data);
		}*/
		if ($this->currency->getsymbolleft()) {
			$this->data['currency_symbol']  = $this->currency->getsymbolleft();
			$this->data['cur_symbol_side']  = 'left';
		} else {
			$this->data['currency_symbol']  = $this->currency->getsymbolright();
			$this->data['cur_symbol_side']  = 'right';
		}
		$this->data['attr_setting']         = $settings;
		$this->data['attr_group']           = $settings['attr_group'];
		
		
			
		if ($settings['opencart_filters']) {
			$this->data['filter_groups'] = array();
			
			$filter_groups = $this->model_catalog_category->getCategoryFilters($data['filter_category_id']);
			if ($filter_groups) {
				foreach ($filter_groups as $filter_group) {
					$filter_data = array();
					
					foreach ($filter_group['filter'] as $filter) {
						
						$filter_data[] = array(
							'filter_id' => $filter['filter_id'],
							'name'      => $filter['name']
						);
					}
					
					$this->data['filter_groups'][] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);
				} 
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/brainyfilter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/brainyfilter.tpl';
		} else {
			$this->template = 'default/template/module/brainyfilter.tpl';
		}

		$this->response->setOutput($this->render());
	}
	
	public function ajaxfilter()
	{
		$attr_setting = $this->config->get('attr_setting');
		$data = $this->_prepareFilterInitialData();
		$settings = $this->config->get('attr_setting');
		
		$this->registry->set('category_ajax', 1);
		$products = $this->getChild('product/category', $data);
		
		$this->load->model('module/brainyfilter');
		
		$totalNumbers = array();
		
		if ($settings['product_count']) {
			$totalByAttr = ($settings['enable_attr']) 
				? $this->model_module_brainyfilter->getTotalByAttributes($data) 
				: array();

			$totalByMn = ($settings['manufacturer']) 
				? $this->model_module_brainyfilter->getTotalByManufacturer($data) 
				: array();

			$totalByStatus = ($settings['stock_status']) 
				? $this->model_module_brainyfilter->getTotalByStockStatus($data) 
				: array();

			/*$totalByoption = ($settings['option']) 
				? $this->model_module_brainyfilter->getTotalByOption($data) 
				: array();*/

			$totalByFilter = ($settings['opencart_filters']) 
				? $this->model_module_brainyfilter->getTotalByFilter($data) 
				: array();

			$totalByRg = ($settings['rating']) 
				? $this->model_module_brainyfilter->getTotalByRating($data) 
				: array();
			
			$totalNumbers = array_merge($totalByAttr, $totalByMn, $totalByStatus, $totalByFilter, $totalByRg); //,$totalByoption);
		}
		if ($settings['price_filter']) {
			$minMax = $this->model_module_brainyfilter->getMinMaxCategoryPrice($data);
			$min = floor($this->currency->format($minMax['min'], '', '', false));
			$max = ceil($this->currency->format($minMax['max'], '', '', false));
			$json = json_encode(array(
				'products' => $products, 
				'brainyfilter' => $totalNumbers,
				'min' => $min,
				'max' => $max
			));	
		}else {
			$json = json_encode(array(
				'products' => $products, 
				'brainyfilter' => $totalNumbers
			));	
		}
		
		die($json);
	}
	
	public function ajaxCountAttributes()
	{
		$data = $this->_prepareFilterInitialData();
		$settings = $this->config->get('attr_setting');
		
		if (!$settings['product_count']) {
			die(json_encode(array()));
		}
		
		$this->load->model('module/brainyfilter');
		$totalByAttr = ($settings['enable_attr']) 
				? $this->model_module_brainyfilter->getTotalByAttributes($data) 
				: array();
		
		$totalByMn = ($settings['manufacturer']) 
			? $this->model_module_brainyfilter->getTotalByManufacturer($data) 
			: array();

		$totalByFilter = ($settings['opencart_filters']) 
			? $this->model_module_brainyfilter->getTotalByFilter($data) 
			: array();

		$totalByStatus = ($settings['stock_status']) 
			? $this->model_module_brainyfilter->getTotalByStockStatus($data) 
			: array();

		$totalByRg = ($settings['rating']) 
			? $this->model_module_brainyfilter->getTotalByRating($data) 
			: array();

		/*$totalByoption = ($settings['option']) 
				? $this->model_module_brainyfilter->getTotalByOption($data) 
				: array();*/
		
		die(json_encode(array_merge($totalByAttr, $totalByMn, $totalByStatus, $totalByFilter, $totalByRg)));//, $totalByoption)));
	}
	
	private function _prepareFilterInitialData()
	{
		$categoryId = false;
		if(isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$categoryId = array_pop($parts);
		}
		
		$data = array(
			'filter_category_id' => $categoryId,
		);

		$settings = $this->config->get('attr_setting');
		
		if ($settings['subcategories_fix']) {
			$data['filter_sub_category'] = true;
		}
		
		return $data;
	}
}
?>
