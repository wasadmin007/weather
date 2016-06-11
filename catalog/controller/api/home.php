<?php   
#
# opencart for android API
#
# copyright all rights reserved
# http://www.opencartandroid.com/
# sergio@ptcommerce.net
# 
# oc15
# version 1.02 @ 2012/03/10
#

class ControllerApiHome extends Controller {
  
  private $version;
  private $method;
  private $encode; 
  private $set;
  
  public function index() {

    # set defaults
    $this->start();

    // request info processed localy
    if($this->method == 'version') {
      $result = $this->version();
      $this->response->addHeader('Content-Type: application/xml; charset=utf-8');
      $this->response->setOutput($result, 0);
      return;
    }
    if($this->method == 'versions') {
      $result = $this->versions();
      $this->response->addHeader('Content-Type: application/xml; charset=utf-8');
      $this->response->setOutput($result, 0);
      return;
    }

    // check offline
    if(!$this->config->get('api_status')) {
      $result = $this->offline();
      $this->response->addHeader('Content-Type: application/xml; charset=utf-8');
      $this->response->setOutput($result, 0);
      return;
    }

    $this->load->model('api/tools');
    // process request with choosen api version
    // todo: check if file exist before
    $this->load->model('api/apiv'.$this->version);
    $execute = 'model_api_apiv' . $this->version;
    
    $result = $this->$execute->main($this->method);
    
    // encoding
    switch($this->encode) {
      case 'json':
      $this->response->addHeader('Content-Type: application/json; charset=utf-8');
      $result = json_encode($result);      
      break;
      case 'xml':
      default:
        $this->load->model('api/xml');
        $result = $this->model_api_xml->toXml($result);
        $this->response->addHeader('Content-Type: application/xml; charset=utf-8');
      break;
    }    
    
    // send result
    $this->response->setOutput($result, 0);
  }

  private function start() {
    # images sizes
    # categories
    $this->config->set('api_ic0w', 100);
    $this->config->set('api_ic0h', 100);
    $this->config->set('api_ic1w', 70);
    $this->config->set('api_ic1h', 70);
    # manufacturers
    $this->config->set('api_im1w', 50);
    $this->config->set('api_im1h', 50);
    # products
    $this->config->set('api_ip0w', 450);
    $this->config->set('api_ip0h', 450);
    $this->config->set('api_ip1w', 70);
    $this->config->set('api_ip1h', 70);
    $this->config->set('api_ip2w', 310);
    $this->config->set('api_ip2h', 310);

    # core
    $this->version = 0;
    $this->method = 'version';
    $this->encode = 'xml';
    $this->set = 1;

    # api version requested
    if(isset($this->request->get['v'])) {
      $this->version = (int)$this->request->get['v'];
    }
    # data requested
    if(isset($this->request->get['m']) && !empty($this->request->get['m'])) {
      $this->method = $this->request->get['m'];
    }
    # requested encode mode
    if(isset($this->request->get['e']) && !empty($this->request->get['e'])) {
      $this->encode = $this->request->get['e'];
    }
    # field set requested
    if(isset($this->request->get['s'])) {
      $this->set = (int)$this->request->get['s'];
    }

    $this->config->set('api_version', $this->version);
    $this->config->set('api_method', $this->method);
    $this->config->set('api_encode', $this->encode);
    $this->config->set('api_set', $this->set);
    
    # html tags request
    $this->config->set('api_htmltags', true);
    if(isset($this->request->get['t']) && empty($this->request->get['t'])) {
      $this->config->set('api_htmltags', false);
    }

    # price format request
    $this->config->set('api_price_format', true);
    if(isset($this->request->get['f'])) {
      $this->config->set('api_price_format', (int)$this->request->get['f']);
    }
  }
  
  private function version () {
    
    $status = (int)$this->config->get('api_status');
    
    $result = '<?xml version="1.0" encoding="utf-8"?>';
    $result .= '<data>';
    $result .= '<system>'.$this->config->get('config_plataform').'</system>';
    $result .= '<version>'.$this->config->get('config_plataform_version').'</version>';
    $result .= '<core>'.VERSION.'</core>';
    $result .= '<api>'.$this->version.'</api>';
    $result .= '<status>'.$status.'</status>';
    $result .= '</data>';
    return $result;
  }

  private function versions () {
    $files = glob(DIR_APPLICATION . 'model/api/apiv*.php');
    $result = '<?xml version="1.0" encoding="utf-8"?>';
    $result .= '<data>';
    if ($files) {
      foreach ($files as $file) {
        $result .= '<item>'.str_replace("apiv", "", basename($file, '.php')).'</item>';
      }
    }
    $result .= '</data>';
    return $result;
  }

  private function offline () {
    $result  = '<?xml version="1.0" encoding="utf-8"?>';
    $result .= '<data>';
    $result .= '<error>999999</error>';
    $result .= '<status>0</status>';
    $result .= '</data>';
    return $result;
  }
}
?>
