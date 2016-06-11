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

class ModelTotalTotalDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$count = 0;
		$price = 0;
		$prices = array();

		foreach ($this->cart->getProducts() as $product) {
			$count += $product['quantity'];

			for ($i = 0; $i < $product['quantity']; $i++) {
				$prices[] = $product['price'];
			}
		}

		sort($prices);

		if ($count >= (int)$this->config->get('total_discount_count')) {
			if ($this->config->get('total_discount_each_count')) {
				$items_count = floor($count / (int)$this->config->get('total_discount_count'));

				for ($i = 0; $i < $items_count; $i++) {
					$price += $prices[$i];
				}
			} else {
				$price += $prices[0];
			}

			$this->load->language('total/total_discount');

			$price *= (float)$this->config->get('total_discount_percent') / 100;

			$total_data[] = array(
				'code'       => 'total_discount',
				'title'      => $this->language->get('text_total_discount'),
				'text'       => $this->currency->format(-$price),
				'value'      => -$price,
				'sort_order' => $this->config->get('total_discount_sort_order')
			);

			$total -= $price;
		}
	}
}
?>