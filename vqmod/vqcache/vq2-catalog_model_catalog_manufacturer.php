<?php
class ModelCatalogManufacturer extends Model {
	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
	
		return $query->row;	
	}
	
	public function getManufacturers($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			$sort_data = array(
				'name',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}				
					
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->config->get('config_store_id'));
		
			if (!$manufacturer_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY name");
	
				$manufacturer_data = $query->rows;
			
				$this->cache->set('manufacturer.' . (int)$this->config->get('config_store_id'), $manufacturer_data);
			}

			return $manufacturer_data;
		}	
	} 
	public function getManufacturersByCategories($category_ids) {
		if (is_array($category_ids) && count($category_ids) > 0){

			$path = '';
			$parts = explode('_', (string)$this->request->get['path']);
			$category_ids = (int)array_pop($parts);
			
			$query = $this->db->query("SELECT m.manufacturer_id, m.name, COUNT(p.product_id) AS products_total FROM " . DB_PREFIX . "product_to_category pc LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = pc.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (m.manufacturer_id = p.manufacturer_id) WHERE pc.category_id IN (".$category_ids.") AND p.status = 1 AND p.quantity > -1 GROUP BY m.manufacturer_id ORDER BY m.name ASC");

			return $query->rows;
		}
		return false;
	}
	
	public function getManufacturersBySearch($search) {
		
			$query = $this->db->query("SELECT m.manufacturer_id, m.name, COUNT(p.product_id) AS products_total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON pd.product_id = p.product_id LEFT JOIN " . DB_PREFIX . "manufacturer m ON m.manufacturer_id = p.manufacturer_id WHERE pd.name LIKE '%$search%' AND p.status = 1 AND p.quantity > -1 GROUP BY m.manufacturer_id ORDER BY m.name ASC");
			
			return $query->rows;
	}
	
}
?>