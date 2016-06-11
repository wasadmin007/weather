<?php

/**
 * Sitewide (Global) Discount extension for Opencart.
 *
 * @author Anthony Lawrence <freelancer@anthonylawrence.me.uk>
 * @version 1.0
 * @copyright © Anthony Lawrence 2011
 * @license Creative Common's ShareAlike License - http://creativecommons.org/licenses/by-sa/3.0/
 */


class ControllerTotalSitewideDiscount extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('total/sitewide_discount');

		$this->document->setTitle = $this->language->get('heading_title');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('sitewide_discount', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

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
       		'text'      => $this->language->get('text_total'),
		'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'text'      => $this->language->get('heading_title'),
       		'href'      => $this->url->link('total/sidewide_discount', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=total/sitewide_discount&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token'];

		if (isset($this->request->post['sitewide_discount_total'])) {
			$this->data['sitewide_discount_total'] = $this->request->post['sitewide_discount_total'];
		} else {
			$this->data['sitewide_discount_total'] = $this->config->get('sitewide_discount_total');
		}

		if (isset($this->request->post['sitewide_discount_amount'])) {
			$this->data['sitewide_discount_amount'] = $this->request->post['sitewide_discount_amount'];
		} else {
			$this->data['sitewide_discount_amount'] = $this->config->get('sitewide_discount_amount');
		}

		if (isset($this->request->post['sitewide_discount_type'])) {
			$this->data['sitewide_discount_type'] = $this->request->post['sitewide_discount_type'];
		} else {
			$this->data['sitewide_discount_type'] = $this->config->get('sitewide_discount_type');
		}

		if (isset($this->request->post['sitewide_discount_date_start'])) {
			$this->data['sitewide_discount_date_start'] = $this->request->post['sitewide_discount_date_start'];
		} else {
			$this->data['sitewide_discount_date_start'] = $this->config->get('sitewide_discount_date_start');
		}

		if (isset($this->request->post['sitewide_discount_date_end'])) {
			$this->data['sitewide_discount_date_end'] = $this->request->post['sitewide_discount_date_end'];
		} else {
			$this->data['sitewide_discount_date_end'] = $this->config->get('sitewide_discount_date_end');
		}

		if (isset($this->request->post['sitewide_discount_status'])) {
			$this->data['sitewide_discount_status'] = $this->request->post['sitewide_discount_status'];
		} else {
			$this->data['sitewide_discount_status'] = $this->config->get('sitewide_discount_status');
		}

		if (isset($this->request->post['sitewide_discount_sort_order'])) {
			$this->data['sitewide_discount_sort_order'] = $this->request->post['sitewide_discount_sort_order'];
		} else {
			$this->data['sitewide_discount_sort_order'] = $this->config->get('sitewide_discount_sort_order');
		}

		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->template = 'total/sitewide_discount.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/sitewide_discount')) {
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