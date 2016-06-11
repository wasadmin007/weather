<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if (isset($_SESSION['_success']) && !empty($_SESSION['_success'])) { ?>
<div class="success"><?php echo $_SESSION['_success']; ?><div class="close" onclick="$(this).parent().remove();">close</div></div>
<?php $_SESSION['_success'] = '';} ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons">
      <a onclick="save_product(0);" class="button"><span><?php echo $button_save; ?></span></a>
      <a onclick="window.location.href='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
    </div>
  <!--  <img class="save_all"  id="save_all" src="view/image/save_all_24x24.png" onclick="save_product(0);"> -->
  </div>
  <div class="content">

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="list" name="list">
      	<input type="hidden" name="eproduct_id" value="">
        <table class="list">
          <thead>
            <tr>
              <td class="left">
              <?php echo $column_image; ?>
              </td>
              <td class="left">
              <?php echo $column_name; ?>
              </td>
              <td class="left">
              <?php echo $column_model; ?>
              </td>
              <td class="left">
              <?php echo $column_price; ?>
              </td>
              <td class="left">
              <?php echo $column_quantity; ?>
              </td>
              <td class="left">
              <?php echo $column_minimum; ?>
              </td>
              <td class="left">
              <?php echo $column_status; ?>
              </td>

              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td class="left">
              &nbsp;
              </td>
              <td>
              <input type="text" class="flt" name="filter_name" value="<?php echo $filter_name; ?>" />
              </td>
              <td>
              <input type="text" class="flt" name="filter_model" value="<?php echo $filter_model; ?>" />
              </td>
              <td class="left">
              <input type="text" class="flt" name="filter_price" value="<?php echo $filter_price; ?>" size="8"/>
              </td>
              <td class="right">
              <input type="text" class="flt" name="filter_quantity" value="<?php echo $filter_quantity; ?>" style="text-align: right;" />
              </td>
              <td class="center"  style="width: 140px;">
                <select name="filter_category_id" style="width: 140px;">
                  <option value="*"></option>
                  <?php foreach ($categories_select as $category) { ?>
                   <?php if ($filter_category_id == $category['category_id']) { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </td>
              <td class="left">
                <select name="filter_status">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </td>

               <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
            </tr>
          
          	<?php
          		$module_rows = array(
								'special' => array(),
								'discount ' => array()
							);
          	?>
          	<?php foreach($products as $product) { ?>
          	<tr class="p-dicount">
          		<td class="center">
              <?php if(isset($_POST['eproduct_id']) && $_POST['eproduct_id'] == $product['product_id']) { ?>
              <span class="plus">+</span>
              <?php } ?>
              <img src="<?php echo $product['image']; ?>" border="0" alt="<?php echo $product['name']; ?>">
              </td>
          		<td class="left"><?php echo $product['name']; ?></td>
          		<td class="left"><?php echo $product['model']; ?></td>
          		<td class="right"><input type="text" name="product[<?php echo $product['product_id'];?>][price]" value="<?php echo $product['price']; ?>" size="2" /></td>
          		<td class="right"><input type="text" name="product[<?php echo $product['product_id'];?>][quantity]" value="<?php echo $product['quantity']; ?>" size="2" /></td>
          		<td class="right"><input type="text" name="product[<?php echo $product['product_id'];?>][minimum]" value="<?php echo $product['minimum']; ?>" size="2" /></td>
          		<td class="center"><?php echo $product['status']; ?></td>
          		<td class="center"><a name="product-<?php echo $product['product_id'];?>" class="position"></a><img class="icon-button" src="view/image/product_save.png" border="0" alt="save" onclick="save_product('<?php echo $product['product_id'];?>');"></td>
          	</tr>
          	<tr>
          		<td colspan="8">
          		  <fieldset>
          			
          			<legend><h3><?php echo $text_product_special; ?> - <?php echo $product['name']; ?></h3></legend>
				        <table class="list">
				          <thead>
				            <tr>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_customer_group; ?>
				              </td>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_priority; ?>
				              </td>
				              <td class="right" style="background-color: #EFEFEF;">
				              <?php echo $column_price; ?>
				              </td>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_date_start; ?>
				              </td>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_date_end; ?>
				              </td>
				              <td class="right" style="background-color: #EFEFEF;">
				              	<img class="icon-title" src="view/image/product_add.png">
				              <?php echo $column_action; ?>
				              </td>
				
				            </tr>
				          </thead>
				          <tbody id="special_row-<?php echo $product['product_id'];?>">
				            <?php $special_row = 0; ?>
				          	<?php foreach($product['product_specials'] as $product_special) {?>

				              <tr>
				                <td class="left"><select name="product_special[<?php echo $product['product_id'];?>][<?php echo $special_row; ?>][customer_group_id]">
				                    <?php foreach ($customer_groups as $customer_group) { ?>
				                    <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
				                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
				                    <?php } else { ?>
				                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
				                    <?php } ?>
				                    <?php } ?>
				                  </select></td>
				                <td class="right"><input type="text" name="product_special[<?php echo $product['product_id'];?>][<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
				                <td class="right"><input type="text" name="product_special[<?php echo $product['product_id'];?>][<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
				                <td class="left"><input type="text" name="product_special[<?php echo $product['product_id'];?>][<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="date" /></td>
				                <td class="left"><input type="text" name="product_special[<?php echo $product['product_id'];?>][<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="date" /></td>
				                <td class="center"><img class="icon-button" src="view/image/product_delete.png" border="0" onclick="$(this).parent().parent().remove();"></td>
				              </tr>

					            <?php $special_row++; ?>

				          	<?php } ?>
				          	<?php $module_rows['special'][$product['product_id']]= $special_row; ?>
				          </tbody>
				        </table>
								<img class="icon-button" src="view/image/discount_add.png" border="0" onclick="add_product_special('<?php echo $product['product_id'];?>');">
								</fieldset>
							</td>
          	</tr>
          	<tr>
          		<td colspan="8" style="border-bottom: 2px #FFCC99 solid;">
          		  <fieldset>
          			
          			<legend><h3><?php echo $text_product_discount; ?> - <?php echo $product['name']; ?></h3></legend>
				        <table class="list">
				          <thead>
				            <tr>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_customer_group; ?>
				              </td>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_quantity; ?>
				              </td>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_priority; ?>
				              </td>
				              <td class="right" style="background-color: #EFEFEF;">
				              <?php echo $column_price; ?>
				              </td>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_date_start; ?>
				              </td>
				              <td class="left" style="background-color: #EFEFEF;">
				              <?php echo $column_date_end; ?>
				              </td>
				              <td class="right" style="background-color: #EFEFEF;">
				              	<img class="icon-title" src="view/image/product_add.png">
				              <?php echo $column_action; ?>
				              </td>
				
				            </tr>
				          </thead>
				          <tbody id="discount_row-<?php echo $product['product_id'];?>">
			            <?php $discount_row = 0; ?>
				          	<?php foreach($product['product_discounts'] as $product_discount) {?>

			              <tr>
			                <td class="left"><select name="product_discount[<?php echo $product['product_id'];?>][<?php echo $discount_row; ?>][customer_group_id]">
			                    <?php foreach ($customer_groups as $customer_group) { ?>
			                    <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
			                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
			                    <?php } else { ?>
			                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
			                    <?php } ?>
			                    <?php } ?>
			                  </select></td>
			                <td class="right"><input type="text" name="product_discount[<?php echo $product['product_id'];?>][<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
			                <td class="right"><input type="text" name="product_discount[<?php echo $product['product_id'];?>][<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" /></td>
			                <td class="right"><input type="text" name="product_discount[<?php echo $product['product_id'];?>][<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" /></td>
			                <td class="left"><input type="text" name="product_discount[<?php echo $product['product_id'];?>][<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" class="date" /></td>
			                <td class="left"><input type="text" name="product_discount[<?php echo $product['product_id'];?>][<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" class="date" /></td>
			                <td class="center"><img class="icon-button" src="view/image/product_delete.png" border="0" onclick="$(this).parent().parent().remove();"></td>
			              </tr>

					            <?php $discount_row++; ?>

				          	<?php } ?>
				          	<?php $module_rows['discount'][$product['product_id']]= $discount_row; ?>
				          </tbody>
				        </table>
								<img class="icon-button" src="view/image/discount_add.png" border="0" onclick="add_product_discount('<?php echo $product['product_id'];?>');">
							 </fieldset>
							</td>
          	</tr>
          	
          	<?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>

			<p>&nbsp;</p>
			

  </div>
</div>
<?php
	$_special = $_discount = array();
	if(!isset($module_rows['special'])) $module_rows['special'] = array();
	if(!isset($module_rows['discount'])) $module_rows['discount'] = array();
	
	foreach($module_rows['special'] as $p_id => $val) {
		$_special[] = "'".$p_id."':'".$val."'";
	}
	foreach($module_rows['discount'] as $p_id => $val) {
		$_discount[] = "'".$p_id."':'".$val."'";
	}
	print "
	<script type=\"text/javascript\">
		var special_rows = {".(count($_special)?implode(", ", $_special):"")."};
		var discount_rows = {".(count($_discount)?implode(", ", $_discount):"")."};
	</script>
	";
?>
<style type="text/css">

	.save_all {
		float: right;
		margin-top: 7px;
		margin-right: 15px;
		cursor: pointer;
	}
	.icon-button {
		cursor: pointer;
	}
	.icon-title {
		float: left;
	}
	fieldset {
    border: 1px #ddd solid;
    margin: 0px 4px 6px 4px;
    /*box-shadow: 0 0 10px rgba(0,0,0,.3);*/
  }
  legend {
 
    
  }
  .position {
    float: left;
    margin-top: -25px;
  }
  span.plus {
    float: left;
    color: green;
    font-weight: bold;
    font-size: 1.8em;
    margin-left: -20px;
    margin-top: 5px;
  }
  div.close {
    float: right;
    font-size: 10px;
    cursor: pointer;
  }
  tr.p-dicount td {
    background-color: #FAFAD9;
  }
  
</style>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=module/product_discount&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	var filter_price = $('input[name=\'filter_price\']').attr('value');
	
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
	
	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
	
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

	var filter_category_id = $('select[name=\'filter_category_id\']').attr('value');
	
	if (filter_category_id != '*') {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}	
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	

	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('input.flt').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 

<script type="text/javascript"><!--
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});


