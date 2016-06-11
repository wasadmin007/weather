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
class ModelApiImage extends Model { function resize($filename, $width, $height) { if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) { return; } $info = pathinfo($filename); $extension = $info['extension']; if($extension == 'swf' || $extension == 'flv') $filename = 'flash_movie.jpg'; $info = pathinfo($filename); $extension = $info['extension']; $old_image = $filename; $filename = $this->tep_url_rewriting_cast($filename); $new_image = 'cache/remote/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension; if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) { $path = ''; $directories = explode('/', dirname(str_replace('../', '', $new_image))); foreach ($directories as $directory) { $path = $path . '/' . $directory; if (!file_exists(DIR_IMAGE . $path)) { @mkdir(DIR_IMAGE . $path, 0777); } } $image = new Image(DIR_IMAGE . $old_image); $image->resize($width, $height); $image->save(DIR_IMAGE . $new_image); } return $new_image; } function tep_url_rewriting_cast($str) { $tmp = strtr($str, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝאבגדהוחטיךכלםמןנעףפץצשתûü‎ÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); $tmp = preg_replace('~[^\\pL0-9/\.]+~u', '-', $tmp); $tmp = strtolower($tmp); return $tmp; } } ?>