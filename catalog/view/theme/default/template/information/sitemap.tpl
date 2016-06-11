<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<script type="text/javascript">
  function showProduct(){
    if ( $( ".productdata").is( ":hidden" ) ) {
        $(".productdata").slideToggle("slow");
    }else{
      $(".productdata").slideToggle("slow");
    }
    
}

  function showProductCat(cid){
    if ( $( "#productdata"+cid).is( ":hidden" ) ) {
          $("#productdata"+cid).slideToggle("slow");
          $("#showp"+cid).attr("src", '<?php echo HTTP_SERVER;?>image/data/arrow-down.png');
    }else{
      $("#productdata"+cid).slideToggle("slow");
      $("#showp"+cid).attr("src", '<?php echo HTTP_SERVER;?>image/data/arrow-right.png');
    }
}

</script>
<style>
  .productdata ul li a{
    color: #38B0E3;
    cursor: pointer;
    text-decoration: none;
  }
  .productdata ul li a:hover{
    cursor: pointer;
    text-decoration: underline;
  }
  .productdata ul li{
    list-style:decimal;
    font-size: 8px;
  }
</style>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="sitemap-info">
    <div class="left">
      <a href="javascript:void(0);" onclick='showProduct();'>Expand All Products</a>
      <ul>
        <?php foreach ($categories as $category_1) { ?>
        <li>
          <img style="cursor: pointer;" src="<?php echo HTTP_SERVER;?>image/data/arrow-right.png" id="showp<?php echo $category_1['category_id']; ?>" onclick='showProductCat("<?php echo $category_1['category_id']; ?>")'>
          <a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
          <div id="productdata<?php echo $category_1['category_id'];?>" class="productdata" style="display: none;">
              <?php if(!empty($products[$category_1['category_id']])){?>
                    <ul>
                      <?php foreach ($products[$category_1['category_id']] as $product) {
                              $plink = $product['href'];
                              $pname = $product['name'];
                              echo '<li><a href="'.$plink.'">'.$pname.'</a></li>';
                      ?>
                  <?php }?>
                  </ul>
              <?php }?>
            </div>
          <?php if ($category_1['children']) { ?>
          <ul>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <li>
              <img style="cursor: pointer;" src="<?php echo HTTP_SERVER;?>image/data/arrow-right.png" id="showp<?php echo $category_2['category_id']; ?>" onclick='showProductCat("<?php echo $category_2['category_id']; ?>")'>
              <a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a>
                <div id="productdata<?php echo $category_2['category_id'];?>" class="productdata" style="display: none;">
                  <?php if(!empty($products2[$category_2['category_id']])){?>
                        <ul>
                          <?php foreach ($products2[$category_2['category_id']] as $product2) {
                                  $plink = $product2['href'];
                                  $pname = $product2['name'];
                                  echo '<li><a href="'.$plink.'">'.$pname.'</a></li>';
                          ?>
                      <?php }?>
                      </ul>
                  <?php }?>
                </div>
              <?php if ($category_2['children']) { ?>
              <ul>
                <?php foreach ($category_2['children'] as $category_3) { ?>
                <li>
                  <img style="cursor: pointer;" src="<?php echo HTTP_SERVER;?>image/data/arrow-right.png" id="showp<?php echo $category_3['category_id']; ?>" onclick='showProductCat("<?php echo $category_3['category_id']; ?>")'>
                  <a href="<?php echo $category_3['href']; ?>"><?php echo $category_3['name']; ?></a>
                 <div id="productdata<?php echo $category_3['category_id'];?>" class="productdata" style="display: none;">
                        <?php if(!empty($products3[$category_3['category_id']])){?>
                              <ul>
                                <?php foreach ($products3[$category_3['category_id']] as $product3) {
                                        $plink = $product3['href'];
                                        $pname = $product3['name'];
                                        echo '<li><a href="'.$plink.'">'.$pname.'</a></li>';
                                ?>
                            <?php }?>
                            </ul>
                        <?php }?>
                      </div>           
                </li>
                <?php } ?>
              </ul>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
    <div class="right">
      <ul>
        <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
          <ul>
            <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
            <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
            <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
            <li><a href="<?php echo $history; ?>"><?php echo $text_history; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
          </ul>
        </li>
        <li><a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a></li>
        <li><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></li>
        <li><a href="<?php echo $search; ?>"><?php echo $text_search; ?></a></li>
        <li><?php echo $text_information; ?>
          <ul>
            <?php foreach ($informations as $information) { ?>
            <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
            <?php } ?>
            <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>