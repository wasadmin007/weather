<?php 
class ControllerModuleMaintenance extends Controller {
	protected function index($setting) {
	
	$this->language->load('module/maintenance');
	
	$module = $this->config->get('maintenance_module');
	$module = $module[0];
	//If we are using admin viewable & admin is logged
	if($module['admin'] == 1 && isset($this->session->data['token'])){	
		//Load the HTML file
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/maintenance/admin.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/maintenance/admin.tpl';
		} else {
			$this->template = 'default/template/module/maintenance/admin.tpl';
		}
		include 'catalog/view/theme/'.$this->template;
		$maintenance = false;
		$this->id = 'maintenance';
		
		//Admin view is unavailable, lets load the maintenance message
	}else{
		
		//Check if we need a logo
		if($module['logo'] == 1){
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = HTTPS_IMAGE;
			} else {
				$server = HTTP_IMAGE;
			}
			
			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$this->data['logo'] = $server . $this->config->get('config_logo');
				$this->data['store'] = $this->config->get('config_title');
			} else {
				$this->data['logo'] = '';
				$this->data['store'] = '';
			}
			
		}else{
			$this->data['logo'] = '';
		}
		
		//Decode our stored binary data to HTML -- IMPORTANT
		$message = html_entity_decode($module['message']);
		
		
		//Load the template file
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/maintenance/maintenance.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/maintenance/maintenance.tpl';
		} else {
			$this->template = 'default/template/module/maintenance/maintenance.tpl';
		}
		
		//Load the CSS file
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/maintenance.css')) {
			$this->data['css'] = 'catalog/view/theme/'.$this->config->get('config_template') . '/stylesheet/maintenance.css';
		} else {
			$this->data['css'] = 'catalog/view/theme/default/stylesheet/maintenance.css';
		}
		
		//Execute the template file
		include DIR_TEMPLATE . $this->template;
		die();
	}
	}
}
?>