<?php

/*
    SEO SCRIPT GENERATOR - v 1.0.1 (29.7.2010)
    generate url_alias from product and category table

    call: http://yourdomain.tld/seo.php

    changes:
    1.0.1 - added ID to url of product and category - duplicity product name possible

		EXTENDED:
		2.0
		* added generate url_alias for manufacturer and information
		* added p- (product), c- (category), m- (manufacturer), and i- (information) on the beginning of url_alias
*/

	// debug
	//ini_set('error_reporting', 6143);
	//ini_set('display_errors', 1);

require_once(dirname(__FILE__)."/config.php");
require_once(DIR_SYSTEM . 'startup.php');
require_once(DIR_DATABASE . 'mysql.php');
$need_configs = array(
	'config_url',
	'config_ssl',
	'config_customer_group_id',
	'config_language'
);

function seo($name){
  return toAscii(html_entity_decode($name));
}

function toAscii($string)
	{
		// cz
		$source[] = '/a/'; $replace[] = 'a';
		$source[] = '/á/'; $replace[] = 'a';
		$source[] = '/b/'; $replace[] = 'b';
		$source[] = '/c/'; $replace[] = 'c';
		$source[] = '/č/'; $replace[] = 'c';
		$source[] = '/d/'; $replace[] = 'd';
		$source[] = '/ď/'; $replace[] = 'd';
		$source[] = '/é/'; $replace[] = 'e';
		$source[] = '/e/'; $replace[] = 'e';
		$source[] = '/ě/'; $replace[] = 'e';
		$source[] = '/f/'; $replace[] = 'f';
		$source[] = '/g/'; $replace[] = 'g';
		$source[] = '/h/'; $replace[] = 'h';
		$source[] = '/í/'; $replace[] = 'i';
		$source[] = '/i/'; $replace[] = 'i';
		$source[] = '/j/'; $replace[] = 'j';
		$source[] = '/k/'; $replace[] = 'k';
		$source[] = '/l/'; $replace[] = 'l';
		$source[] = '/m/'; $replace[] = 'm';
		$source[] = '/n/'; $replace[] = 'n';
		$source[] = '/ň/'; $replace[] = 'n';
		$source[] = '/o/'; $replace[] = 'o';
		$source[] = '/p/'; $replace[] = 'p';
		$source[] = '/q/'; $replace[] = 'q';
		$source[] = '/ó/'; $replace[] = 'o';
		$source[] = '/r/'; $replace[] = 'r';
		$source[] = '/ř/'; $replace[] = 'r';
		$source[] = '/š/'; $replace[] = 's';
		$source[] = '/s/'; $replace[] = 's';
		$source[] = '/ť/'; $replace[] = 't';
		$source[] = '/t/'; $replace[] = 't';
		$source[] = '/ů/'; $replace[] = 'u';
		$source[] = '/ú/'; $replace[] = 'u';
		$source[] = '/u/'; $replace[] = 'u';
		$source[] = '/v/'; $replace[] = 'v';
		$source[] = '/w/'; $replace[] = 'w';
		$source[] = '/y/'; $replace[] = 'y';
		$source[] = '/ý/'; $replace[] = 'y';
		$source[] = '/ž/'; $replace[] = 'z';
		$source[] = '/z/'; $replace[] = 'z';
		// hu
		$source[] = '/ö/'; $replace[] = 'o';
		$source[] = '/o/'; $replace[] = 'o';
		$source[] = '/ü/'; $replace[] = 'u';

		// CZ
		$source[] = '/A/'; $replace[] = 'a';
		$source[] = '/Á/'; $replace[] = 'a';
		$source[] = '/B/'; $replace[] = 'b';
		$source[] = '/C/'; $replace[] = 'c';
		$source[] = '/Č/'; $replace[] = 'c';
		$source[] = '/D/'; $replace[] = 'd';
		$source[] = '/Ď/'; $replace[] = 'd';
		$source[] = '/É/'; $replace[] = 'e';
		$source[] = '/E/'; $replace[] = 'e';
		$source[] = '/Ě/'; $replace[] = 'e';
		$source[] = '/F/'; $replace[] = 'f';
		$source[] = '/G/'; $replace[] = 'g';
		$source[] = '/H/'; $replace[] = 'h';
		$source[] = '/Í/'; $replace[] = 'i';
		$source[] = '/I/'; $replace[] = 'i';
		$source[] = '/J/'; $replace[] = 'j';
		$source[] = '/K/'; $replace[] = 'k';
		$source[] = '/L/'; $replace[] = 'l';
		$source[] = '/M/'; $replace[] = 'm';
		$source[] = '/N/'; $replace[] = 'n';
		$source[] = '/Ň/'; $replace[] = 'n';
		$source[] = '/O/'; $replace[] = 'o';
		$source[] = '/P/'; $replace[] = 'p';
		$source[] = '/Q/'; $replace[] = 'q';
		$source[] = '/Ó/'; $replace[] = 'o';
		$source[] = '/R/'; $replace[] = 'r';
		$source[] = '/Ř/'; $replace[] = 'r';
		$source[] = '/Š/'; $replace[] = 's';
		$source[] = '/S/'; $replace[] = 's';
		$source[] = '/Ť/'; $replace[] = 't';
		$source[] = '/T/'; $replace[] = 't';
		$source[] = '/Ů/'; $replace[] = 'u';
		$source[] = '/Ú/'; $replace[] = 'u';
		$source[] = '/U/'; $replace[] = 'u';
		$source[] = '/V/'; $replace[] = 'v';
		$source[] = '/W/'; $replace[] = 'w';
		$source[] = '/Y/'; $replace[] = 'y';
		$source[] = '/Ý/'; $replace[] = 'y';
		$source[] = '/Ž/'; $replace[] = 'z';
		$source[] = '/Z/'; $replace[] = 'z';
		// HU
		$source[] = '/Ö/'; $replace[] = 'o';
		$source[] = '/Ü/'; $replace[] = 'u';

		$string = preg_replace($source, $replace, $string);

		for ($i=0; $i<strlen($string); $i++)
		{
			if ($string[$i] >= 'a' && $string[$i] <= 'z') continue;
			if ($string[$i] >= 'A' && $string[$i] <= 'Z') continue;
			if ($string[$i] >= '0' && $string[$i] <= '9') continue;
			$string[$i] = '-';
		}
		$string = str_replace("--","-",$string);
		return $string;
	}

