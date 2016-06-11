<?php
class ControllerModuleProductDiscount extends Controller {
	private $error = array();

	public function index() {
	 $this->language->load('module/product_discount'); 

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/product_discount');
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			if ($this->updateProducts()) {
				$this->session->data['_success'] = $this->language->get('text_success'); 
				$this->session->data['success'] = $this->language->get('text_success');
			}

		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');


		$this->data['column_image'] = $this->language->get('text_column_image');
		$this->data['column_name'] = $this->language->get('text_column_name');
		$this->data['column_model'] = $this->language->get('text_column_model');
		$this->data['column_price'] = $this->language->get('text_column_price');
		$this->data['column_quantity'] = $this->language->get('text_column_quantity');
		$this->data['column_minimum'] = $this->language->get('text_column_minimum');
		$this->data['column_status'] = $this->language->get('text_column_status');
		$this->data['column_action'] = $this->language->get('text_column_action');
		
		$this->data['column_priority'] = $this->language->get('text_column_priority');
		$this->data['column_date_start'] = $this->language->get('text_column_date_start');
		$this->data['column_date_end'] = $this->language->get('text_column_date_end');
		$this->data['column_customer_group'] = $this->language->get('text_column_customer_group');

		$this->data['text_product_special'] = $this->language->get('text_product_special');
		$this->data['text_product_discount'] = $this->language->get('text_product_discount');


		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_filter'] = $this->language->get('button_filter');


		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}
		
		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
						
		$url = '';
						
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}
		
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}		

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}		

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/product_discount', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('module/product_discount', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('module/product_discount', 'token=' . $this->session->data['token'] . $url, 'SSL');


		$data = array(
			'filter_name'	       => $filter_name, 
			'filter_model'	     => $filter_model,
			'filter_price'	     => $filter_price,
			'filter_quantity'    => $filter_quantity,
			'filter_category_id' => $filter_category_id,
			'filter_status'      => $filter_status,
			'sort'               => 'pd.name',
			'order'              => 'ASC',
			'start'              => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'              => $this->config->get('config_admin_limit')
		);
		
    $this->load->model('catalog/category');
		$results_select = $this->model_catalog_category->getCategories(0);
		$this->data['categories_select'] = array();
		foreach ($results_select as $_result) {
			$this->data['categories_select'][] = array(
				'category_id' => $_result['category_id'],
				'name'        => $_result['name'],
				'sort_order'  => $_result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($_result['category_id'], $this->request->post['selected']),
				'action'      => array()
			);
		}
		
		$this->load->model('tool/product_discount');
		$this->load->model('tool/image');

		
		$customer_total = $this->model_tool_product_discount->getTotalProducts($data);
	
		$results = $this->model_tool_product_discount->getProducts($data);
 			$this->data['products'] = array();

		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
 			
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/product_discount', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
			$product_specials = $this->model_tool_product_discount->getProductSpecials($result['product_id']);
			$product_discounts = $this->model_tool_product_discount->getProductDiscounts($result['product_id']);
			
			$this->data['products'][] = array(
				'product_id'    					=> $result['product_id'],
				'image'    								=> $image,
				'name'   									=> $result['name'],
				'model'    								=> $result['model'],
				'price'    								=> $result['price'],
				'quantity'   							=> $result['quantity'],
				'minimum'   							=> $result['minimum'],
				'status'        					=> ($result['status'] ? ($result['status'] == 1?$this->language->get('text_status_enabled'):$this->language->get('text_status_disabled')):'&nbsp;'), //$this->language->get('text_enabled') : $this->language->get('text_disabled')
				'product_specials'   			=> $product_specials,
				'product_discounts'   		=> $product_discounts,
				'action'        					=> $action
			);
		}	
		
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}
		
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}		

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}		

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/product_discount', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_status'] = $filter_status;
		
			

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('text_module'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/product_discount', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$_page = '';
		if (isset($this->request->get['page'])) {
			$_page .= '&page=' . $this->request->get['page'];
		} else {
			$_page = '&page=1';
		}

		$this->data['action'] = $this->url->link('module/product_discount', 'token=' . $this->session->data['token'] . $url	.$_page, 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

	
		$this->data['filter_category'] = '*';
		
		$this->data['sort'] = '';
		$this->data['order'] = '';
				

		$this->template = 'module/product_discount.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	
	private function updateProducts() {
		$this->load->model('tool/product_discount');

     $products = $this->request->post['product'];
     $product_special = (isset($this->request->post['product_special'])?$this->request->post['product_special']:array());
     $product_discount = (isset($this->request->post['product_discount'])?$this->request->post['product_discount']:array());
     
	   if(!empty($this->request->post['eproduct_id'])) {
        $product_id = $this->request->post['eproduct_id'];
        if(isset($products[$product_id])) {
          foreach(array($products[$product_id]) as $key => $product) {
             $this->model_tool_product_discount->updateProduct($product_id, $product);
             $_product_special = (isset($product_special[$product_id])?$product_special[$product_id]:array());
             $_product_discount = (isset($product_discount[$product_id])?$product_discount[$product_id]:array());

             $this->model_tool_product_discount->deleteSpecialProduct($product_id);
             if(count($_product_special)) {
              foreach($_product_special as $k =>$sproduct) {
                $this->model_tool_product_discount->updateSpecialProduct($product_id, $sproduct);
              }
             }
             $this->model_tool_product_discount->deleteDiscountProduct($product_id);
             if(count($_product_discount)) {
               foreach($_product_discount as $k =>$dproduct) {
                $this->model_tool_product_discount->updateDiscountProduct($product_id, $dproduct);
              }
            }
            
          }
        }
     } else {
          foreach($products as $_product_id => $product) {
             $this->model_tool_product_discount->updateProduct($_product_id, $product);
             $_product_special = (isset($product_special[$_product_id])?$product_special[$_product_id]:array());
             $_product_discount = (isset($product_discount[$_product_id])?$product_discount[$_product_id]:array());

             $this->model_tool_product_discount->deleteSpecialProduct($_product_id);

             if(count($_product_special)) {
              foreach($_product_special as $k =>$sproduct) {
                $this->model_tool_product_discount->updateSpecialProduct($_product_id, $sproduct);
              }
             }

             $this->model_tool_product_discount->deleteDiscountProduct($_product_id);
             
             if(count($_product_discount)) {
               foreach($_product_discount as $k =>$dproduct) {
                $this->model_tool_product_discount->updateDiscountProduct($_product_id, $dproduct);
              }
            }
            
          }
     
     }
     return true;
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/product_discount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>
