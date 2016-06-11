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
  
  public function translate_chars($string) { $ent = array( '&nbsp;' => ' ', '&reg;' => '®', '&tilde;' => '˜ ', '&cedil;' => '¸', '&trade;' => '™', '&cent;' => '¢', '&pound;' => '£', '&euro;' => '€', '&yen;' => '¥', '&copy;' => '©', '&sect;' => '§', '&para;' => '¶', '&micro;' => 'µ', '&ordf;' => 'ª', '&ordm;' => 'º', '&deg;' => '°', '&plusmn;' => '±', '&sup1;' => '¹', '&sup2;' => '²', '&sup3;' => '³', '&times;' => '×', '&divide;' => '÷', '&frac14;' => '¼', '&frac12;' => '½', '&frac34;' => '¾', '&not;' => '¬', '&Agrave;' => 'À', '&Aacute;' => 'Á', '&Acirc;' => 'Â', '&Atilde;' => 'Ã', '&Auml;' => 'Ä', '&Aring;' => 'Å', '&AElig;' => 'Æ', '&Ccedil;' => 'Ç', '&Egrave;' => 'È', '&Eacute;' => 'É', '&Ecirc;' => 'Ê', '&Euml;' => 'Ë', '&Igrave;' => 'Ì', '&Iacute;' => 'Í', '&Icirc;' => 'Î', '&Iuml;' => 'Ï', '&ETH;' => 'Ğ', '&Ntilde;' => 'Ñ', '&Ograve;' => 'Ò', '&Oacute;' => 'Ó', '&Ucirc;' => 'Û', '&Otilde;' => 'Õ', '&Ouml;' => 'Ö', '&Ugrave;' => 'Ù', '&Uuml;' => 'Ü', '&Yacute;' => 'İ', '&Yuml;' => 'Ÿ', '&agrave;' => 'à', '&aacute;' => 'á', '&acirc;' => 'â', '&atilde;' => 'ã', '&auml;' => 'ä', '&aring;' => 'å', '&aelig;' => 'æ', '&ccedil;' => 'ç', '&egrave;' => 'è', '&eacute;' => 'é', '&ecirc;' => 'ê', '&euml;' => 'ë', '&igrave;' => 'ì', '&iacute;' => 'í', '&icirc;' => 'î', '&iuml;' => 'ï', '&eth;' => 'ğ', '&ntilde;' => 'ñ', '&ograve;' => 'ò', '&oacute;' => 'ó', '&ocirc;' => 'ô', '&otilde;' => 'õ', '&ouml;' => 'ö', '&ugrave' => 'ù', '&uacute;' => 'ú', '&ucirc;' => 'û', '&uuml;' => 'ü', '&yacute;' => 'ı', '&thorn;' => 'ş', '&yuml;' => 'ÿ', '&oelig;' => 'œ', '&OElig;' => 'Œ', '&iquest;' => '¿', '&szlig;' => 'ß', '&THORN;' => 'Ş', '&Oslash;' => 'Ø', '&oslash;' => 'ø', '&laquo;' => '«', '&raquo;' => '»', '&middot;' => '·', '&bull;' => '•', '&curren;' => '¤', '&shy;' => '', '&uml;' => '¨', '&Phi;' => '', '&rsquo;' => "'" ); $string = strtr ($string, $ent); return $string; } 

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
