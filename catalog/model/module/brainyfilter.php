<?php
/**
 * Brainy Filter model class.
 * The class contains methods for calculating of MIN/MAX price, total products,
 * for retrieving list of attributes/manufacturers and for applying filters 
 * to the methods from Category module
 * 
 * BrainyFilter 2.2, April 19, 2014 / brainyfilter.com
 * Copyright 2014 Giant Leap Lab / www.giantleaplab.com
 * License: Commercial. Reselling of this software or its derivatives is not allowed. You may use this software for one website ONLY including all its subdomains if the top level domain belongs to you and all subdomains are parts of the same OpenCart store.
 * Support: support@giantleaplab.com
 */
class ModelModuleBrainyFilter extends Model 
{
	/**
	 * Separators for SEO URL
	 */
	const SEPARATOR_VAL_OPEN  =	'[';	//'{';	// '::';	// '[';
	const SEPARATOR_VAL_CLOSE =	']';	//'}';	// '';		// ']';
	const SEPARATOR_VAL       =	',';	//',';	// ',';		// ',';
	const SEPARATOR_ATTR      =	',';	//',';	// '|'		// ',';
	
	/**
	 * @var int|null top price limit
	 */
	protected $higher = null;
	/**
	 * @var int|null bottom price limit
	 */
	protected $lower  = null;
	/**
	 * @var array selected attributes and their values for filtering
	 */
	protected $attributeValue = array();
	/**
	 * @var array array of selected manufacturers
	 */
	protected $manufacturerValue = array();
	/**
	 * @var array Selected stock statuses
	 */
	protected $stockStatusValue = array();

	protected $filterValue = array();

	protected $ratingValue = array();

	protected $optionValue = array();
	/**
	 * Constructor
	 *
	 * @param array $registry 
	 */
	public function __construct($registry) {
		parent::__construct($registry);

		$this->attr_setting = $this->config->get('attr_setting');
		
		$this->_parseBFilterParam();
		if ($this->attr_setting['price_filter']) {
			if (isset($this->request->get['higher'])) {
				$this->higher = $this->request->get['higher'];
			}
			
			if (isset($this->request->get['lower'])) {
				$this->lower = $this->request->get['lower'];
			}
			
			if ($this->lower) {
				$this->lower /= $this->currency->getValue();
			}
			if ($this->higher) {
				$this->higher /= $this->currency->getValue();
			}
		}
		if (isset($this->request->get['attribute_value'])) {
			$this->attributeValue = $this->request->get['attribute_value'];
		}
		if (isset($this->request->get['manufacturer'])) {
			$this->manufacturerValue = $this->request->get['manufacturer'];
		}
		if (isset($this->request->get['stock_status'])) {
			$this->stockStatusValue = $this->request->get['stock_status'];
		}
		if (isset($this->request->get['bfrating'])) {
			$this->ratingValue = $this->request->get['bfrating'];
		}
		if (isset($this->request->get['bfoption'])) {
			$this->optionValue = $this->_findOptionIds($this->request->get['bfoption']);
		}
		if (isset($this->request->get['filter'])) {
		$this->filterValue = is_array($this->request->get['filter']) ? $this->request->get['filter'] : explode(',', (string)$this->request->get['filter']);;
		}
		
		$this->attributeValue = $this->_cleanAttrArray($this->attributeValue);
	}

    /**
     * Clean attributeValue array
     * 
     * @param array $params Array of parameters
     * @return array
     */
	private function _cleanAttrArray($params) {
        if (!is_array($params)) {
        
            return array();
        }

        foreach ($params as $k => $v) {
            if (is_array($v)) {
                $params[$k] = $this->_cleanAttrArray($v);
                if (!count($params[$k])) {
                    unset($params[$k]);
                }
            } else {
                if ($v === '' || $v === null) {
                    unset($params[$k]);
                }
            }
        }

        return $params;
	}
    
    /**
     * Get MIN/MAX category price
	 * <br />
     * The method calculates min/max price taking into account special offers, 
     * discounts and taxes
     * 
     * @param array $data [OPTIONAL] input parameters
     * @return array Associative array with min and max fields
     */
	public function getMinMaxCategoryPrice($data = array()) 
 	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "	SELECT p.product_id, p.price, " . $this->_columnsTax() . $this->_columnsPrice();
		
		if (!empty($filter->rating)) {
			$sql .= ',' . $this->_columnRating();
		}

		$sql .= "	FROM " . DB_PREFIX . "product p";
		
		if (!empty($data['filter_filter'])) {
				$sql .= "	LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id)";
			}
		
