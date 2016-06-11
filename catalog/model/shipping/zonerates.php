<?php

class ModelShippingZoneRates extends Model {

    public function getQuote($address) {
        $this->load->language('shipping/zonerates');

        $quote_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");
        if ($this->config->get('zonerates_calculation_method') == 'item') {
            $calculation = $this->cart->hasProducts();
        } elseif ($this->config->get('zonerates_calculation_method') == 'price') {
            $calculation = $this->cart->getTotal();
        }elseif ($this->config->get('zonerates_calculation_method') == 'weight') {
            $calculation = $this->cart->getWeight();
        }
        
        $weight = $this->cart->getWeight();
        $zoneSt = 'false';
        $allZoneSt = 'false';
        $cost = '';

        if ($query->num_rows) {
            foreach ($query->rows as $result) {
                if ($this->config->get('zonerates_' . $result['geo_zone_id'] . '_status')) {

                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $result['geo_zone_id'] . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");
                    foreach ($query->rows as $zoneresult) {
                        $zoneId = $zoneresult['zone_id'];
                    }
                    if ($query->num_rows) {
                        $status = true;
                    } else {
                        $status = false;
                    }
                } else {
                    $status = false;
                }

                if ($status) {
                    $rates = explode(',', $this->config->get('zonerates_' . $result['geo_zone_id'] . '_rate'));

                    foreach ($rates as $rate) {
                        $data = explode(':', $rate);

                        if ($data[0] >= $calculation) {
                            if (isset($data[1])) {
                                $cost = $data[1];
                            }

                            break;
                        }
                    }
                    if ((string) $cost != '' && ($zoneId == $address['zone_id']) || $zoneId == 0) {
                        $zoneSt = 'true';
                        $quote_data['zonerates_' . $result['geo_zone_id']] = array(
                            'code' => 'zonerates.zonerates_' . $result['geo_zone_id'],
                            'title' => $result['name'] . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
                            'cost' => $cost,
                            'tax_class_id' => $this->config->get('zonerates_tax_class_id'),
                            'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('zonerates_tax_class_id'), $this->config->get('config_tax')))
                        );
                    }
                }
            }
            if ((string) $cost != '' && $zoneId == 0 && $zoneSt != 'true') {
                $quote_data = array();
                $allZoneSt = 'true';
                $quote_data['zonerates_' . $result['geo_zone_id']] = array(
                    'code' => 'zonerates.zonerates_' . $result['geo_zone_id'],
                    'title' => $result['name'] . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
                    'cost' => $cost,
                    'tax_class_id' => $this->config->get('zonerates_tax_class_id'),
                    'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('zonerates_tax_class_id'), $this->config->get('config_tax')))
                );
            }
        }

        if ($this->config->get('zonerates_00_status') && $allZoneSt != 'true' && $zoneSt != 'true') {
            $rates = explode(',', $this->config->get('zonerates_00_rate'));

            foreach ($rates as $rate) {
                $data = explode(':', $rate);

                if ($data[0] >= $calculation) {
                    if (isset($data[1])) {
                        $cost = $data[1];
                    }

                    break;
                }
            }

            if ((string) $cost != '') {
                $quote_data['zonerates_00'] = array(
                    'code' => 'zonerates.zonerates_00',
                    'title' => $address['country'] . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
                    'cost' => $cost,
                    'tax_class_id' => $this->config->get('zonerates_tax_class_id'),
                    'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('zonerates_tax_class_id'), $this->config->get('config_tax')))
                );
            }
        }

        $method_data = array();

        if ($quote_data) {
            $method_data = array(
                'code' => 'zonerates',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('zonerates_sort_order'),
                'error' => false
            );
        }

        return $method_data;
    }

}

?>