// Config
$config = new Config();
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

//PRODUCTS
$query = $db->query("SELECT product_id,name FROM " . DB_PREFIX . "product_description;");
foreach ($query->rows as $row) {
  $query_alias = $db->query("SELECT url_alias_id,query,keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=".((int)$row['product_id'])."';");
  if($query_alias->num_rows){
    $db->query("UPDATE " . DB_PREFIX . "url_alias SET keyword = 'p-".((int)$row['product_id'])."-".$db->escape(seo($row['name']))."' WHERE query = 'product_id=".((int)$row['product_id'])."';");
  }else{
    $db->query("INSERT INTO " . DB_PREFIX . "url_alias (query,keyword) VALUES ('product_id=".((int)$row['product_id'])."','p-".((int)$row['product_id'])."-".$db->escape(seo($row['name']))."');");
  }
}

//CATEGORIES
$query = $db->query("SELECT category_id,name FROM " . DB_PREFIX . "category_description;");
foreach ($query->rows as $row) {
  $query_alias = $db->query("SELECT url_alias_id,query,keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=".((int)$row['category_id'])."';");
  if($query_alias->num_rows){
    $db->query("UPDATE " . DB_PREFIX . "url_alias SET keyword = 'c-".((int)$row['category_id'])."-".$db->escape(seo($row['name']))."' WHERE query = 'category_id=".((int)$row['category_id'])."';");
  }else{
    $db->query("INSERT INTO " . DB_PREFIX . "url_alias (query,keyword) VALUES ('category_id=".((int)$row['category_id'])."','c-".((int)$row['category_id'])."-".$db->escape(seo($row['name']))."');");
  }
}

//MANUFACTURERS
$query = $db->query("SELECT manufacturer_id,name FROM " . DB_PREFIX . "manufacturer;");
foreach ($query->rows as $row) {
  $query_alias = $db->query("SELECT url_alias_id,query,keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=".((int)$row['manufacturer_id'])."';");
  if($query_alias->num_rows){
    $db->query("UPDATE " . DB_PREFIX . "url_alias SET keyword = 'm-".((int)$row['manufacturer_id'])."-".$db->escape(seo($row['name']))."' WHERE query = 'manufacturer_id=".((int)$row['manufacturer_id'])."';");
  }else{
    $db->query("INSERT INTO " . DB_PREFIX . "url_alias (query,keyword) VALUES ('manufacturer_id=".((int)$row['manufacturer_id'])."','m-".((int)$row['manufacturer_id'])."-".$db->escape(seo($row['name']))."');");
  }
}

//INFORMATIONS
$query = $db->query("SELECT information_id,title FROM " . DB_PREFIX . "information_description;");
foreach ($query->rows as $row) {
  $query_alias = $db->query("SELECT url_alias_id,query,keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=".((int)$row['information_id'])."';");
  if($query_alias->num_rows){
    $db->query("UPDATE " . DB_PREFIX . "url_alias SET keyword = 'i-".((int)$row['information_id'])."-".$db->escape(seo($row['title']))."' WHERE query = 'information_id=".((int)$row['information_id'])."';");
  }else{
    $db->query("INSERT INTO " . DB_PREFIX . "url_alias (query,keyword) VALUES ('information_id=".((int)$row['information_id'])."','i-".((int)$row['information_id'])."-".$db->escape(seo($row['title']))."');");
  }
}

echo "done";
?>
