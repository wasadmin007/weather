<?php
class ControllerModuleMyGeneralTab extends Controller {
	private $error = array();
	public function index() {
		$this->load->language('module/my_general_tab');
		$this->document->setTitle($this->language->get('mygt_title'));
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$arr = array('mygt_setting' => implode(',', $this->request->post['mygt_use']));
			$this->model_setting_setting->editSetting('mygt', $arr);
			$this->session->data['success'] = $this->language->get('mygt_success_text');
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['error']) ) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}
		else {
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
			'text'      => $this->language->get('mygt_title'),
			'href'      => $this->url->link('module/my_general_tab', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['token'] = $this->session->data['token'];
		$this->data['button_save'] = $this->language->get('mygt_save');
		$this->data['button_cancel'] = $this->language->get('mygt_cancel');
		
		if (isset($this->request->post['mygt_use'])) {
			$this->data['mygt_use'] = $this->request->post['mygt_use'];
		} else {
			$this->data['mygt_use'] = explode(",", $this->config->get('mygt_setting'));
		}
		
		$this->data['action'] = $this->url->link('module/my_general_tab', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['heading_title'] = $this->language->get('mygt_title');
		$this->data['description'] = $this->language->get('mygt_description');
		$this->data['entry_quantity'] = $this->language->get('mygt_entry_quantity');
		$this->data['entry_manufacturer'] = $this->language->get('mygt_entry_manufacturer');
		$this->data['entry_categories'] = $this->language->get('mygt_entry_categories');
		$this->data['entry_downloads'] = $this->language->get('mygt_entry_downloads');
		$this->data['yes'] = $this->language->get('mygt_yes');
		$this->data['no'] = $this->language->get('mygt_no');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['my_general_tab_module'])) {
			$this->data['modules'] = $this->request->post['my_general_tab_module'];
		} else { 
			$this->data['modules'] = $this->config->get('my_general_tab_module');
		}
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->template = 'module/my_general_tab.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());

	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/my_general_tab')) {
			$this->error['warning'] = $this->language->get('mygt_error_access');
		}
		
		if (isset($this->request->post['my_general_tab_module'])) {
			foreach ($this->request->post['my_general_tab_module'] as $key => $value) {				
				
			}
		}	
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','mygt','mygt_setting','y,y,y,n','0')");
	}

	public function uninstall() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'mygt_setting'");
	}

}
?>