//$module_rows
function save_product(product_id) {
  $('input[name=\'eproduct_id\']').attr('value', product_id);
  if(product_id != 0) {
    document.list.action += '&#product-'+product_id;
    // = window.location.href+'&#product-'+product_id;
  } 
  //$('#list').submit();
  document.list.submit();
}

function add_product_special(product_id) {
	if(typeof(special_rows[product_id]) != 'undefined') {
		var special_row = special_rows[product_id];
		++special_row;
		var row = '';
		row += '<tr>';
		row += '    <td class="left"><select name="product_special['+product_id+']['+special_row+'][customer_group_id]">';
				    <?php foreach ($customer_groups as $customer_group) { ?>
		row += '      <option value="<?php echo $customer_group["customer_group_id"]; ?>"><?php echo $customer_group["name"]; ?></option>';
				    <?php } ?>
		row += '</select></td>';
		row += '    <td class="right"><input type="text" name="product_special['+product_id+']['+special_row+'][priority]" value="" size="2" /></td>';
		row += '    <td class="right"><input type="text" name="product_special['+product_id+']['+special_row+'][price]" value="" /></td>';
		row += '    <td class="left"><input type="text" name="product_special['+product_id+']['+special_row+'][date_start]" value="" class="date" /></td>';
		row += '    <td class="left"><input type="text" name="product_special['+product_id+']['+special_row+'][date_end]" value="" class="date" /></td>';
		row += '    <td class="center"><img class="icon-button" src="view/image/product_delete.png" border="0" onclick="$(this).parent().parent().remove();"></td>';
		row += '</tr>';
				                  
	   $('#special_row-'+product_id).append(row);		
     $('#special_row-'+product_id + ' .date').datepicker({dateFormat: 'yy-mm-dd'});	                
 
		special_rows[product_id] = special_row;
		
	}
}

