<?php
#
# opencart for android API
#
# copyright all rights reserved
# http://www.opencartandroid.com/
# sergio@ptcommerce.net
# 
# version 1.01 @ 2012/03/10
# version 1.00 @ 2012/02/27
#
class ModelApiTools extends Model { 
  
  public function translate_chars($string) { $ent = array( '&nbsp;' => ' ', '&reg;' => '�', '&tilde;' => '� ', '&cedil;' => '�', '&trade;' => '�', '&cent;' => '�', '&pound;' => '�', '&euro;' => '�', '&yen;' => '�', '&copy;' => '�', '&sect;' => '�', '&para;' => '�', '&micro;' => '�', '&ordf;' => '�', '&ordm;' => '�', '&deg;' => '�', '&plusmn;' => '�', '&sup1;' => '�', '&sup2;' => '�', '&sup3;' => '�', '&times;' => '�', '&divide;' => '�', '&frac14;' => '�', '&frac12;' => '�', '&frac34;' => '�', '&not;' => '�', '&Agrave;' => '�', '&Aacute;' => '�', '&Acirc;' => '�', '&Atilde;' => '�', '&Auml;' => '�', '&Aring;' => '�', '&AElig;' => '�', '&Ccedil;' => '�', '&Egrave;' => '�', '&Eacute;' => '�', '&Ecirc;' => '�', '&Euml;' => '�', '&Igrave;' => '�', '&Iacute;' => '�', '&Icirc;' => '�', '&Iuml;' => '�', '&ETH;' => '�', '&Ntilde;' => '�', '&Ograve;' => '�', '&Oacute;' => '�', '&Ucirc;' => '�', '&Otilde;' => '�', '&Ouml;' => '�', '&Ugrave;' => '�', '&Uuml;' => '�', '&Yacute;' => '�', '&Yuml;' => '�', '&agrave;' => '�', '&aacute;' => '�', '&acirc;' => '�', '&atilde;' => '�', '&auml;' => '�', '&aring;' => '�', '&aelig;' => '�', '&ccedil;' => '�', '&egrave;' => '�', '&eacute;' => '�', '&ecirc;' => '�', '&euml;' => '�', '&igrave;' => '�', '&iacute;' => '�', '&icirc;' => '�', '&iuml;' => '�', '&eth;' => '�', '&ntilde;' => '�', '&ograve;' => '�', '&oacute;' => '�', '&ocirc;' => '�', '&otilde;' => '�', '&ouml;' => '�', '&ugrave' => '�', '&uacute;' => '�', '&ucirc;' => '�', '&uuml;' => '�', '&yacute;' => '�', '&thorn;' => '�', '&yuml;' => '�', '&oelig;' => '�', '&OElig;' => '�', '&iquest;' => '�', '&szlig;' => '�', '&THORN;' => '�', '&Oslash;' => '�', '&oslash;' => '�', '&laquo;' => '�', '&raquo;' => '�', '&middot;' => '�', '&bull;' => '�', '&curren;' => '�', '&shy;' => '', '&uml;' => '�', '&Phi;' => '', '&rsquo;' => "'" ); $string = strtr ($string, $ent); return $string; } 

  #bof 1.01
  public function random_select($sql) {
    $random_item = '';
    $random_query = $this->db->query($sql);
    $num_rows = $random_query->num_rows;
    if ($num_rows > 0) {
      $random_row = $this->rand(0, ($num_rows - 1));
    } else return array();

    return $random_query->rows[$random_row];
  }

  public function my_rand($min = null, $max = null) {
    static $seeded;
    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }
    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }
  #eof 1.01
}
