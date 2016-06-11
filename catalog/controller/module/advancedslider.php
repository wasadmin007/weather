<?php  
class ControllerModuleadvancedslider extends Controller {

	private $_name = 'advancedslider';
	
		// pos func
		private function getPosConfig($include_inactive=false)
  	{
		$config = array();
		if( ($data = $this->config->get('advancedslider_ccpos_config')) && ($data = unserialize($data)) && is_array($data) )
		{
			$config = $data;
			if(!$include_inactive)
			{
				foreach($config as $key=>$pos)
				{
					if($pos['aktif']!=1)
						unset($config[$key]);
				}
			}
		}		
		return $config;
	}

	// pos func son

	protected function index() {
		$this->language->load('module/' . $this->_name);

      		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (isset($this->request->post['advancedslider_code' . $language['language_id']])) {
				$this->data['advancedslider_code' . $language['language_id']] = $this->request->post['advancedslider_code' . $language['language_id']];
			} else {
				$this->data['advancedslider_code' . $language['language_id']] = $this->config->get('advancedslider_code' . $language['language_id']);
			}
		}
		$this->data['code'] = html_entity_decode($this->config->get('advancedslider_code' . $this->config->get('config_language_id')));
		
		foreach ($languages as $language) {
			if (isset($this->request->post['advancedslider_title' . $language['language_id']])) {
				$this->data['advancedslider_title' . $language['language_id']] = $this->request->post['advancedslider_title' . $language['language_id']];
			} else {
				$this->data['advancedslider_title' . $language['language_id']] = $this->config->get('advancedslider_title' . $language['language_id']);
			}
		}
		$this->data['title'] = $this->config->get($this->_name . '_title' . $this->config->get('config_language_id'));
		$this->data['slide_size'] = $this->config->get($this->_name . '_slide_size');
		$this->data['slide_height'] = $this->config->get($this->_name . '_slide_height');
		$this->data['slide_duration'] = $this->config->get($this->_name . '_slide_duration');
		$this->data['slide_velocity'] = $this->config->get($this->_name . '_slide_velocity');
		$this->data['slide_headline'] = $this->config->get($this->_name . '_headline');
		$this->data['header'] = $this->config->get( $this->_name . '_header');
		
		$this->id = $this->_name;
// pos
		$this->language->load('module/advancedslider');
		
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['error_failed'] = $this->language->get('error_failed');
		
		$this->data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$mon = sprintf('%02d', $i);
			$this->data['months'][] = array(
				//'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'text'  => $mon,
				'value' => $mon
			);
		}
		
		$today = getdate();

		$this->data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		$this->data['ccpos_config'] = $this->getPosConfig();

		

// pos son
		$slide_type=$this->config->get($this->_name . '_slide_type');
		$tmpl = '/template/module/' . $this->_name . '_type_'.$slide_type.'.tpl'; 
		$tmplhome = '/template/module/' . $this->_name . '_home.tpl';
 
		if( !$this->data['title'] ) { 
			$this->data['title'] = $this->data['heading_title']; 
		} 
		if( !$this->data['header'] ) { 
			$this->data['title'] = ''; 
		}
		
		if ($this->config->get('advancedslider_position') == 'home') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $tmplhome)) {
				$this->template = $this->config->get('config_template') . $tmpl;
			} else {
				$this->template = 'default' . $tmpl;
			}		
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $tmpl)) {
				$this->template = $this->config->get('config_template') . $tmpl;
			} else {
				$this->template = 'default' . $tmpl;
			}
		}

		$this->render();
	}
}
?>
