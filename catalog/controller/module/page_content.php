<?php  
class ControllerModulePageContent extends Controller {
	protected function index($setting) {


		$startdate = $setting['startdate'][$this->config->get('config_language_id')];
		$enddate = $setting['enddate'][$this->config->get('config_language_id')];
		$this->data['message'] = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		$this->data['heading_title'] = html_entity_decode($setting['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

		$display = true;

		$todays_date = date("d-m-Y");
		$today = strtotime($todays_date);

		if($enddate && !$startdate)
		{
			$enddate = strtotime($enddate);

			if ($enddate > $today) {
				 $display = true;
			} else {
				 $display = false;
			}
		} elseif(!$enddate && $startdate) {
			$startdate = strtotime($startdate);

			if ($startdate <= $today) {
				 $display = true;
			} else {
				 $display = false;
			}
		} elseif($enddate && $startdate) {
			$startdate = strtotime($startdate);
			$enddate = strtotime($enddate);

			if (($today >= $startdate) && ($today <= $enddate)) {
				 $display = true;
			} else {
				 $display = false;
			}
		}

		if($display)
		{
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/page_content.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/page_content.tpl';
		} else {
			$this->template = 'default/template/module/page_content.tpl';
		}
		
		$this->render();
		}
	}
}
?>