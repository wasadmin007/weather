<?php
class ControllerModuleadvancedslider extends Controller {
	private $error = array();
	private $_name = 'advancedslider';
	private $_version = '0.2.2'; 
	
	public function index() {   
		$this->load->language('module/' . $this->_name);

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');

		$this->data['advancedslider_version'] = $this->_version;
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting($this->_name, $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		$this->data['text_home'] = $this->language->get('text_home');
		
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		//siralama
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		//siralama son
				//resim boyutu
		$this->data['entry_slide_size'] = $this->language->get('entry_slide_size');
		$this->data['entry_slide_height'] = $this->language->get('entry_slide_height');
		$this->data['entry_slide_duration'] = $this->language->get('entry_slide_duration');
		$this->data['entry_slide_velocity'] = $this->language->get('entry_slide_velocity');
		//resim boyutu son
		//manset
		$this->data['text_headline_on'] = $this->language->get('text_headline_on');
		$this->data['text_headline_off'] = $this->language->get('text_headline_off');
		$this->data['entry_headline'] = $this->language->get('entry_headline');
		//manset 
		
		
	//slide type
		$this->data['text_slide_type'] = $this->language->get('text_slide_type');
		//slide type son
		
		$this->data['entry_yes'] = $this->language->get( 'entry_yes' ); 
		$this->data['entry_no']	= $this->language->get( 'entry_no' );

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');


		//pos
				foreach(array('text_info','text_ccpos_configuration','text_loading','button_yenile','button_advancedslider_hesap_ekle','button_kaydet','entry_aktif','entry_sira','entry_img','entry_h3link','entry_span_i','entry_h2','entry_p','entry_thumb','entry_urls','error_general','alert_confirm_save') as $entry_key)
		{
			$this->data[$entry_key] = $this->language->get($entry_key);
		}
//pos
		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (isset($this->error['code' . $language['language_id']])) {
				$this->data['error_code' . $language['language_id']] = $this->error['code' . $language['language_id']];
			} else {
				$this->data['error_code' . $language['language_id']] = '';
			}
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=module/advancedslider&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/advancedslider&token=' . $this->session->data['token'];
				$this->data['action_kaydet'] = HTTPS_SERVER . 'index.php?route=module/advancedslider/hesaplar_kaydet&token=' . $this->session->data['token'];
		$this->data['action_yenile'] = HTTPS_SERVER . 'index.php?route=module/advancedslider/hesaplar_yenile&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];

		$this->load->model('localisation/language');
		

		if (isset($this->request->post[$this->_name . '_position'])) {
			$this->data[$this->_name . '_position'] = $this->request->post[$this->_name . '_position'];
		} else {
			$this->data[$this->_name . '_position'] = $this->config->get($this->_name . '_position');
		}
		
		if (isset($this->request->post[$this->_name . '_status'])) {
			$this->data[$this->_name . '_status'] = $this->request->post[$this->_name . '_status'];
		} else {
			$this->data[$this->_name . '_status'] = $this->config->get($this->_name . '_status');
		}
		
		//manset
		if (isset($this->request->post[$this->_name . '_headline'])) {
			$this->data[$this->_name . '_headline'] = $this->request->post[$this->_name . '_headline'];
		} else {
			$this->data[$this->_name . '_headline'] = $this->config->get($this->_name . '_headline');
		}
		//manset
		//siralama
		if (isset($this->request->post[$this->_name . '_sort_order'])) {
			$this->data[$this->_name . '_sort_order'] = $this->request->post[$this->_name . '_sort_order'];
		} else {
			$this->data[$this->_name . '_sort_order'] = $this->config->get($this->_name . '_sort_order');
		}	
//siralama son		

//resim boyutu

		if (isset($this->request->post[$this->_name . '_slide_size'])) {
			$this->data[$this->_name . '_slide_size'] = $this->request->post[$this->_name . '_slide_size'];
		} else {
			$this->data[$this->_name . '_slide_size'] = $this->config->get($this->_name . '_slide_size');
		}
		if (isset($this->request->post[$this->_name . '_slide_height'])) {
			$this->data[$this->_name . '_slide_height'] = $this->request->post[$this->_name . '_slide_height'];
		} else {
			$this->data[$this->_name . '_slide_height'] = $this->config->get($this->_name . '_slide_height');
		}		
		if (isset($this->request->post[$this->_name . '_slide_duration'])) {
			$this->data[$this->_name . '_slide_duration'] = $this->request->post[$this->_name . '_slide_duration'];
		} else {
			$this->data[$this->_name . '_slide_duration'] = $this->config->get($this->_name . '_slide_duration');
		}
		if (isset($this->request->post[$this->_name . '_slide_velocity'])) {
			$this->data[$this->_name . '_slide_velocity'] = $this->request->post[$this->_name . '_slide_velocity'];
		} else {
			$this->data[$this->_name . '_slide_velocity'] = $this->config->get($this->_name . '_slide_velocity');
		}			
//resim boyutu

//slide type

		if (isset($this->request->post[$this->_name . '_slide_type'])) {
			$this->data[$this->_name . '_slide_type'] = $this->request->post[$this->_name . '_slide_type'];
		} else {
			$this->data[$this->_name . '_slide_type'] = $this->config->get($this->_name . '_slide_type');
		}	
//slide type


		
		$this->template = 'module/' . $this->_name . '.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/' . $this->_name)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
//pos
		
	public function hesaplar_yenile()
	{		
		$this->load->library('json');
		$this->load->language('module/advancedslider');
		$this->load->model('setting/setting');
		
		$lang = array();
		
		foreach(array('text_ccpos_configuration','text_loading','button_yenile','button_advancedslider_hesap_ekle','button_kaydet','entry_aktif','entry_sira','entry_img','entry_h3link','entry_span_i','entry_h2','entry_p','entry_thumb','entry_urls','error_general','alert_confirm_save') as $entry_key)
		{
			$lang[$entry_key] = $this->language->get($entry_key);
		}
		
		
		$json = array(
			'result'=>0,
			'html'=>'',
			'alert'=>$this->language->get('error_general')
		);
		
		unset($json['alert']);
		$json['result'] = 1;
		
		$ccpos_config = array();
		
		$setting = $this->model_setting_setting->getSetting('advancedslider_ccpos');
		
		if(isset($setting['advancedslider_ccpos_config']))
		{
			$ccpos_config = unserialize($setting['advancedslider_ccpos_config']);
		}		
		ob_start();		
		foreach($ccpos_config as $ccpos)
		{
			?>			
			<div class="advancedslider_hesap"><table>
				<tr><td></td><td><button class="sil">X</button></td></tr>
				<tr><td><?php echo $lang['entry_aktif'];?></td><td><input type="checkbox" class="aktif"<?php echo ($ccpos['aktif']?'checked="checked"':'')?>></td></tr>
				<tr><td><?php echo $lang['entry_sira'];?></td><td><input class="sira" size="2" value="<?php echo $ccpos['sira'];?>"></td></tr>
				<tr><td><?php echo $lang['entry_img'];?></td><td><input class="img" size="80" name="img_<?php echo $ccpos['sira'];?>" id="img_<?php echo $ccpos['sira'];?>" value="<?php echo $ccpos['img'];?>"></td></tr>
				<tr><td>Resim Yükle:</td><td><form action="" method="POST" enctype="multipart/form-data" id="formum_<?php echo $ccpos['sira'];?>" name="formum_<?php echo $ccpos['sira'];?>">
<input type="hidden" name="action" value="upload">
<input type="hidden" name="actionsira" id="actionsira" value="<?php echo $ccpos['sira'];?>">
<input type="file" name="uploadimage_<?php echo $ccpos['sira'];?>" id="uploadimage_<?php echo $ccpos['sira'];?>">
</form>
<br /></td></tr>
				<tr><td><?php echo $lang['entry_h3link'];?></td><td><input class="h3link" size="80" value="<?php echo $ccpos['h3link'];?>"></td></tr>
				<tr><td><?php echo $lang['entry_span_i'];?></td><td><input class="span_i" size="80" value="<?php echo $ccpos['span_i'];?>"></td></tr>				
				<tr><td><?php echo $lang['entry_h2'];?></td><td><input class="h2" size="80" value="<?php echo $ccpos['h2'];?>"></td></tr>
				<tr><td><?php echo $lang['entry_p'];?></td><td><input class="paragraf" size="80" value="<?php echo $ccpos['paragraf'];?>"></td></tr>
				<tr><td><?php echo $lang['entry_thumb'];?></td><td><input class="thumb" size="80" name="thumb_<?php echo $ccpos['sira'];?>" id="thumb_<?php echo $ccpos['sira'];?>" value="<?php echo $ccpos['thumb'];?>"></td></tr>
				<tr><td><?php echo $lang['entry_urls'];?></td><td><input class="urls" size="80" value="<?php echo $ccpos['urls'];?>"></td></tr>
				
				

			</table></div>
			
  <script type="text/javascript">
    $(function() {
        $('#uploadimage_<?php echo $ccpos['sira'];?>').change(function() {
            $(this).upload('index.php?route=module/advancedslider/resim_yukle&token=<?php echo $this->session->data['token'];?>', $('form#formum_<?php echo $ccpos['sira'];?>').serialize(), function(res) {
               alert(res);
            }, 'html');
			var file = $('#uploadimage_<?php echo $ccpos['sira'];?>').attr("files")[0];
var fileName = file.fileName;
var fileSize = file.fileSize;
$('input#img_<?php echo $ccpos['sira'];?>').val('image/advancedslider/'+fileName);
$('input#thumb_<?php echo $ccpos['sira'];?>').val('image/advancedslider/thumbs/'+fileName);


        });
    });
  </script>

			<?php
		}
		$json['html'] = ob_get_clean();
		$this->response->setOutput(Json::encode($json));
	}
	
	public function resim_yukle()
	{	
	$allowedfiletypes = array("jpeg","jpg");
$uploadfolder = DIR_IMAGE."advancedslider/" ;
$thumbnailheight = 50; //pixel
$thumbnailfolder = $uploadfolder."thumbs/" ;

$action = $_POST['action'];
$file_sira=$_POST['actionsira'];
$output ="";
if ($action == "upload") {
    if(empty($_FILES['uploadimage_'.$file_sira]['name'])){
       $output .= "Hata! Yüklemek için bir dosya seçin" ;
    } else {
        $uploadfilename = $_FILES['uploadimage_'.$file_sira]['name'];
        $fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
        if (!in_array($fileext,$allowedfiletypes)) { $output .= "Hata: Geçersiz dosya uzantısı(Unsupported file type)!" ; }
        else {
            $fulluploadfilename = $uploadfolder.$uploadfilename ;
            if (move_uploaded_file($_FILES['uploadimage_'.$file_sira]['tmp_name'], $fulluploadfilename)) {
                $output .= "$uploadfilename başarıyla kaydedildi.(image uploaded successfully)";
                $im = imagecreatefromjpeg($fulluploadfilename);
                if (!$im) { $output .="Hata: Resim önizlemesi oluşturulamadı(Thumbnail creation Failed)!" ; }
                else {
                    $imw = imagesx($im); // uploaded image width
                    $imh = imagesy($im); // uploaded image height
                    $nh = $thumbnailheight; // thumbnail height
                    $nw = round(($nh / $imh) * $imw); //thumnail width
                    $newim = imagecreatetruecolor ($nw, $nh);
                    imagecopyresampled ($newim,$im, 0, 0, 0, 0, $nw, $nh, $imw, $imh) ;
                    $thumbfilename = $thumbnailfolder.$uploadfilename ;
                    imagejpeg($newim, $thumbfilename) or die("<strong>Hata: Önizleme kaydedilemedi(Thumbnail saving Failed)!");
                    $output .= "Önizleme kaydedildi(Thumbnail saved successfully)" ;
                }
            } else { $output .="Hata: Dosya kaydedilemedi ($fulluploadfilename)(Image couldnt save)!"; }
        }
    }
}
	$this->response->setOutput($output, $this->config->get('config_compression'));	

	}	
	
	public function hesaplar_kaydet()
	{		
		$this->load->library('json');
		$this->load->language('module/advancedslider');
		$this->load->model('setting/setting');
		
		$json = array(
			'result'=>0,
			'alert'=>$this->language->get('error_general')
		);
		
		if(!$this->user->hasPermission('modify', 'module/advancedslider')){
			$json['alert'] = $this->language->get('error_permission');
		}
		else
		{
			$advancedslider_data = '[]';
		
			if(isset($this->request->post['advancedslider_data']))
			{
				$advancedslider_data = html_entity_decode($this->request->post['advancedslider_data'],ENT_QUOTES,'utf-8');
			}
			
			if( $advancedslider_data = Json::decode($advancedslider_data) )
			{
				$ccpos_config = array();
				$sort_keys = array();
				foreach($advancedslider_data as $key=>$pos_hesap)
				{
					$ccpos_config[$key] = array(
						'aktif'=>$pos_hesap->aktif,
						'sira'=>intval($pos_hesap->sira),
						'img'=>$pos_hesap->img,
						'h3link'=>$pos_hesap->h3link,
						'span_i'=>$pos_hesap->span_i,
						'h2'=>$pos_hesap->h2,
						'paragraf'=>$pos_hesap->paragraf,
						'thumb'=>$pos_hesap->thumb,
						'urls'=>$pos_hesap->urls,
					
					);
					
					$sort_keys[] = $pos_hesap->sira;
					
				}
				
				array_multisort($sort_keys,$ccpos_config);
				$this->model_setting_setting->editSetting('advancedslider_ccpos', array('advancedslider_ccpos_config'=>serialize($ccpos_config)));
				$json['result'] = 1;
				$json['alert'] = $this->language->get('alert_config_save_success');
			}
		}
		
		$this->response->setOutput(Json::encode($json));
	}
	//
}
?>
