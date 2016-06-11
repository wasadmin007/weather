<?php
#
# opencart for android API level 0
#
# copyright all rights reserved
# http://www.opencartandroid.com/
# sergio@ptcommerce.net
# 
# oc15
# version 1.02 @ 2012/03/10
#

class ModelApiApiV0 extends Model {
  public function main($method) {
    switch($method) {
      case 'logo':
        $result = $this->getLogo();
      break;
      case 'banner':
        $result = $this->getBanner();
      break;
      case 'welcome':
        $result = $this->getWelcome();
      break;
      case 'contacts':
        $result = $this->getContacts();
      break;
      case 'information':
        $result = $this->getInformation();
      break;
      case 'informations':
        $result = $this->getInformations();
      break;
      case 'category': 
        $result = $this->getCategoryById();
      break;
      case 'categories': 
        $result = $this->getCategoriesByParentId();
      break;
      case 'manufacturers': 
        $result = $this->getManufacturers();
      break;
      case 'products/category': 
        $result = $this->getProductsByCategoryId();
      break;
      case 'products/manufacturer': 
        $result = $this->getProductsByManufacturerId();
      break;
      case 'products/special': 
        $result = $this->getProductsSpecial();
      break;
      case 'products/featured': 
        $result = $this->getProductsFeatured();
      break;
      case 'products/latest': 
        $result = $this->getProductsLatest();
      break;
      case 'products/bestseller': 
        $result = $this->getProductsBestseller();
      break;
      case 'product': 
        $result = $this->getProductById();
      break;
      default:
        $result = $this->getError(1);
      break;
    }

    return $result;
  }
  private function getError($n) {
    $result = array();
    $result['error'] = $n;
    return $result;
  }
  public function getLogo() {
    $result = array();
    if ($this->config->get('api_store_logo') && file_exists(DIR_IMAGE . $this->config->get('api_store_logo'))) {
      $result['image'] = $this->config->get('api_store_logo');
    } else {
      if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
        $result['image'] = $this->config->get('config_logo');
      } else {
        $result['image'] = '';
      }
    }
    return $result;
  }
  public function getBanner() {
    $result = $tmp = array();
    $id = 'default'; if(isset($this->request->get['id']) && !empty($this->request->get['id'])) $id = $this->db->escape($this->request->get['id']);
    $images = $this->config->get('api_images');
    foreach($images as $image) {
      if($image['group'] == $id) {
        if ($image['image'] && file_exists(DIR_IMAGE . $image['image'])) {
          $tmp[] = $image['image'];
        }
      }
    }
    $nr = count($tmp);
    if($nr) {
      $rnd = $this->model_api_tools->my_rand(0, ($nr-1));
      $result[] = $tmp[$rnd]; 
    }
    return $result;
  }
  public function getWelcome() {
    $result = array();
    $result['title'] = $this->config->get('config_name');
    $this->load->model('setting/store');
    if (!$this->config->get('config_store_id')) {
      $result['text'] = $this->config->get('config_description_' . $this->config->get('config_language_id'));
    } else {
      $store_info = $this->model_setting_store->getStore($this->config->get('config_store_id'));
      if ($store_info) {
        $result['text'] = $store_info['description'];
      } else {
        $result['text'] = '';
      }
    }
    $result['title']  = $this->decode1($result['title']);
    $result['text']   = $this->decode2($result['text']);
    if(!$this->config->get('api_htmltags')) $result['text'] = $this->my_strip_tags($result['text']);
    $tmp = $result;
    $result = array();
    $result[] = $tmp;
    return $result;
  }
  public function getContacts() {
    $result = array();
    if ($this->config->get('api_store_logo') && file_exists(DIR_IMAGE . $this->config->get('api_store_logo'))) {
      $result['image'] = $this->config->get('api_store_logo');
    } else {
      if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
        $result['image'] = $this->config->get('config_logo');
      } else {
        $result['image'] = '';
      }
    }

    $result['title'] = $this->config->get('config_title');
    $result['description'] = $this->config->get('config_meta_description');
    $result['url']              = $this->config->get('config_url');
    $result['title']            = $this->decode1($result['title']);
    $result['description']      = $this->decode1($result['description']);
    $result['owner']            = $this->decode1($this->config->get('config_owner'));
    $result['address']          = $this->decode1($this->config->get('config_address'));
    $result['email']            = $this->decode1($this->config->get('config_email'));
    $result['telephone']        = $this->decode1($this->config->get('config_telephone'));
    $result['fax']              = $this->decode1($this->config->get('config_fax'));

    // banner and banners    
    $tmp =array();
    $images = $this->config->get('api_images');
    foreach($images as $image) {
      if($image['group'] == 'default' || $image['group'] == '') {
        if ($image['image'] && file_exists(DIR_IMAGE . $image['image'])) {
          $tmp[] = $image['image'];
        }
      }
    }
    $nr = count($tmp);
    if($nr) {
      $rnd = $this->model_api_tools->my_rand(0, ($nr-1));
      $banner = $tmp[$rnd]; 
    } else $banner = '';
    $tmp1 =array();
    $max = min($nr, 5);
    for($i = 0; $i < $max; $i++) {
      $tmp1[] = $tmp[$i];
    }
    $banners = implode(',', $tmp1);
    
    $result['banner']          = $banner;
    $result['banners']          = $banners;

    // localization
    $result['location']         = $this->config->get('api_location');
    $result['country_id']       = $this->config->get('config_country_id');
    $result['zone_id']          = $this->config->get('config_zone_id');
    
    $tmp = array();
    $rs = $this->getLanguages();
    foreach($rs as $r) {
      if($r['status']) {
        $tmp[] = $r['code'];
      }
    }
    $languages = implode(',', $tmp);

    $tmp = array();
    $rs = $this->getCurrencies();
    foreach($rs as $r) {
      if($r['status']) {
        $tmp[] = $r['code'];
      }
    }
    $currencies = implode(',', $tmp);

    $tmp = array();
    $rs = $this->getTaxes();
    foreach($rs as $r) {
      $tmp[] = implode('$@$', array($r['tax_class_id'], $this->decode1($r['title'])));
    }
    $taxes = implode('+%+', $tmp);
    
    $result['languages']        = $this->decode1($languages);
    $result['currencies']       = $this->decode1($currencies);
    $result['taxes']            = $taxes;
    $result['tax_status']       = $this->config->get('config_tax');
    
    // system
    $result['system']           = $this->config->get('config_plataform');
    $result['version']          = $this->config->get('config_plataform_version');
    $result['core']             = VERSION;
    $result['api']              = $this->config->get('api_version');

    $tmp = array();
    $files = glob(DIR_APPLICATION . 'model/api/apiv*.php');
    if ($files) {
      foreach ($files as $file) {
        $tmp[] = str_replace("apiv", "", basename($file, '.php'));
      }
    }
    $versions = implode(',', $tmp);

    $result['versions']           = $versions;
    $result['status']           = $this->config->get('api_status');

    $tmp = $result;
    $result = array();
    $result[] = $tmp;
    return $result;
  }
  public function getInformation() {
    $result = array();
    $id = 0; if(isset($this->request->get['id']) && !empty($this->request->get['id'])) $id = (int)$this->request->get['id'];
    if(!$id) return $this->getError(2);
    $this->load->model('catalog/information');
    $results = array();
    $results[] = $this->model_catalog_information->getInformation($id);
    foreach($results as $k => $v ) {
      $results[$k]['title']  = $this->decode1($results[$k]['title']);
      $results[$k]['description']  = $this->decode2($results[$k]['description']);
      if(!$this->config->get('api_htmltags')) $results[$k]['description'] = $this->my_strip_tags($results[$k]['description']);
      $result[] = array(
        'information_id'  => $results[$k]['information_id'],
        'title'           => $results[$k]['title'],
        'description'     => $results[$k]['description']
      );
    }
    return $result;
  }
  public function getInformations() {
    $result = array();
    $this->load->model('catalog/information');
    if($this->config->get('information_range_start') && $this->config->get('information_range_end') && $this->config->get('information_limit')) {
      $results = $this->model_catalog_information->getInformations($this->config->get('information_range_start'), $this->config->get('information_range_end'), $this->config->get('information_limit'));
    } else {
      $results = $this->model_catalog_information->getInformations();
    }
    foreach($results as $k => $v ) {
      $results[$k]['title']  = $this->decode1($results[$k]['title']);
      $result[] = array(
        'information_id' => $results[$k]['information_id'],
        'title'        => $results[$k]['title']
      );
    }
    return $result;
  }
  public function getProductById() {
    $result = array();
    $id = 0; if(isset($this->request->get['id']) && !empty($this->request->get['id'])) $id = (int)$this->request->get['id'];
    if(!$id) return $this->getError(2);
    $isize1 = $this->getRequestedImageSize ('p2', 'is');
    $isize2 = $this->getRequestedImageSize ('p0', 'is1');
    $this->load->model('api/image');
    $this->load->model('catalog/product');
    $results = array();
    $results[] = $this->model_catalog_product->getProduct($id);
    foreach($results as $k => $v ) {
      $results[$k]['price']               = $this->getPrice($results[$k]['price'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['special']             = $this->getSpecial($results[$k]['special'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['name']                = $this->decode1($results[$k]['name']);
      $results[$k]['model']               = $this->decode1($results[$k]['model']);
      $results[$k]['meta_description']    = $this->decode1($results[$k]['meta_description']);
      $results[$k]['manufacturer']    = $this->decode1($results[$k]['manufacturer']);
      $results[$k]['description']     = $this->decode2($results[$k]['description']);
      if(!$this->config->get('api_htmltags')) $results[$k]['description'] = $this->my_strip_tags($results[$k]['description']);
      $image = $results[$k]['image'];
      $results[$k]['image'] = $this->model_api_image->resize($image, $isize1['w'], $isize1['h']);
      $results[$k]['big'] = $this->model_api_image->resize($image, $isize2['w'], $isize2['h']);
      $result[] = array( 
        'product_id'        => $results[$k]['product_id'],
        'manufacturer_id'   => $results[$k]['manufacturer_id'],
        'model'             => $results[$k]['model'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'big'               => $results[$k]['big'],
        'price'             => $results[$k]['price'],
        'special'           => $results[$k]['special'],
        'tax_class_id'      => $results[$k]['tax_class_id'],
        'minimum'           => $results[$k]['minimum'],
        'manufacturer'      => $results[$k]['manufacturer'],
        'meta_description'  => $results[$k]['meta_description'],
        'description'       => $results[$k]['description']
      );
    }
        
    return $result;
  }
  public function getProductsSpecial() {
    $result = array();
    $isize = $this->getRequestedImageSize ('p1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/product');
    $results = $this->model_catalog_product->getProductSpecials();
    foreach($results as $k => $v ) {
      $results[$k]['price']               = $this->getPrice($results[$k]['price'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['special']             = $this->getSpecial($results[$k]['special'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['name']                = $this->decode1($results[$k]['name']);
      $results[$k]['model']               = $this->decode1($results[$k]['model']);
      $results[$k]['meta_description']    = $this->decode1($results[$k]['meta_description']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'product_id'        => $results[$k]['product_id'],
        'manufacturer_id'   => $results[$k]['manufacturer_id'],
        'model'             => $results[$k]['model'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'price'             => $results[$k]['price'],
        'special'           => $results[$k]['special'],
        'tax_class_id'      => $results[$k]['tax_class_id'],
        'minimum'           => $results[$k]['minimum'],
        'meta_description'  => $results[$k]['meta_description'],
      );
    }
    return $result;
  }
  public function getProductsFeatured() {
    $result = $results = array();
    $isize = $this->getRequestedImageSize ('p1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/product');
    //$results = $this->model_catalog_product->getFeaturedProducts($this->config->get('featured_limit'));
    $products = explode(',', $this->config->get('featured_product'));    
    if (empty($setting['limit'])) {
      $setting['limit'] = 5;
    }
    $products = array_slice($products, 0, (int)$setting['limit']);
    foreach ($products as $product_id) {
      $results[] = $this->model_catalog_product->getProduct($product_id);
    }
    foreach($results as $k => $v ) {
      $results[$k]['price']               = $this->getPrice($results[$k]['price'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['special']             = $this->getSpecial($results[$k]['special'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['name']                = $this->decode1($results[$k]['name']);
      $results[$k]['model']               = $this->decode1($results[$k]['model']);
      $results[$k]['meta_description']    = $this->decode1($results[$k]['meta_description']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'product_id'        => $results[$k]['product_id'],
        'manufacturer_id'   => $results[$k]['manufacturer_id'],
        'model'             => $results[$k]['model'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'price'             => $results[$k]['price'],
        'special'           => $results[$k]['special'],
        'tax_class_id'      => $results[$k]['tax_class_id'],
        'minimum'           => $results[$k]['minimum'],
        'meta_description'  => $results[$k]['meta_description'],
      );
    }
    return $result;
  }
  public function getProductsLatest() {
    $result = array();
    $isize = $this->getRequestedImageSize ('p1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/product');
    $results = $this->model_catalog_product->getLatestProducts(20);
    foreach($results as $k => $v ) {
      $results[$k]['price']               = $this->getPrice($results[$k]['price'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['special']             = $this->getSpecial($results[$k]['special'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['name']                = $this->decode1($results[$k]['name']);
      $results[$k]['model']               = $this->decode1($results[$k]['model']);
      $results[$k]['meta_description']    = $this->decode1($results[$k]['meta_description']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'product_id'        => $results[$k]['product_id'],
        'manufacturer_id'   => $results[$k]['manufacturer_id'],
        'model'             => $results[$k]['model'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'price'             => $results[$k]['price'],
        'special'           => $results[$k]['special'],
        'tax_class_id'      => $results[$k]['tax_class_id'],
        'minimum'           => $results[$k]['minimum'],
        'meta_description'  => $results[$k]['meta_description'],
      );
    }
    return $result;
  }
  public function getProductsBestseller() {
    $result = array();
    $isize = $this->getRequestedImageSize ('p1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/product');
    $results = $this->model_catalog_product->getBestSellerProducts(10);
    foreach($results as $k => $v ) {
      $results[$k]['price']               = $this->getPrice($results[$k]['price'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['special']             = $this->getSpecial($results[$k]['special'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['name']                = $this->decode1($results[$k]['name']);
      $results[$k]['model']               = $this->decode1($results[$k]['model']);
      $results[$k]['meta_description']    = $this->decode1($results[$k]['meta_description']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'product_id'        => $results[$k]['product_id'],
        'manufacturer_id'   => $results[$k]['manufacturer_id'],
        'model'             => $results[$k]['model'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'price'             => $results[$k]['price'],
        'special'           => $results[$k]['special'],
        'tax_class_id'      => $results[$k]['tax_class_id'],
        'minimum'           => $results[$k]['minimum'],
        'meta_description'  => $results[$k]['meta_description'],
      );
    }
    return $result;
  }
  public function getProductsByCategoryId() {
    $result = array();
    $id = 0; if(isset($this->request->get['id']) && !empty($this->request->get['id'])) $id = (int)$this->request->get['id'];
    $isize = $this->getRequestedImageSize ('p1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/product');
    $data = array(
      'filter_category_id' => $id, 
    );
    $results = $this->model_catalog_product->getProducts($data);
    foreach($results as $k => $v ) {
      $results[$k]['price']               = $this->getPrice($results[$k]['price'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['special']             = $this->getSpecial($results[$k]['special'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['name']                = $this->decode1($results[$k]['name']);
      $results[$k]['model']               = $this->decode1($results[$k]['model']);
      $results[$k]['meta_description']    = $this->decode1($results[$k]['meta_description']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'product_id'        => $results[$k]['product_id'],
        'manufacturer_id'   => $results[$k]['manufacturer_id'],
        'model'             => $results[$k]['model'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'price'             => $results[$k]['price'],
        'special'           => $results[$k]['special'],
        'tax_class_id'      => $results[$k]['tax_class_id'],
        'minimum'           => $results[$k]['minimum'],
        'meta_description'  => $results[$k]['meta_description'],
      );
    }
    return $result;
  }
  public function getProductsByManufacturerId() {
    $id = 0; if(isset($this->request->get['id']) && !empty($this->request->get['id'])) $id = (int)$this->request->get['id'];
    if(!$id) return $this->getError(2);
    $result = array();
    $isize = $this->getRequestedImageSize ('p1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/product');
    $data = array(
      'filter_manufacturer_id' => $id, 
    );
    $results = $this->model_catalog_product->getProducts($data);
    foreach($results as $k => $v ) {
      $results[$k]['price']               = $this->getPrice($results[$k]['price'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['special']             = $this->getSpecial($results[$k]['special'], $results[$k]['product_id'], $results[$k]['tax_class_id']);
      $results[$k]['name']                = $this->decode1($results[$k]['name']);
      $results[$k]['model']               = $this->decode1($results[$k]['model']);
      $results[$k]['meta_description']    = $this->decode1($results[$k]['meta_description']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'product_id'        => $results[$k]['product_id'],
        'manufacturer_id'   => $results[$k]['manufacturer_id'],
        'model'             => $results[$k]['model'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'price'             => $results[$k]['price'],
        'special'           => $results[$k]['special'],
        'tax_class_id'      => $results[$k]['tax_class_id'],
        'minimum'           => $results[$k]['minimum'],
        'meta_description'  => $results[$k]['meta_description'],
      );
    }
    return $result;
  }
  public function getCategoryById() {
    $result = array();
    $id = 0; if(isset($this->request->get['id']) && !empty($this->request->get['id'])) $id = (int)$this->request->get['id'];
    $isize = $this->getRequestedImageSize ('c1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/category');
    $results = array();
    $results[] = $this->model_catalog_category->getCategory($id);
    foreach($results as $k => $v ) {
      $results[$k]['name']                = $this->decode1($results[$k]['name']);
      $results[$k]['meta_description']    = $this->decode1($results[$k]['meta_description']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'category_id'       => $results[$k]['category_id'],
        'parent_id'         => $results[$k]['parent_id'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'meta_description'  => $results[$k]['meta_description'],
      );
    }
    return $result;
  }
  public function getCategoriesByParentId() {
    $result = array();
    $id = 0; if(isset($this->request->get['id']) && !empty($this->request->get['id'])) $id = (int)$this->request->get['id'];
    $isize = $this->getRequestedImageSize ('c1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/category');
    $results = $this->model_catalog_category->getCategories($id);
    foreach($results as $k => $v ) {
      $results[$k]['name']              = $this->decode1($results[$k]['name']);
      $results[$k]['meta_description']  = $this->decode1($results[$k]['meta_description']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'category_id'       => $results[$k]['category_id'],
        'parent_id'         => $results[$k]['parent_id'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
        'meta_description'  => $results[$k]['meta_description']
      );
    }
    return $result;
  }
  public function getManufacturers() {
    $result = array();
    $id = 0; if(isset($this->request->get['id']) && !empty($this->request->get['id'])) $id = (int)$this->request->get['id'];
    $isize = $this->getRequestedImageSize ('m1', 'is');
    $this->load->model('api/image');
    $this->load->model('catalog/manufacturer');
    $results = $this->model_catalog_manufacturer->getManufacturers();
    foreach($results as $k => $v ) {
      $results[$k]['name']  = $this->decode1($results[$k]['name']);
      $results[$k]['image'] = $this->model_api_image->resize($results[$k]['image'], $isize['w'], $isize['h']);
      $result[] = array( 
        'manufacturer_id'   => $results[$k]['manufacturer_id'],
        'name'              => $results[$k]['name'],
        'image'             => $results[$k]['image'],
      );
    }
    return $result;
  }
  private function getPrice($value, $product_id, $tax_class_id ) {
    if($this->config->get('config_customer_price')) return "";
    $format = $this->config->get('api_price_format');
    $price = $this->currency->format($this->tax->calculate($value, $tax_class_id, $this->config->get('config_tax')), 0, 0, $format);
    if(strstr($price,"€")) {
      $price = str_replace("€", " euro", $price);
    }
    $price = str_replace("€", " euro", $price);
    $price = str_replace("£", "�", $price);
    return $price;
  }
  private function getSpecial($value, $product_id, $tax_class_id ) {
    if($this->config->get('config_customer_price')) return "";
    $format = $this->config->get('api_price_format');
    $special = $this->currency->format($this->tax->calculate($value, $tax_class_id, $this->config->get('config_tax')), 0, 0, $format);
    $special = str_replace("€", " euro", $special);
    $special = str_replace("£", "�", $special);
    return $special;
  }
  private function getRequestedImageSize ($format, $request = 'is') {
    if(isset($this->request->get[$request]) && ((int)$this->request->get[$request] > 0)) {
      $iw = $this->request->get[$request];
      $ih = $iw * round($this->config->get('api_i'.$format.'h') / $this->config->get('api_i'.$format.'w'));
    } else {
      $iw = $this->config->get('api_i'.$format.'w');
      $ih = $this->config->get('api_i'.$format.'h');
    }
    return array('w' => $iw, 'h' => $ih);
  }
  private function decode1($string) {
    $mode = $this->config->get('api_encoding');
    switch($mode) {
      case '1':
        $string = utf8_decode($string);
        $string = $this->translate_chars($string);
      break;
      default:
        $string = utf8_decode($string);
      break;
    }
    return $string;
  }
  private function decode2($string) {
        $mode = $this->config->get('api_encoding');
    switch($mode) {
      case '1':
        $string = $this->translate_chars(html_entity_decode($string, ENT_QUOTES, 'UTF-8'));
      break;
      default:
        $string = utf8_decode($string);
      break;
    }

    return $string;
  }
  private function my_strip_tags($string) {
    $string = strip_tags($string);
    return $string;
  }
  private function translate_chars($string) {
    return $this->model_api_tools->translate_chars($string);
  }
  
  #
  #
  public function getCurrencies() {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE status = '1' ORDER BY title");
    return $query->rows;
  }
  
  #
  #
  public function getLanguages() {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1' ORDER BY sort_order, name");
    return $query->rows;      
  }

  #
  #
  public function getTaxes() {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class");
    return $query->rows;      
  }

}
?>