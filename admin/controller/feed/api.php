<?php
class ControllerFeedApi extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('feed/api');

		//$this->document->title = $this->language->get('heading_title');
    $this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('api', $this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/feed&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

    $this->data['text_image_manager'] = $this->language->get('text_image_manager');
    $this->data['text_help'] = $this->language->get('text_help');
    
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
    $this->data['text_edit'] = $this->language->get('text_edit');
    $this->data['text_remove'] = $this->language->get('text_remove');
    $this->data['text_browse'] = $this->language->get('text_browse');
    $this->data['text_clear'] = $this->language->get('text_clear');
		
    $this->data['entry_image'] = $this->language->get('entry_image');
    $this->data['entry_status'] = $this->language->get('entry_status');
    $this->data['entry_encoding'] = $this->language->get('entry_encoding');

    $this->data['entry_plataform_version'] = $this->language->get('entry_plataform_version');
    $this->data['entry_location'] = $this->language->get('entry_location');
    $this->data['entry_banner'] = $this->language->get('entry_banner');
    $this->data['entry_group'] = $this->language->get('entry_group');
    
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
    $this->data['button_remove'] = $this->language->get('button_remove');
    $this->data['button_add_image'] = $this->language->get('button_add_image');

    $this->data['tab_general'] = $this->language->get('tab_general');
    $this->data['tab_image'] = $this->language->get('tab_image');

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
      'text'      => $this->language->get('text_feed'),
      'href'      => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),           
      'separator' => ' :: '
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('feed/api', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );
    
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=feed/api&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/feed&token=' . $this->session->data['token'];
    
    $this->data['token'] = $this->session->data['token'];
		
    if (isset($this->request->post['api_store_logo'])) {
      $this->data['api_store_logo'] = $this->request->post['api_store_logo'];
    } elseif ( $this->config->get('api_store_logo')) {
      $this->data['api_store_logo'] = $this->config->get('api_store_logo');
    } else {
      $this->data['api_store_logo'] = '';
    }
    
    $this->load->model('tool/image');

    if ($this->config->get('api_store_logo') && file_exists(DIR_IMAGE . $this->config->get('api_store_logo'))) {
      $this->data['preview'] = $this->model_tool_image->resize($this->config->get('api_store_logo'), 100, 100);
    } else {
      $this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
    }

    if (isset($this->request->post['api_images'])) {
      $api_images = $this->request->post['api_images'];
    } else {
      $api_images = $this->config->get('api_images');
    }
    if(!is_array($api_images)) $api_images = array();

    $this->data['api_images'] = array();
    foreach($api_images as $image) {
      $this->data['api_images'][] = array(
        'image'      => $image['image'],
        'thumb'      => $this->model_tool_image->resize($image['image'], 100, 100),
        'group' => $image['group']
      );
    }
    
    $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

    if (isset($this->request->post['api_encoding'])) {
      $this->data['api_encoding'] = $this->request->post['api_encoding'];
    } else {
      $this->data['api_encoding'] = $this->config->get('api_encoding');
    }

    $this->data['encodings'] = array();
    $this->data['encodings'][] = array('code' => '0', 'title' => 'Default');
    $this->data['encodings'][] = array('code' => '1', 'title' => 'Alternative 1');

    if (isset($this->request->post['api_location'])) {
      $this->data['api_location'] = $this->request->post['api_location'];
    } else {
      $this->data['api_location'] = $this->config->get('api_location');
    }
    
    if (isset($this->request->post['api_plataform_version'])) {
      $this->data['api_plataform_version'] = $this->request->post['api_plataform_version'];
    } else {
      $this->data['api_plataform_version'] = $this->config->get('api_plataform_version');
    }
    
    $this->data['plataforms'] = array();
    $this->data['plataforms'][] = array('code' => '15', 'title' => 'Versions 1.5.x');
		
		if (isset($this->request->post['api_status'])) {
			$this->data['api_status'] = $this->request->post['api_status'];
		} else {
			$this->data['api_status'] = $this->config->get('api_status');
		}
				
		$this->template = 'feed/api.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'feed/api')) {
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