<?php  
class ControllerModuleMyLiveChat extends Controller {
	protected function index() {
		$this->language->load('module/mylivechat');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['renderhead'] = $this->config->get('mylivechat_renderhead');
		$mylivechatid = $this->config->get('mylivechat_code');
		$displaytype = $this->config->get('mylivechat_displaytype');
		
		$tempstr = "<script type=\"text/javascript\" src=\"https://www.mylivechat.com/chatinline.aspx?hccid=".$mylivechatid."\"></script>";
		switch($displaytype)
		{
			case "button":
				$tempstr = "<script type=\"text/javascript\" src=\"https://www.mylivechat.com/chatbutton.aspx?hccid=".$mylivechatid."\"></script>";
				break;
			case "box":
				$tempstr = "<script type=\"text/javascript\" src=\"https://www.mylivechat.com/chatbox.aspx?hccid=".$mylivechatid."\"></script>";
				break;
			case "link":
				$tempstr = "<script type=\"text/javascript\" src=\"https://www.mylivechat.com/chatlink.aspx?hccid=".$mylivechatid."\"></script>";
				break;
			case "widget":
				$tempstr = "<script type=\"text/javascript\" src=\"https://www.mylivechat.com/chatwidget.aspx?hccid=".$mylivechatid."\"></script>";
				break;
			default:
				break;
		}
		//if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
		//	$this->data['code'] = str_replace('http:', 'https:', html_entity_decode($this->config->get('mylivechat_code')));
		//} else {
		//	$this->data['code'] = html_entity_decode($this->config->get('mylivechat_code'));
		//}
		$this->data['code'] = $tempstr;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mylivechat.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/mylivechat.tpl';
		} else {
			$this->template = 'default/template/module/mylivechat.tpl';
		}
		
		$this->render();
	}
}
?>