function add_product_discount(product_id) {
	if(typeof(discount_rows[product_id]) != 'undefined') {
		var discount_row = discount_rows[product_id];
		++discount_row;
		var row = '';
		row += '<tr>';
		row += '    <td class="left"><select name="product_discount['+product_id+']['+discount_row+'][customer_group_id]">';
				    <?php foreach ($customer_groups as $customer_group) { ?>
		row += '      <option value="<?php echo $customer_group["customer_group_id"]; ?>"><?php echo $customer_group["name"]; ?></option>';
				    <?php } ?>
		row += '</select></td>';
		row += '    <td class="right"><input type="text" name="product_discount['+product_id+']['+discount_row+'][quantity]" value="" size="2" /></td>';
		row += '    <td class="right"><input type="text" name="product_discount['+product_id+']['+discount_row+'][priority]" value="" size="2" /></td>';
		row += '    <td class="right"><input type="text" name="product_discount['+product_id+']['+discount_row+'][price]" value="" /></td>';
		row += '    <td class="left"><input type="text" name="product_discount['+product_id+']['+discount_row+'][date_start]" value="" class="date" /></td>';
		row += '    <td class="left"><input type="text" name="product_discount['+product_id+']['+discount_row+'][date_end]" value="" class="date" /></td>';
		row += '    <td class="center"><img class="icon-button" src="view/image/product_delete.png" border="0" onclick="$(this).parent().parent().remove();"></td>';
		row += '</tr>';


	   $('#discount_row-'+product_id).append(row);			                
     $('#discount_row-'+product_id + ' .date').datepicker({dateFormat: 'yy-mm-dd'});	                

		discount_rows[product_id] = discount_row;
		
	}
}
//--></script>

<?php echo $footer; ?>
