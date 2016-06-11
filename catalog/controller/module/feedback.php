<?php  
class ControllerModuleFeedback extends Controller {
	protected function index($setting) {
	
		static $module = 0;
		$this->load->language('information/feedback');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));
					
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/feeback.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/feedback.tpl';
		} else {
			$this->template = 'default/template/module/feedback.tpl';
		}
		
		$this->render();
	}
}
?>