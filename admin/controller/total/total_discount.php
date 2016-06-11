<?php

/**
 * Total Discount module for Opencart by Extensa Web Development
 *
 * Copyright © 2013 Extensa Web Development Ltd. All Rights Reserved.
 * This file may not be redistributed in whole or significant part.
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 * @author 		Extensa Web Development Ltd. (www.extensadev.com)
 * @copyright	Copyright (c) 2013, Extensa Web Development Ltd.
 * @package 	Total Discount module
 * @link		http://www.opencart.com/index.php?route=extension/extension/info&extension_id=14733
 */

class ControllerTotalTotalDiscount extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('total/total_discount');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('total_discount', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_each'] = $this->language->get('text_each');
		$this->data['text_once'] = $this->language->get('text_once');

		$this->data['entry_count'] = $this->language->get('entry_count');
		$this->data['entry_percent'] = $this->language->get('entry_percent');
		$this->data['entry_each'] = $this->language->get('entry_each');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

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
			'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/total_discount', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('total/total_discount', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['total_discount_count'])) {
			$this->data['total_discount_count'] = $this->request->post['total_discount_count'];
		} else {
			$this->data['total_discount_count'] = $this->config->get('total_discount_count');
		}

		if (isset($this->request->post['total_discount_percent'])) {
			$this->data['total_discount_percent'] = $this->request->post['total_discount_percent'];
		} else {
			$this->data['total_discount_percent'] = $this->config->get('total_discount_percent');
		}

		if (isset($this->request->post['total_discount_each_count'])) {
			$this->data['total_discount_each_count'] = $this->request->post['total_discount_each_count'];
		} else {
			$this->data['total_discount_each_count'] = $this->config->get('total_discount_each_count');
		}

		if (isset($this->request->post['total_discount_status'])) {
			$this->data['total_discount_status'] = $this->request->post['total_discount_status'];
		} else {
			$this->data['total_discount_status'] = $this->config->get('total_discount_status');
		}

		if (isset($this->request->post['total_discount_sort_order'])) {
			$this->data['total_discount_sort_order'] = $this->request->post['total_discount_sort_order'];
		} else {
			$this->data['total_discount_sort_order'] = $this->config->get('total_discount_sort_order');
		}

		$this->template = 'total/total_discount.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/total_discount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>