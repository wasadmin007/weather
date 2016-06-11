<?
class ModelModule3wImagesSort extends Model
{
	public $settings;
	public function is_enabled()
	{
		if ($this->config->get('3w_images_sort_status') == '1')
		{
			return true;
		}
		else 
		{
			return false;
		}
		
	}
	
	public function settings()
	{
		$sql = 'SELECT * FROM `'.DB_PREFIX.'settings` WHERE `group` = "3w_images_sort"';
		$res = $this->db->query($sql);
		foreach ($res->rows as $row)
		{
			$this->settings->{$row['key']} = $row['value'];
		}
		return $this->settings;
		
	}
	
	function install()
	{
		if ($this->verify())
		{
			return true;
		}
		$sql = 'ALTER TABLE  `'.DB_PREFIX.'product_image` ADD  `sort_order` INT( 11 ) NOT NULL';
		$this->db->query($sql);
		return true;
	}
	
	function verify()
	{
		$sql = 'SELECT * FROM `'.DB_PREFIX.'product_image` LIMIT 1';
		$res = $this->db->query($sql);
		foreach ($res->rows as $row)
		{
			if (isset($row['sort_order']))
			{
				return true;
			}
		}
		return false;
	}
	
	function uninstall()
	{
		if ($this->verify())
		{
			$sql = 'ALTER TABLE  `'.DB_PREFIX.'product_image` DROP  `sort_order`';
			$this->db->query($sql);
			return true;
		}
		return true;
	}
	
}
?>