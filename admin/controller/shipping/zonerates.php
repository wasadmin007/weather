<?php
class ControllerShippingZoneRates extends Controller { 
	private $error = array();
	
	public function index() {  
		$this->load->language('shipping/zonerates');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('zonerates', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
                $this->data['text_item'] = $this->language->get('text_item');
                $this->data['text_price'] = $this->language->get('text_price');
                $this->data['text_weight'] = $this->language->get('text_weight');
		
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
                $this->data['entry_calculation_method'] = $this->language->get('entry_calculation_method');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/zonerates', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/zonerates', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 

		$this->load->model('localisation/geo_zone');
		
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();
		
		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['zonerates_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$this->data['zonerates_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['zonerates_' . $geo_zone['geo_zone_id'] . '_rate'];
			} else {
				$this->data['zonerates_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->config->get('zonerates_' . $geo_zone['geo_zone_id'] . '_rate');
			}		
			
			if (isset($this->request->post['zonerates_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$this->data['zonerates_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['zonerates_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
				$this->data['zonerates_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('zonerates_' . $geo_zone['geo_zone_id'] . '_status');
			}		
		}
                
                if (isset($this->request->post['zonerates_00_rate'])) {
				$this->data['zonerates_00_rate'] = $this->request->post['zonerates_00_rate'];
			} else {
				$this->data['zonerates_00_rate'] = $this->config->get('zonerates_00_rate');
			}		
			
			if (isset($this->request->post['zonerates_00_status'])) {
				$this->data['zonerates_00_status'] = $this->request->post['zonerates_00_status'];
			} else {
				$this->data['zonerates_00_status'] = $this->config->get('zonerates_00_status');
			}
                        
                        $otherZonesarray[] = array('geo_zone_id' => '00',
                                                    'name' => 'Other Zone(s)',
                                                    'description' => 'Other Zone(s)');
		
		$this->data['geo_zones'] = array_merge($geo_zones,$otherZonesarray);
                
		if (isset($this->request->post['zonerates_tax_class_id'])) {
			$this->data['zonerates_tax_class_id'] = $this->request->post['zonerates_tax_class_id'];
		} else {
			$this->data['zonerates_tax_class_id'] = $this->config->get('zonerates_tax_class_id');
		}
		
		if (isset($this->request->post['zonerates_status'])) {
			$this->data['zonerates_status'] = $this->request->post['zonerates_status'];
		} else {
			$this->data['zonerates_status'] = $this->config->get('zonerates_status');
		}
		
		if (isset($this->request->post['zonerates_sort_order'])) {
			$this->data['zonerates_sort_order'] = $this->request->post['zonerates_sort_order'];
		} else {
			$this->data['zonerates_sort_order'] = $this->config->get('zonerates_sort_order');
		}
                
                if (isset($this->request->post['zonerates_calculation_method'])) {
			$this->data['zonerates_calculation_method'] = $this->request->post['zonerates_calculation_method'];
		} else {
			$this->data['zonerates_calculation_method'] = $this->config->get('zonerates_calculation_method');
		}
		
		$this->load->model('localisation/tax_class');
				
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->template = 'shipping/zonerates.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/zonerates')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>