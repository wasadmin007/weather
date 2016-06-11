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
define('VERSION', '1.02'); 

// Configuration
require_once('config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Store
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
  $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {
  $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}

if ($store_query->num_rows) {
  $config->set('config_store_id', $store_query->row['store_id']);
} else {
  $config->set('config_store_id', 0);
}
    
// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $setting) {
  if (!$setting['serialized']) {
    $config->set($setting['key'], $setting['value']);
  } else {
    $config->set($setting['key'], unserialize($setting['value']));
  }
}

if (!$store_query->num_rows) {
  $config->set('config_url', HTTP_SERVER);
  $config->set('config_ssl', HTTPS_SERVER);  
}

$config->set('config_plataform', 'oct');
$config->set('config_plataform_version', $config->get('api_plataform_version'));

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

// Error Handler
function error_handler($errno, $errstr, $errfile, $errline) {
	global $config, $log;
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}
	return TRUE;
}

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
//$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response); 

// Cache
$cache = new Cache();
$registry->set('cache', $cache); 

// Keep sessions
if(!isset($request->get['k']) || !$request->get['k']) {
  unset($_COOKIE);
}

$session = new Session();
$registry->set('session', $session);

// Document
$registry->set('document', new Document());

// Language
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = array(
		'language_id' => $result['language_id'],
		'name'        => $result['name'],
		'code'        => $result['code'],
		'locale'      => $result['locale'],
		'directory'   => $result['directory'],
		'filename'    => $result['filename']
	);
}

if (isset($request->get['l']) && array_key_exists($request->get['l'], $languages)) {
  $code = $request->get['l'];
} else {
  $code = $config->get('config_language');
}

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);  

$registry->set('language', $language);

if (isset($request->get['c'])) {
  $request->get['currency'] = $request->get['c'];
} else {
  $request->get['currency'] = $config->get('config_currency');
}

// Customer
$customer = new Customer($registry);
$registry->set('customer', $customer);

// no cookies login
if(isset($request->get['email']) && isset($request->get['pass'])) {
  if(!isset($request->get['k']) || !$request->get['k']) {
    $session->data['cart'] = array();
    $customer->login($request->get['email'], $request->get['pass']);
  }
}

// Currency
$registry->set('currency', new Currency($registry));

// Tax
$registry->set('tax', new Tax($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// Cart
$registry->set('cart', new Cart($registry));


//  Encryption
if(class_exists('Encryption')) {
  $registry->set('encryption', new Encryption($config->get('config_encryption')));
}


// Front Controller
$controller = new Front($registry);

// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('api/home');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>