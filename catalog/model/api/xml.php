<?php
#
# opencart for android API
#
# copyright all rights reserved
# http://www.opencartandroid.com/
# sergio@ptcommerce.net
# 
# version 1.00 @ 2012/02/27
#
class ModelApiXml extends Model { public static function toXml($data, $rootNodeName = 'data', &$xml=null, $itemNodeName = 'item') { if (ini_get('zend.ze1_compatibility_mode') == 1) { ini_set ('zend.ze1_compatibility_mode', 0); } if (is_null($xml)) { $xml = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><'.$rootNodeName.' />'); } foreach($data as $key => $value) { if (is_numeric($key)) { $key = $itemNodeName; } $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key); if (is_array($value)) { $node = ModelApiXml::isAssoc($value) ? $xml->addChild($key) : $xml; ModelApiXml::toXml($value, $key, $node); } else { $value = utf8_encode($value); $xml->addChild($key,$value); } } return $xml->asXML(); } public static function isAssoc( $array ) { return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array))))); } public static function toArray( $obj, &$arr = null ){ if ( is_null( $arr ) ) $arr = array(); if ( is_string( $obj ) ) $obj = new SimpleXMLElement( $obj ); $children = $obj->children(); $executed = false; foreach ($children as $elementName => $node){ if(isset($arr[$elementName]) && $arr[$elementName]!=null){ if(isset($arr[$elementName][0]) && $arr[$elementName][0]!==null){ $i = count($arr[$elementName]); ModelApiXml::toArray($node, $arr[$elementName][$i]); }else{ $tmp = $arr[$elementName]; $arr[$elementName] = array(); $arr[$elementName][0] = $tmp; $i = count($arr[$elementName]); ModelApiXml::toArray($node, $arr[$elementName][$i]); } }else{ $arr[$elementName] = array(); ModelApiXml::toArray($node, $arr[$elementName]); } $executed = true; } if(!$executed&&$children->getName()==""){ $arr = utf8_decode((String)$obj); } return $arr; } } 