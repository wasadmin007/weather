<?php

class ModelToolProductDiscount extends Model {

	public function getTotalProducts($data = array()) {
      $join = "";
      if(isset($data['filter_category_id']) && !is_null($data['filter_category_id'])) {
        $join = "LEFT JOIN " . DB_PREFIX . "product_to_category as p2c ON (p.product_id = p2c.product_id)";
      }
		  $sql = "
			SELECT
			  count(p.product_id) as total 
			FROM 
			" . DB_PREFIX . "product p 
			LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
			".$join."
			WHERE 
				pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
			}

			if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
				$sql .= " AND LCASE(p.model) LIKE '" . $this->db->escape(mb_strtolower($data['filter_model'], 'UTF-8')) . "%'";
			}
			
			if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}

			if (isset($data['filter_category_id']) && !is_null($data['filter_category_id'])) {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		
		$query = $this->db->query($sql);
				
      return $query->row['total'];				
	}
	

	public function getProducts($data) {
      $join = "";
      if(isset($data['filter_category_id']) && !is_null($data['filter_category_id'])) {
        $join = "LEFT JOIN " . DB_PREFIX . "product_to_category as p2c ON (p.product_id = p2c.product_id)";
      }

		  $sql = "
			SELECT
				p.product_id 
				, p.image
				, p.model
				,	p.quantity
				, p.minimum
				, p.price
				, p.status
				, pd.name
			FROM 
			" . DB_PREFIX . "product p 
			LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
			".$join."
			WHERE 
				pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
			}

			if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
				$sql .= " AND LCASE(p.model) LIKE '" . $this->db->escape(mb_strtolower($data['filter_model'], 'UTF-8')) . "%'";
			}
			
			if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}

			if (isset($data['filter_category_id']) && !is_null($data['filter_category_id'])) {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			$sql .= " ORDER BY pd.name ASC";	

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
	}


	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");
		
		return $query->rows;
	}
	
	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
		
		return $query->rows;
	}
	
  public function updateProduct($product_id, $data) {
    $query = "UPDATE " . DB_PREFIX . "product set price = '".(float)$data['price']."', quantity='".(float)$data['quantity']."', minimum='".(float)$data['minimum']."' WHERE product_id = '" . (int)$product_id . "'";
    $this->db->query($query);
  }	
  
  public function updateSpecialProduct($product_id, $data) {
    $query = "INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', priority = '" . (int)$data['priority'] . "', price = '" . (float)$data['price'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "'";
    $this->db->query($query);
  }	
  public function updateDiscountProduct($product_id, $data) {
    $query = "INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', quantity = '" . (int)$data['quantity'] . "', priority = '" . (int)$data['priority'] . "', price = '" . (float)$data['price'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "'";
    $this->db->query($query);
  }	

  public function deleteSpecialProduct($product_id) {
    $query = "DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'";
    $this->db->query($query);
  }	
  public function deleteDiscountProduct($product_id) {
    $query = "DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'";
    $this->db->query($query);
  }	

}
?>