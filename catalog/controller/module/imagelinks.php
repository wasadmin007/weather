<?php
class ControllerModuleImageLinks extends Controller {
	protected function index() {
		$this->language->load('module/imagelinks');
		$this->load->model('tool/image');

      	$this->data['heading_title'] = $this->language->get('heading_title');
        $image = array();
        $alt = array();
        $url = array();
        
      	for($i = 0; $this->config->get('imagelinks_image'.$i); $i++){
      		
      		$image = rtrim($this->config->get('imagelinks_image'.$i)) ;
      		$this->data['image'][$i] = $image ? HTTP_IMAGE . $image : false; 
      		$this->data['alt'][$i] = $this->config->get('imagelinks_alt'.$i);
      		$this->data['url'][$i] = $this->config->get('imagelinks_url'.$i);
      	}
      	      	
      	
      	
		
		$this->id = 'imagelinks';

		if ($this->config->get('imagelinks_position') == 'home') {
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/imagelinks_home.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/imagelinks_home.tpl';
			} else {
				$this->template = 'default/template/module/imagelinks_home.tpl';
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/imagelinks.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/imagelinks.tpl';
			} else {
				$this->template = 'default/template/module/imagelinks.tpl';
			}
		}

		$this->render();
	}
}
?>