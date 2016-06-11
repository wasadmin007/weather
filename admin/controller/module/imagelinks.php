<?php
class ControllerModuleImageLinks extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/imagelinks');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			
			
			$this->model_setting_setting->editSetting('imagelinks', $this->request->post);								
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		$this->data['text_home'] = $this->language->get('text_home');
		
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_alt'] = $this->language->get('entry_alt');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_image_manager'] =  $this->language->get('text_image_manager');
		$this->data['image_instruction'] =  $this->language->get('image_instruction');
		

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=module/imagelinks&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/imagelinks&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['imagelinks_limit'])) {
			$this->data['imagelinks_limit'] = $this->request->post['imagelinks_limit'];
		} else {
			$this->data['imagelinks_limit'] = $this->config->get('imagelinks_limit');
		}	
		
		 if(! $this->data['imagelinks_limit']){
		 	 $this->data['imagelinks_limit'] = 5;
		 }
		
		$this->data['positions'] = array();
		
		$this->data['positions'][] = array(
			'position' => 'left',
			'title'    => $this->language->get('text_left'),
		);
		
		$this->data['positions'][] = array(
			'position' => 'right',
			'title'    => $this->language->get('text_right'),
		);
		
		$this->data['positions'][] = array(
			'position' => 'home',
			'title'    => $this->language->get('text_home'),
		);
		
		if (isset($this->request->post['imagelinks_position'])) {
			$this->data['imagelinks_position'] = $this->request->post['imagelinks_position'];
		} else {
			$this->data['imagelinks_position'] = $this->config->get('imagelinks_position');
		}
		
		if (isset($this->request->post['imagelinks_status'])) {
			$this->data['imagelinks_status'] = $this->request->post['imagelinks_status'];
		} else {
			$this->data['imagelinks_status'] = $this->config->get('imagelinks_status');
		}
		
		if (isset($this->request->post['imagelinks_sort_order'])) {
			$this->data['imagelinks_sort_order'] = $this->request->post['imagelinks_sort_order'];
		} else {
			$this->data['imagelinks_sort_order'] = $this->config->get('imagelinks_sort_order');
		}				
		
		
		$imagelinks_image= array();
		$imagelinks_url = array();
		$imagelinks_alt = array();
		$preview = array();
		
		$this->load->model('tool/image');
		for ($i = 0; $i < 10; $i++){						
			if (isset($this->request->post['imagelinks_image'.$i])) {								
				$imagelinks_image[$i] = $this->request->post['imagelinks_image'.$i];
			  	$imagelinks_url[$i] = $this->request->post['imagelinks_url'.$i];
			  	$imagelinks_alt[$i] = $this->request->post['imagelinks_alt'.$i];
			  	$preview[$i] = $this->model_tool_image->resize($this->request->post['imagelinks_image'.$i], 100, 100);
			} else {
				$imagelinks_image[$i] = $this->config->get('imagelinks_image'.$i);
				$imagelinks_url[$i] = $this->config->get('imagelinks_url'.$i);
				$imagelinks_alt[$i] = $this->config->get('imagelinks_alt'.$i);
				$preview[$i]  = $this->model_tool_image->resize($this->config->get('imagelinks_image'.$i), 100, 100);			
			}
			
			if (!$preview[$i]){
				$preview[$i]  = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
						
		}
		
		
		
		
                $this->data['imagelinks_preview'] = $preview;
                $this->data['imagelinks_image'] = $imagelinks_image;
                $this->data['imagelinks_url'] =   $imagelinks_url;
                $this->data['imagelinks_alt'] =   $imagelinks_alt;
                
			
		$this->template = 'module/imagelinks.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/imagelinks')) {
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