		$sql .= "	LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
					LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)"
				. $filter->filterJoin
				. $filter->optionJoin
				. $filter->join . 
				"	WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
						AND p.status = '1' 
						AND p.date_available <= NOW() 
						AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($filter->terms)) {
			$sql .= ' AND ' . $filter->terms;
		}
		
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$sql2 = "	SELECT MAX(including_tax) AS max, MIN(including_tax) AS min
					FROM (
						SELECT *, ";
		
		if ($this->config->get('config_tax')) {
			$sql2 .= "( IF(special IS NOT NULL, special, IF(discount IS NOT NULL, discount, price)) * (1 + IFNULL(percent_tax, 0)/100) + IFNULL(fixed_tax, 0)) AS including_tax";
		} else {
			$sql2 .= " IF(special IS NOT NULL, special, IF(discount IS NOT NULL, discount, price)) AS including_tax";
		}
		$sql2 .= " FROM (" . $sql . ") AS sub1
						) AS sub2";
		
		if (!empty($filter->rating)) {
			$sql2 .= ' WHERE ' . $filter->rating;
		}
		
		$res = $this->db->query($sql2);
		
		return $res->row;
		
  	}
	
    /**
     * Prepare Query String For Category
	 * <br />
     * The method applies BrainyFilter conditions to the query for products.
     * It is injected to the ModelCatalogProduct::getProducts() via vQmod
     * 
     * @param string $sql SQL query string from ModelCatalogProduct::getProducts() method
     * @return string
     */
   
	public function prepareQueryForCategory($sql) 
	{
		$filter = $this->_prepareFilter();
		preg_match("/(^SELECT)\s(DISTINCT\s)?(SQL_CALC_FOUND_ROWS\s)?(.*)$/",$sql, $matches); //compatibility with ocstore
		$sql = preg_replace("/(^SELECT\s)(DISTINCT\s)?(SQL_CALC_FOUND_ROWS p.product_id,\s)?(.*)$/", "$1$2$3 " . $this->_columnsTax() . " p.price, $4", $sql);
		// add filter terms
		if (!empty($filter->terms) || !empty($filter->agregatedTerms)) {
			
			$lPos = strrpos($sql, 'LIMIT');
			$wPos = strrpos($sql, 'WHERE');
			if ($lPos !== false && $wPos && $lPos > $wPos) {
				$limit = ($lPos !== false) ? substr($sql, $lPos) : '';
				// remove LIMIT part (it will be moved to the end)
				$sql = substr($sql, 0, $lPos - 1);
			} else {
				$limit = '';
			}
			
			if (!empty($filter->terms)) {
				$sql = preg_replace('/(WHERE )(pd\.language_id)/', $filter->join . $filter->optionJoin . ' WHERE ' . $filter->terms . " AND $2", $sql);
			}
			// add terms for ocstore
			if (!empty($matches[3])) {
				$sql = preg_replace("/(DISTINCT\s)?(SQL_CALC_FOUND_ROWS\s)?/", "", $sql);

			}
			if (!empty($filter->agregatedTerms)) {
				$sql = "SELECT " . $matches[2] . $matches[3] . " * FROM (" . $sql . ") AS tt WHERE " . $filter->agregatedTerms . $limit;
			} else {
				$sql = "SELECT " . $matches[2] . $matches[3] . " * FROM (" . $sql . ") AS tt " . $limit;
			}
		}
		
		return $sql;
	}

	/**
     * Prepare Query For Total
	 * <br />
     * Applying of BRainyFilter conditions onto query for calculating of total products
     * 
     * @param string $sql SQL query string from ModelCatalogProduct::getTotalProducts()
     * @return string
     */
	public function prepareQueryForTotal($sql)
	{
		$filter = $this->_prepareFilter();
		
		$select  = ($filter->price) ? ', ' . $this->_columnsTax() . $this->_columnsPrice() : '';
		$select .= ($filter->rating) ? ', ' . $this->_columnRating() : '';
		
		$sql = str_replace('COUNT(DISTINCT p.product_id) AS total', ' p.price, p.product_id' . $select, $sql);

		if (!empty($filter->terms)) {
			$sql = preg_replace('/(WHERE )(pd\.language_id)/', $filter->optionJoin . $filter->join . ' WHERE ' . $filter->terms . " AND $2", $sql);
		}
			
		if (!empty($filter->agregatedTerms)) {
			$sql = "SELECT COUNT(DISTINCT tt.product_id) AS total FROM (" . $sql . ") AS tt WHERE " . $filter->agregatedTerms;
		} else {
			$sql = "SELECT COUNT(DISTINCT tt.product_id) AS total FROM (" . $sql . ") AS tt ";
		}
//		echo $sql;
		return $sql;
	}
	
    /**
     * Get Attributes
     * 
     * @param array $data input parameters
     * @return array Returns array of existed attributes in the given category 
     * and  all their values
     */
  	public function getAttributes($data = array()) 
  	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "SELECT pa.attribute_id, pa.text, ad.name, agd.name AS group_name, agd.attribute_group_id
				FROM " . DB_PREFIX . "product p
				INNER JOIN " . DB_PREFIX . "product_attribute pa ON ( p.product_id = pa.product_id ) 
				{$filter->categoryJoin}
				INNER JOIN " . DB_PREFIX . "product_to_store p2s ON ( p.product_id = p2s.product_id ) 
				INNER JOIN " . DB_PREFIX . "attribute_description ad ON ( ad.attribute_id = pa.attribute_id ) 
				INNER JOIN " . DB_PREFIX . "attribute a ON ( a.attribute_id = pa.attribute_id ) 
				INNER JOIN " . DB_PREFIX . "attribute_group ag ON ( a.attribute_group_id = ag.attribute_group_id ) 
				INNER JOIN " . DB_PREFIX . "attribute_group_description agd ON ( a.attribute_group_id = agd.attribute_group_id ) 
				WHERE pa.language_id = " . (int)$this->config->get('config_language_id') . "
					AND ad.language_id = " . (int)$this->config->get('config_language_id') . "
					AND agd.language_id = " . (int)$this->config->get('config_language_id') . "
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
					AND p.status = 1
					AND p.date_available <= NOW( ) 
				GROUP BY pa.text, pa.attribute_id
				ORDER BY ag.sort_order, agd.name, a.sort_order, ad.name, pa.text ";
		
		$query = $this->db->query($sql);
		
		$output = array();
		
	  	foreach ($query->rows as $row) {
			if (isset($this->attr_setting['expanded_attribute_'.$row['attribute_id']])) {
	  		
	  		
			if (!isset($output[$row['attribute_group_id']])) {
				$output[$row['attribute_group_id']] = array(
					'name'       => $row['group_name'],
					'attributes' => array()
				);
			}
			if (!isset($output[$row['attribute_group_id']]['attributes'][$row['attribute_id']])) {
				$output[$row['attribute_group_id']]['attributes'][$row['attribute_id']] = array(
					'name'   => $row['name'],
					'values' => array()
				);
				}
			
			$output[$row['attribute_group_id']]['attributes'][$row['attribute_id']]['values'][] = $row['text'];
			}
		}
		
		return $output;
  	}

    /**
     * Get Total By Attribute
	 * <br />
     * Calculates totals by attribute taking into account all conditions
     * 
     * @param array $data Input parameters
     * @return array Array of totals by attribute/manufacturer
     */
  	public function getTotalByAttributes($data = array()) 
	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "	SELECT a.attribute_id AS id, pa.text AS val, p.product_id, p.price";
		
		if ($filter->price) {
			$sql .= ',' . $this->_columnsTax() . $this->_columnsPrice();
		}
		if ($filter->rating) {
			$sql .= ',' . $this->_columnRating();
		}
		
		$sql .= "	FROM  " . DB_PREFIX . "product_attribute pa
					INNER JOIN " . DB_PREFIX . "attribute a ON ( pa.attribute_id = a.attribute_id ) 
					INNER JOIN " . DB_PREFIX . "attribute_description ad ON ( a.attribute_id = ad.attribute_id ) 
					INNER JOIN " . DB_PREFIX . "attribute_group ag ON ( ag.attribute_group_id = a.attribute_group_id ) 
					INNER JOIN " . DB_PREFIX . "attribute_group_description agd ON ( agd.attribute_group_id = ag.attribute_group_id ) 
					INNER JOIN " . DB_PREFIX . "product p ON ( p.product_id = pa.product_id ) 
					INNER JOIN " . DB_PREFIX . "product_to_store p2s ON ( p.product_id = p2s.product_id ) " 
				. $filter->optionJoin
				. $filter->filterJoin
				. $filter->join ;

		$sql .= "WHERE p.status =  '1'
					AND p.date_available <= NOW( ) 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
					AND pa.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					AND ad.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					AND agd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		
		if (!empty($filter->totalAttrTerms)) {
			$sql .= " AND " . $filter->totalAttrTerms;
		}
		if (!empty($filter->mnTerms)) {
			$sql .= " AND " . $filter->mnTerms;
		}
		if (!empty($filter->filter)) {
			$sql .= " AND " . $filter->filter;
		}
		if (!empty($filter->option)) {
			$sql .= " AND " . $filter->option;
		}
		if (!empty($filter->stockTerms)) {
			$sql .= " AND " . $filter->stockTerms;
		}

		$sql = "SELECT id, val, COUNT(DISTINCT tt.product_id, id, val) AS c FROM (" . $sql . ") AS tt ";
		
		if (!empty($filter->agregatedTerms)) {
			$sql .= " WHERE " . $filter->agregatedTerms;
		}
		$sql .= " GROUP BY val, id";

		// calculate totals for attributes
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalByManufacturer($data = array()) 
	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "	SELECT p.product_id, p.manufacturer_id, p.price ";
		
		if ($filter->price) {
			$sql .= ',' . $this->_columnsTax() . $this->_columnsPrice();
		}
		if ($filter->rating) {
			$sql .= ',' . $this->_columnRating();
		}
		
		$sql .= "	FROM  " . DB_PREFIX . "product p
					INNER JOIN " . DB_PREFIX . "product_to_store p2s ON ( p.product_id = p2s.product_id ) " 
				  . $filter->optionJoin
				  . $filter->filterJoin
				  . $filter->join ;
		
		if ($filter->attrTerms) {
			$sql .= "LEFT JOIN " . DB_PREFIX . "product_attribute pa ON ( p.product_id = pa.product_id )
					 INNER JOIN " . DB_PREFIX . "attribute a ON ( pa.attribute_id = a.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_description ad ON ( a.attribute_id = ad.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group ag ON ( ag.attribute_group_id = a.attribute_group_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group_description agd ON ( agd.attribute_group_id = ag.attribute_group_id )";
		}

		$sql .= " WHERE p.status =  '1'
					AND p.date_available <= NOW( ) 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
					
		if (!empty($filter->option)) {
			$sql .= " AND " . $filter->option;
		}

		if (!empty($filter->filter)) {
			$sql .= " AND " . $filter->filter;
		}
		if ($filter->attrTerms) {
			$sql .= " AND pa.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND ad.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND agd.language_id = '" . (int) $this->config->get('config_language_id') . "'
					  AND " . $filter->attrTerms;
		}
		if ($filter->stockTerms) {
			$sql .= ' AND ' . $filter->stockTerms;
		}

		$sql = "SELECT CONCAT('mn', manufacturer_id) AS id, manufacturer_id AS val, COUNT(DISTINCT tt.product_id, manufacturer_id) AS c FROM (" . $sql . ") AS tt ";
		
		if (!empty($filter->agregatedTerms)) {
			$sql .= " WHERE " . $filter->agregatedTerms;
		}
		$sql .= " GROUP BY manufacturer_id";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalByStockStatus($data = array()) 
	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "	SELECT p.product_id, p.price, IF (p.quantity > 0, '".$this->attr_setting['stock_status_id']."', p.stock_status_id) as stock_status_id ";
		
		if ($filter->price) {
			$sql .= ',' . $this->_columnsTax() . $this->_columnsPrice();
		}
		if ($filter->rating) {
			$sql .= ',' . $this->_columnRating();
		}
		
		$sql .= "	FROM  " . DB_PREFIX . "product p
					INNER JOIN " . DB_PREFIX . "product_to_store p2s ON ( p.product_id = p2s.product_id ) " 
				  . $filter->optionJoin
				  . $filter->filterJoin
				  . $filter->join ;
		
		if ($filter->attrTerms) {
			$sql .= "LEFT JOIN " . DB_PREFIX . "product_attribute pa ON ( p.product_id = pa.product_id )
					 INNER JOIN " . DB_PREFIX . "attribute a ON ( pa.attribute_id = a.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_description ad ON ( a.attribute_id = ad.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group ag ON ( ag.attribute_group_id = a.attribute_group_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group_description agd ON ( agd.attribute_group_id = ag.attribute_group_id )";
		}

		$sql .= " WHERE p.status =  '1'
					AND p.date_available <= NOW( ) 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($filter->option)) {
			$sql .= " AND " . $filter->option;
		}			
		if (!empty($filter->filter)) {
			$sql .= " AND " . $filter->filter;
		}
		if ($filter->attrTerms) {
			$sql .= " AND pa.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND ad.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND agd.language_id = '" . (int) $this->config->get('config_language_id') . "'
					  AND " . $filter->attrTerms;
		}
		if ($filter->mnTerms) {
			$sql .= ' AND ' . $filter->mnTerms;
		}

		$sql = "SELECT CONCAT('s', stock_status_id) AS id, stock_status_id AS val, COUNT(DISTINCT tt.product_id, stock_status_id) AS c FROM (" . $sql . ") AS tt ";
		
		if (!empty($filter->agregatedTerms)) {
			$sql .= " WHERE " . $filter->agregatedTerms;
		}
		$sql .= " GROUP BY stock_status_id";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	/**
	 * Get Total By Filters
	 * <br />product_option_value
	 * Returns associative array of data which contains total amount of products
	 * for each filter value.<br />
	 * Note: the methods calculates totals only for non-selected filters. For 
	 * selected filters totals are set to 1 for compability with client-side
	 *
	 * @param array $data Input data
	 * @return array
	 */
	public function getTotalByFilter($data = array()) 
	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "	SELECT pf.filter_id as id, p.product_id, p.price ";
		
		if ($filter->price) {
			$sql .= ',' . $this->_columnsTax() . $this->_columnsPrice();
		}
		if ($filter->rating) {
			$sql .= ',' . $this->_columnRating();
		}
		
		$sql .= "	FROM  " . DB_PREFIX . "product p
					INNER JOIN " . DB_PREFIX . "product_to_store p2s ON ( p.product_id = p2s.product_id )
					INNER JOIN " . DB_PREFIX . "product_filter AS pf ON ( p.product_id = pf.product_id ) "
				  . $filter->optionJoin
				  . $filter->join ;
		
		if ($filter->attrTerms) {
			$sql .= "LEFT JOIN " . DB_PREFIX . "product_attribute pa ON ( p.product_id = pa.product_id )
					 INNER JOIN " . DB_PREFIX . "attribute a ON ( pa.attribute_id = a.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_description ad ON ( a.attribute_id = ad.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group ag ON ( ag.attribute_group_id = a.attribute_group_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group_description agd ON ( agd.attribute_group_id = ag.attribute_group_id )";
		}
		
		if ($this->filterValue) {
			$sql .= "	LEFT JOIN (
							SELECT DISTINCT product_id
							FROM " . DB_PREFIX . "product_filter
							WHERE filter_id IN (" . implode(',', $this->filterValue) . ")
					   ) AS exclude ON (exclude.product_id = p.product_id) ";
		}
			
		$sql .= " WHERE p.status =  '1'
					AND p.date_available <= NOW( ) 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
					
		if ($filter->mnTerms) {
			$sql .= ' AND ' . $filter->mnTerms;
		}
		if ($filter->attrTerms) {
			$sql .= " AND pa.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND ad.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND agd.language_id = '" . (int) $this->config->get('config_language_id') . "'
					  AND " . $filter->attrTerms;
		}

		if ($filter->stockTerms) {
			$sql .= ' AND ' . $filter->stockTerms;
		}
		if ($this->filterValue) {
			$sql .= ' AND exclude.product_id IS NULL ';
		}
		if (!empty($filter->option)) {
			$sql .= " AND " . $filter->option;
		}
		
		$sql = "SELECT CONCAT('f', tt.id) AS id, tt.id AS val, COUNT(DISTINCT tt.product_id, tt.id) AS c FROM (" . $sql . ") AS tt ";
		
		if (!empty($filter->agregatedTerms)) {
			$sql .= " WHERE " . $filter->agregatedTerms;
		}
		$sql .= " GROUP BY id";
		
		$query = $this->db->query($sql);
		
		// add selected filters, so they won't be disabled
		if ($this->filterValue) {
			foreach ($this->filterValue as $filterId) {
				$query->rows[] = array('id' => 'f' . $filterId, 'val' => $filterId, 'c' => 1);
			}
		}
		
		return $query->rows;
	}

	public function getTotalByOption($data = array()) 
	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "	SELECT pov.option_value_id AS val, pov.option_id AS id, p.product_id, p.price ";
		
		if ($filter->price) {
			$sql .= ',' . $this->_columnsTax() . $this->_columnsPrice();
		}
		if ($filter->rating) {
			$sql .= ',' . $this->_columnRating();
		}
		
		$sql .= "	FROM  " . DB_PREFIX . "product p
					INNER JOIN " . DB_PREFIX . "product_to_store p2s ON ( p.product_id = p2s.product_id )
					INNER JOIN " . DB_PREFIX . "product_option_value AS pov ON ( p.product_id = pov.product_id ) "
				  . $filter->filterJoin
				  . $filter->join 
                  . $filter->optionJoin;
		
		if ($filter->attrTerms) {
			$sql .= "LEFT JOIN " . DB_PREFIX . "product_attribute pa ON ( p.product_id = pa.product_id )
					 INNER JOIN " . DB_PREFIX . "attribute a ON ( pa.attribute_id = a.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_description ad ON ( a.attribute_id = ad.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group ag ON ( ag.attribute_group_id = a.attribute_group_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group_description agd ON ( agd.attribute_group_id = ag.attribute_group_id )";
		}
		
        $optVals = array();
        $optIds  = array();
        if ($this->optionValue) {
            foreach ($this->optionValue as $optId => $vals) {
                $optVals = array_merge($optVals, $vals);
                $optIds[] = $optId;
            }
            $sql .= "   LEFT JOIN ("
                    . "     SELECT DISTINCT pov.product_id"
                    . "     FROM " . DB_PREFIX . "product_option_value AS pov "
                    . "     INNER JOIN " . DB_PREFIX . "product AS p ON (p.product_id = pov.product_id) "
                    . $filter->optionJoin
                    . "     WHERE " . $filter->option
                    . " ) AS exclude ON (exclude.product_id = p.product_id)";
        }
		
		$sql .= " WHERE p.status =  '1'
					AND p.date_available <= NOW( ) 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
					
		if ($filter->mnTerms) {
			$sql .= ' AND ' . $filter->mnTerms;
		}
		if ($filter->attrTerms) {
			$sql .= " AND pa.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND ad.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND agd.language_id = '" . (int) $this->config->get('config_language_id') . "'
					  AND " . $filter->attrTerms;
		}

		if ($filter->stockTerms) {
			$sql .= ' AND ' . $filter->stockTerms;
		}
		
        if ($this->optionValue) {
            $sql .= 'AND ( ( pov.option_id IN (' . implode(',', $optIds) . ') AND exclude.product_id IS NULL ) '
                    . ' OR ( pov.option_id NOT IN (' . implode(',', $optIds) . ') ) )';
            
            foreach ($this->optionValue as $optId => $opt) {
                $terms = array();
                foreach ($opt as $val) {
                    $terms[] = "pov{$optId}.option_value_id =  '" . $this->db->escape($val) . "'";
                }
                $sql .= " AND pov{$optId}.option_id = '{$optId}' "
                      . " AND ( ((" . implode(' OR ', $terms) . ") AND pov.option_value_id != pov{$optId}.option_value_id) OR (pov.option_value_id = pov{$optId}.option_value_id) )";
            }
		}
        
		if (!empty($filter->filter)) {
			$sql .= " AND " . $filter->filter;
		}

		
		$sql = "SELECT CONCAT('o', tt.id) AS id, tt.val, COUNT(DISTINCT tt.product_id, tt.id, tt.val) AS c FROM (" . $sql . ") AS tt ";
		
		if (!empty($filter->agregatedTerms)) {
			$sql .= " WHERE " . $filter->agregatedTerms;
		}
		$sql .= " GROUP BY id, val";
		
		$query = $this->db->query($sql);
		
        // add selected filters, so they won't be disabled
		if ($this->optionValue) {
			foreach ($this->optionValue as $optId => $opt) {
                foreach ($opt as $val) {
                    $query->rows[] = array('id' => 'o' . $optId, 'val' => $val, 'c' => 1);
                }
			}
		}
        
		return $query->rows;
	}

	public function getTotalByRating($data = array()) 
	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "	SELECT p.product_id, p.price, " . $this->_columnRating();
		
		if ($filter->price) {
			$sql .= ',' . $this->_columnsTax() . $this->_columnsPrice();
		}
		
		$sql .= "	FROM  " . DB_PREFIX . "product p
					INNER JOIN " . DB_PREFIX . "product_to_store p2s ON ( p.product_id = p2s.product_id )" 
				  . $filter->optionJoin
				  . $filter->filterJoin
				  . $filter->join ;
		
		if (!count($this->ratingValue)) {
			$sql .= " INNER JOIN " . DB_PREFIX . "review AS r ON ( p.product_id = r.product_id ) ";
		}
		
		if ($filter->attrTerms) {
			$sql .= "LEFT JOIN " . DB_PREFIX . "product_attribute pa ON ( p.product_id = pa.product_id )
					 INNER JOIN " . DB_PREFIX . "attribute a ON ( pa.attribute_id = a.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_description ad ON ( a.attribute_id = ad.attribute_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group ag ON ( ag.attribute_group_id = a.attribute_group_id ) 
					 INNER JOIN " . DB_PREFIX . "attribute_group_description agd ON ( agd.attribute_group_id = ag.attribute_group_id )";
		}

		$sql .= " WHERE p.status =  '1'
					AND p.date_available <= NOW( ) 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
					
		if ($filter->mnTerms) {
			$sql .= ' AND ' . $filter->mnTerms;
		}
		if ($filter->attrTerms) {
			$sql .= " AND pa.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND ad.language_id =  '" . (int) $this->config->get('config_language_id') . "'
					  AND agd.language_id = '" . (int) $this->config->get('config_language_id') . "'
					  AND " . $filter->attrTerms;
		}

		if ($filter->stockTerms) {
			$sql .= ' AND ' . $filter->stockTerms;
		}
		if (!empty($filter->option)) {
			$sql .= " AND " . $filter->option;
		}

		if (!empty($filter->filter)) {
			$sql .= " AND " . $filter->filter;
		}

		$sql = "SELECT CONCAT('r', tt.rating) AS id, tt.rating AS val, COUNT(DISTINCT tt.product_id, tt.rating) AS c FROM (" . $sql . ") AS tt ";
		
		if (!empty($filter->price)) {
			$sql .= " WHERE " . $filter->price;
		}
		$sql .= " GROUP BY id";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
    /**
     * Parse BrainyFilter Param
	 * <br />
     * The method converts SEO modificated bfilter GET parameter to 
     * the attribute_value/lower/higher/manufacturer/stock_status/rating GET parameters
     * 
     * @return void
     */
	private function _parseBFilterParam()
	{
		if (!isset($this->request->get['bfilter']) || empty($this->request->get['bfilter'])) 
		{
			return;
		}
		
		$bfilter = $this->request->get['bfilter'];
		$bfilter = preg_replace('/\\' . self::SEPARATOR_VAL_CLOSE . '$/', '', $bfilter);

		$params = explode(self::SEPARATOR_VAL_CLOSE . self::SEPARATOR_ATTR, $bfilter);
		foreach ($params as $param) {
			$p = explode(self::SEPARATOR_VAL_OPEN, $param);
			$paramName = htmlspecialchars_decode($p[0]);
			$paramVals = htmlspecialchars_decode($p[1]);
			$values    = explode(self::SEPARATOR_VAL, $paramVals);
			
			if ($paramName == 'price') {
				$pValues = explode('-', $paramVals);
				if ((int)$pValues[0] > 0) {
					$this->request->get['lower']  = $pValues[0];
				}
				if ((int)$pValues[1] > 0) {
					$this->request->get['higher'] = $pValues[1];
				}
			} elseif ($paramName == 'brand') {
				$this->request->get['manufacturer'] = $values;
			} elseif ($paramName == 'status') {
				$this->request->get['stock_status'] = $values;
			} elseif ($paramName == 'rating') {
				$this->request->get['bfrating'] = $values;
			} elseif ($paramName == 'option') {
				$this->request->get['bfoption'] = $values;
			} else {
				$p  = explode('-', $paramName);
				$id = (int)$p[0];

				if (count($values) && $id > 0) {
					$this->request->get['attribute_value'][$id] = $values;
				}
			}
		}
	}

    /**
     * Get Manufacturers
	 * <br />
     * Retrieve list of manufacturers for the given category ID
     * 
     * @param array $data Input parameters
     * @return mixed Array of manufacturers for the given category ID if found. 
     * Otherwise returns FALSE
     */
	public function getManufacturers($data = array())
	{
		$filter = $this->_prepareFilter($data);
		
		$sql = "SELECT m.manufacturer_id, m.name FROM " . DB_PREFIX . "product p
				INNER JOIN " . DB_PREFIX . "manufacturer m on (m.manufacturer_id = p.manufacturer_id)";
		
		$sql .= $filter->categoryJoin;
		
		$sql .= ' WHERE p.status = 1
				  GROUP BY m.manufacturer_id 
				  ORDER BY m.sort_order, m.name ASC';

		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getStockStatuses($data = array())
	{
		$sql = "	SELECT stock_status_id, name 
					FROM " . DB_PREFIX . "stock_status
					WHERE language_id = " . (int) $this->config->get('config_language_id');
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	public function getOptions($data = array())
	{
		$output = array();
        $filter = $this->_prepareFilter($data);
       
        	$imageColumn = ($this->attr_setting['image_and_label']) ? ", ov.image" : '';

        $sql =  "   SELECT od.name as namegroup, ovd.name, ovd.option_value_id, pov.option_id, o.type" . $imageColumn; 
        $sql .= "   FROM oc_product AS p
                    INNER JOIN " . DB_PREFIX . "product_to_store p2s ON ( p.product_id = p2s.product_id ) 
                    INNER JOIN " . DB_PREFIX . "product_option_value AS pov ON (p.product_id = pov.product_id) 
                    INNER JOIN " . DB_PREFIX . "option_description AS od ON (pov.option_id = od.option_id) 
                    INNER JOIN " . DB_PREFIX . "option_value_description AS ovd ON (pov.option_value_id = ovd.option_value_id)
                    INNER JOIN " . DB_PREFIX . "option AS o ON (pov.option_id = o.option_id)";
        
	    $sql .= " INNER JOIN " . DB_PREFIX . "option_value as ov ON (pov.option_value_id = ov.option_value_id)";
        
        
        $sql .= $filter->categoryJoin;
        
		$sql .= "	WHERE ovd.language_id = " . (int)$this->config->get('config_language_id') . "
                        AND od.language_id = " . (int)$this->config->get('config_language_id') . "
                        AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
                        AND p.status = 1
                        AND p.date_available <= NOW( ) ";
        
		$sql .= " GROUP BY pov.option_value_id";
		$sql .= " ORDER BY ov.sort_order, o.sort_order ASC";
		$query = $this->db->query($sql);

		foreach ($query->rows as $row) {

            $r = array(
                'name' => $row['name'],
                'id' => $row['option_value_id']
            );
            
            if (isset($row['image'])) {
                $r['image'] = $row['image'];
            }
            if (!isset($output[$row['option_id']])) {
                $output[$row['option_id']] = array(
                    'name' => $row['namegroup'],
                    'type' => $row['type'],
                    'values' => array()
                );
            }
            $output[$row['option_id']]['values'][] = $r;
        }

        return $output;
	}
	
    /**
     * Prepare Filter
	 * <br />
     * The method contains common terms for filtering, which are included in sql queries
     * above
     * 
     * @return stdClass Object which holds necessary sql query parts:
     * <ul>
     * <li><b>join</b> - additional table joins
	 * <li><b>categoryJoin</b> - additional table joins for filter by category
	 * <li><b>filterJoin</b> - join of product_filter table
     * <li><b>totalAttrTerms</b> - terms for filtering by attributes (for getTotalByAttributes method)
     * <li><b>attrTerms</b> - terms for filtering by attributes
     * <li><b>mnTerms</b> - terms for filtering by manufacturer
	 * <li><b>stockTerms</b> - terms for filtering by stock status</li>
	 * <li><b>filter</b> - terms for filtering by Opencart standart filter</li>
	 * <li><b>rating</b> - terms for filtering by rating</li>
     * <li><b>terms</b> - common terms for filtering by attributes and manufacturer
     * <li><b>categoryJoin</b> - join tables for filtering by category (sub-category)
     * <li><b>price</b> - terms for filtering by price
     * </ul>
     */
	private function _prepareFilter($data = array())
	{	
		$aterms  = '';
		$atterms = '';
		$join = '';
		if (count($this->attributeValue)) {
			foreach ($this->attributeValue as $k => $v) {
				$k = (int)$k;
				$join .= "INNER JOIN " . DB_PREFIX . "product_attribute AS pa{$k} ON ( p.product_id = pa{$k}.product_id ) ";
				$tArr = array();
                foreach ($v as $val) {
                    $tArr[] = "pa{$k}.text =  '" . $this->db->escape($val) . "'";
                }
                $atterms .= (!empty($aterms) ? ' AND ' : '') 
						. " pa{$k}.attribute_id = '{$k}' AND (
								((" . implode(' OR ', $tArr) . ") AND pa.attribute_id != pa{$k}.attribute_id) 
								OR (pa.attribute_id = pa{$k}.attribute_id)
							)";
				
				$aterms .= (!empty($aterms) ? ' AND ' : '') . " pa{$k}.attribute_id = '{$k}' AND (" . implode(' OR ', $tArr) . ")";
			}
		}
		
		$mterms = '';
		if (count($this->manufacturerValue)) {
			$mtermsArr = array();
			foreach ($this->manufacturerValue as $id) {
				$mtermsArr[] = "p.manufacturer_id = '" . $this->db->escape($id) ."'";
			}
			$mterms = '(' . implode(' OR ', $mtermsArr) . ')';
		}
		
		$stockTerms = '';
		if (count($this->stockStatusValue)) {
			$stockTermsArr = array();
			foreach ($this->stockStatusValue as $id) {
				$stockTermsArr[] = "p.stock_status_id = '" . $this->db->escape($id) . "'";
			}
			$stockTerms = '(' . implode(' OR ', $stockTermsArr) . ')';
		}

		// standard filter
		$filterJoin = '';
		$filter = '';
		if ($this->attr_setting['opencart_filters'] && count($this->filterValue)) {
			$filterJoin = "INNER JOIN " . DB_PREFIX . "product_filter AS pf ON ( p.product_id = pf.product_id ) ";
		}
		if (count($this->filterValue)) {
			$filterArr = array();
			foreach ($this->filterValue as $id) {
				$filterArr[] = "pf.filter_id = '" . $this->db->escape($id) . "'";
			}
			$filter = '(' . implode(' OR ', $filterArr) . ')';
		}

		$optionJoin = '';
		$option = '';
        
        $optTerms = '';
		if (count($this->optionValue)) {
			foreach ($this->optionValue as $optId => $vals) {
				$optId = (int)$optId;
				$optionJoin .= "INNER JOIN " . DB_PREFIX . "product_option_value AS pov{$optId} ON ( p.product_id = pov{$optId}.product_id ) ";
				$tArr = array();
				foreach ($vals as $val) {
					$tArr[] = "pov{$optId}.option_value_id =  '" . $this->db->escape($val) . "'";
				}
				
				$optTerms .= (!empty($optTerms) ? ' AND ' : '') . " pov{$optId}.option_id = '{$optId}' AND (" . implode(' OR ', $tArr) . ")";
			}
		}

		$ratingTerms = '';
		if (count($this->ratingValue)) {
			$ratingArr = array();
			foreach ($this->ratingValue as $id) {
				$ratingArr[] = "ROUND(rating) = '" . $this->db->escape($id) . "'";
			}
			$ratingTerms = '(' . implode(' OR ', $ratingArr) . ')';
		}

		$commonTerms  = (!empty($aterms)) ? $aterms : '';
		$commonTerms .= (!empty($commonTerms) && !empty($mterms)) ? ' AND ' . $mterms : $mterms;
		$commonTerms .= (!empty($commonTerms) && !empty($stockTerms)) ? ' AND ' . $stockTerms : $stockTerms;
		$commonTerms .= (!empty($commonTerms) && !empty($filter)) ? ' AND ' . $filter : $filter;
		$commonTerms .= (!empty($commonTerms) && !empty($optTerms)) ? ' AND ' . $optTerms : $optTerms;
		
		$priceTerms = '';
		if (isset($this->lower)) {
			$lower = $this->db->escape($this->lower);
			if ($this->config->get('config_tax')) {
				$priceTerms .= "IF(special IS NOT NULL, special, IF(discount IS NOT NULL, discount, price)) * (1 + IFNULL(percent_tax, 0)/100) + IFNULL(fixed_tax, 0) >= '{$lower}'";
			} else {
				$priceTerms .= "IF(special IS NOT NULL, special, IF(discount IS NOT NULL, discount, price)) >= '{$lower}'";
			}
		}
		
		if (isset($this->higher)) {
			$higher = $this->db->escape($this->higher);
			if (!empty($priceTerms)) {
				$priceTerms .= ' AND ';
			}
			if ($this->config->get('config_tax')) {
				$priceTerms .= "IF(special IS NOT NULL, special, IF(discount IS NOT NULL, discount, price)) * (1 + IFNULL(percent_tax, 0)/100) + IFNULL(fixed_tax, 0) <= '{$higher}'";
			} else {
				$priceTerms .= "IF(special IS NOT NULL, special, IF(discount IS NOT NULL, discount, price)) <= '{$higher}'";
			}
		}
		
		// filter by category
		$categoryJoin = '';
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$category = "	SELECT p2c1.category_id, product_id FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c1 ON (cp.category_id = p2c1.category_id) WHERE cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$category = "	SELECT category_id, product_id FROM " .  DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$data['filter_category_id'] . "'";
			}
			$categoryJoin = " INNER JOIN ( {$category} ) p2c ON(p.product_id=p2c.product_id) ";
		}

		$agregatedTerms = (!empty($priceTerms) && !empty($ratingTerms)) 
				? $priceTerms . ' AND ' . $ratingTerms 
				: $priceTerms . $ratingTerms;

		
		$res = new stdClass();
		$res->join           = $join . $categoryJoin;
		$res->categoryJoin   = $categoryJoin;
		$res->filterJoin     = $filterJoin;
		$res->optionJoin     = $optionJoin;
		$res->attrTerms      = $aterms;
		$res->totalAttrTerms = $atterms;
		$res->mnTerms        = $mterms;
		$res->stockTerms     = $stockTerms;
		$res->filter     	 = $filter;
		$res->option     	 = $optTerms;
		$res->rating     	 = $ratingTerms;
		$res->terms          = $commonTerms;
		$res->price          = $priceTerms;
		$res->agregatedTerms = $agregatedTerms;
		
		return $res;
	}
	
	private function _columnsTax()
	{
		if (!$this->config->get('config_tax')) {
			
			return '';
		}
		$zterms = "(tr1.based = 'store'";
		if (isset($this->session->data['shipping_country_id']) 
				|| isset($this->session->data['shipping_zone_id'])
				|| $this->config->get('config_tax_default') == 'shipping') {
			$zterms .= " OR tr1.based = 'shipping'";
		}
		if (isset($this->session->data['payment_country_id']) 
				|| isset($this->session->data['payment_zone_id'])
				|| $this->config->get('config_tax_default') == 'payment') {
			$zterms .= " OR tr1.based = 'payment'";
		}
		$zterms .= ")";

		$fixedTax   = "(SELECT SUM(rate) FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) WHERE tr1.tax_class_id = p.tax_class_id AND tr2.type = 'F' AND {$zterms}) AS fixed_tax";
		$percentTax = "(SELECT SUM(rate) FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) WHERE tr1.tax_class_id = p.tax_class_id AND tr2.type = 'P' AND {$zterms}) AS percent_tax";

		return $fixedTax . ',' . $percentTax . ',';
	}
	
	private function _columnsPrice()
	{
		$customerGroupId = ($this->customer->isLogged()) 
				? $this->customer->getCustomerGroupId() 
				: $this->config->get('config_customer_group_id');
		
		$specials = "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customerGroupId . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
		$discount = "(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customerGroupId . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount";
		
		return $specials . ',' . $discount;
	}
	
	private function _columnRating()
	{
		return "(SELECT ROUND(AVG(rating)) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating";
	}
    
    private function _findOptionIds($values)
    {
        $sql = "   SELECT option_id, option_value_id "
                . "FROM " . DB_PREFIX . "option_value "
                . "WHERE option_value_id IN (" . implode(',', $values) . ")";
        
        $query = $this->db->query($sql);
        $options = array();
        if (count($query->rows)) {
            foreach ($query->rows as $row) {
                if (!isset($options[$row['option_id']])) {
                    $options[$row['option_id']] = array();
                }
                $options[$row['option_id']][] = $row['option_value_id'];
            }
        }
        
        return $options;
    }
}