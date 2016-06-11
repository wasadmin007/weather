<?php
// -------------------------------
// PureChat for OpenCart 
// By Best-Byte
// www.best-byte.com
// -------------------------------
?>
<?php  
class ControllerModulePurechat extends Controller {
	
	protected function index() {
		
		$this->data['code'] = $this->config->get('purechat_code');
		
		$this->id = 'purechat';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/purechat.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/purechat.tpl';
		} else {
			$this->template = 'default/template/module/purechat.tpl';
		}
				
		$this->response->setOutput($this->render());		
	}
}
?>