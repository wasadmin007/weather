<?php
class ModelCatalogFeedback extends Model {
	public function addfeedback($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "feedback SET name = '" . $this->db->escape($data['name']) . "', feedback = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	
		$this->cache->delete('product');
	}
	
	public function editfeedback($feedback_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "feedback SET name = '" . $this->db->escape($data['name']) . "', feedback = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', date_added = NOW() WHERE feedback_id = '" . (int)$feedback_id . "'");
	
		$this->cache->delete('product');
	}
	
	public function deletefeedback($feedback_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "feedback WHERE feedback_id = '" . (int)$feedback_id . "'");
		
		$this->cache->delete('product');
	}
	
	public function getfeedback($feedback_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "feedback r WHERE r.feedback_id = '" . (int)$feedback_id . "'");
		
		return $query->row;
	}

	public function getfeedbacks($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "feedback r";																																					  
		
		$sort_data = array(
			'r.status',
			'r.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.date_added";	
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
	}
	
	public function getTotalfeedbacks() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "feedback");
		
		return $query->row['total'];
	}
	
	public function getTotalfeedbacksAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "feedback WHERE status = '0'");
		
		return $query->row['total'];
	}	
}
?>