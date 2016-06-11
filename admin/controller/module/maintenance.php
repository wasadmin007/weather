<?php
class ControllerModuleMaintenance extends Controller {
	private $error = array(); 
	
	public function index() {  
	
		$this->load->language('module/maintenance');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['maintenance_status'] = $this->config->get('maintenance_status');
		$this->data['maintenance_logo'] = $this->config->get('maintenance_logo');
		$this->data['maintenance_admin'] = $this->config->get('maintenance_admin');
		$this->data['maintenance_message'] = $this->config->get('maintenance_message');
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$post = $this->request->post;
			$i = 0;
			while($i < 12){
				$key = $i+1;
				$post['maintenance_module'][$key] = $post['maintenance_module'][$i];
				$post['maintenance_module'][$key]['layout_id'] = $i;
				$this->model_setting_setting->editSetting('maintenance', $post);
				next($post['maintenance_module']);
				$i++;
			}
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_enable'] = $this->language->get('entry_enable');
		$this->data['entry_admin_viewable'] = $this->language->get('entry_admin_viewable');
		$this->data['entry_show_logo'] = $this->language->get('entry_show_logo');
		$this->data['entry_message'] = $this->language->get('entry_message');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

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
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/maintenance', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/maintenance', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/maintenance&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];
		
		
		$this->data['modules'][0] = array();
		
		if (isset($this->request->post['maintenance_module'])) {
				$this->data['modules'][0] = $this->request->post['maintenance_module'];
		} elseif ($this->config->get('maintenance_module')) { 
			$this->data['modules'] = $this->config->get('maintenance_module');
		}	

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/maintenance.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/maintenance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>