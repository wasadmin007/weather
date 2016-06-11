<?php
/**
* BrainyFilter 2.2, April 19, 2014 / brainyfilter.com
* Copyright 2014 Giant Leap Lab / www.giantleaplab.com
* License: Commercial. Reselling of this software or its derivatives is not allowed. You may use this software for one website ONLY including all its subdomains if the top level domain belongs to you and all subdomains are parts of the same OpenCart store.
* Support: support@giantleaplab.com
*/
?>

<?php if ($priceMin || $priceMax || $manufacturers || ($attr_groups && count($attr_groups))) : ?>
<div class="box">
    <div class="box-heading"><?php echo $attr_setting['block_title']; ?></div>
    <div id="brainyfilter-panel" class="box-content">
		<form id="ps-module-filter-form" method="get" action="index.php">
			<input type="hidden" name="route" value="product/category" />
			<input type="hidden" name="path" value="<?php echo $this->data['path']; ?>" />
			<?php if(isset($_GET['filter']) && !isset($filter_groups)):?><input type="hidden" name="filter" value="<?php echo $_GET['filter']; ?>" /><?php endif;?>
			<?php foreach ($count as $item) : ?>
			<?php if ($attr_setting['price_filter'] || $priceMin && $priceMax) :?>
				<?php if ($sort_price == $item) :?>
				<div class="bf-attr-block">
					<div class="bf-attr-header  <?php echo $collapse_price?'collapse':'';?>" <?php if ($sort_price > 1) {echo 'style="border-style: solid none none;"'; }?> ><?php echo $lang_price; ?><span class="bf-arrow"></span></div>
					<div class="bf-attr-block-cont">
						<div id="bf-price-container" class="box-content bf-attr-filter ">
							<div>
								<span class="bf-cur-symb-left"><?php echo $currency_symbol; ?></span>
								<input type="text" id="bf-range-min" name="lower" value="<?php echo round($lowerlimit); ?>" maxlength="<?php echo strlen(round($priceMax)); ?>" size="4" />
								<span class="ndash">&#8211;</span>
								<span class="bf-cur-symb-left"><?php echo $currency_symbol; ?></span>
								<input type="text" id="bf-range-max" name="higher" value="<?php echo round($upperlimit); ?>" maxlength="<?php echo strlen(round($priceMax)); ?>" size="4" /> 
							</div>
							<div id="slider-container">
								<div id="bf-slider-range"></div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php endif; ?>
			<?php if ($stock_statuses && count($stock_statuses) && $sort_stock == $item) : ?>
				<div class="bf-attr-block">
					<div class="bf-attr-header <?php echo $collapse_stock?'collapse':'';?>"><?php echo $lang_stock_status; ?> <span class="bf-arrow"></span></div>
					<div class="bf-attr-block-cont">
						<?php foreach ($stock_statuses as $status) : ?>
						<div class="bf-attr-filter bf-attr-s<?php echo $status['stock_status_id']; ?> bf-row">
							<span class="bf-cell bf-c-1">
								<input id="stock_status_<?php echo $status['stock_status_id']; ?>"
									   type="checkbox" name="stock_status[]"
									   value="<?php echo $status['stock_status_id']; ?>"  <?php echo (in_array($status['stock_status_id'], $selected_statuses)) ? 'checked="true"' : ''; ?> />
							</span>
							<span class="bf-cell bf-c-2">
								<span class="bf-hidden bf-attr-val"><?php echo $status['stock_status_id']; ?></span>
								<label for="stock_status_<?php echo $status['stock_status_id']; ?>"><?php echo $status['name']; ?></label>
							</span>
							<span class="bf-cell bf-c-3"></span>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($manufacturers && $sort_manufacturer ==  $item) : ?>
				<div class="bf-attr-block">
					<div class="bf-attr-header <?php echo $collapse_manufacturer?'collapse':'';?>"><?php echo $lang_manufacturers; ?> <span class="bf-arrow"></span></div>
					<div class="bf-attr-block-cont">
					<?php foreach ($manufacturers as $manufacturer) : ?>
						<div class="bf-attr-filter bf-attr-mn<?php echo $manufacturer['manufacturer_id']; ?> bf-row">
							<span class="bf-cell bf-c-1">
								<input id="manufacturer_<?php echo $manufacturer['manufacturer_id']; ?>"
									   type="checkbox" name="manufacturer[]"
									   value="<?php echo $manufacturer['manufacturer_id']; ?>"  <?php echo (in_array($manufacturer['manufacturer_id'], $selected_manufacturer)) ? 'checked="true"' : ''; ?> />
							</span>
							<span class="bf-cell bf-c-2">
								<span class="bf-hidden bf-attr-val"><?php echo $manufacturer['manufacturer_id']; ?></span>
								<label for="manufacturer_<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></label>
							</span>
							<span class="bf-cell bf-c-3"></span>
						</div>
					<?php endforeach; ?>						
					</div>
				</div>
			<?php endif; ?>
			<?php if ($enable_attr && $sort_attr ==  $item) : ?>
			<?php if ($attr_groups && count($attr_groups)) : ?>
				<?php foreach ($attr_groups as $attrGroup) : ?>
					<?php if (isset($attr_setting['attr_group']) && $attr_setting['attr_group']) : ?>
						<div class="bf-attr-group-header"><?php echo $attrGroup['name']; ?></div>
					<?php endif; ?>
					<?php foreach ($attrGroup['attributes'] as $attrId => $attr) : ?>
					<div class="bf-attr-block">
						<div class="bf-attr-header bf-attr-<?php echo $attrId; ?> <?php echo $collapse_attr?'collapse':'';?>"><?php echo $attr['name']; ?><span class="bf-arrow"></span></div>
						<div class="bf-attr-block-cont">
						<?php if (isset($this->attr_setting['expanded_attribute_'.$attrId])) :?>
						<?php if ($attr_setting['display_attribute_' . $attrId] == 'select') : ?>
							<div class="bf-attr-filter bf-attr-<?php echo $attrId; ?> bf-row">
								<div class="bf-cell">
									<select name="attribute_value[<?php echo $attrId; ?>][]">
										<option value="" class="bf-default"><?php echo $default_value_select; ?></option>
										<?php foreach ($attr['values'] as $val) : ?>
											<?php $sel = isset($selected_attr[$attrId]) && in_array($val, $selected_attr[$attrId]); ?>
											<option value="<?php echo $val; ?>" <?php echo ($sel) ? 'selected="true"' : ''; ?> class="bf-attr-val"><?php echo $val; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						<?php else : ?>
							<?php $i = 0; ?>
							<?php foreach ($attr['values'] as $val) : ?>
								<?php if ($val) :?>
									
								<?php $sel = isset($selected_attr[$attrId]) && in_array($val, $selected_attr[$attrId]); ?>
								<div class="bf-attr-filter bf-attr-<?php echo $attrId; ?> bf-row">
									<span class="bf-cell bf-c-1">
										<input id="bf-attr-<?php echo $attrId . '-' . $i; ?>" type="<?php echo $attr_setting['display_attribute_' . $attrId]; ?>" name="attribute_value[<?php echo $attrId; ?>][]" value="<?php echo $val; ?>" <?php echo ($sel) ? 'checked="true"' : ''; ?>/>
									</span>
									<span class="bf-cell bf-c-2">
										<label for="bf-attr-<?php echo $attrId . '-' . $i++; ?>" class="bf-attr-val"><?php echo $val; ?></label>
									</span>
									<span class="bf-cell bf-c-3"></span>
								</div>
							<?php endif; ?>
							<?php endforeach; ?>

						<?php endif; ?>
						<?php endif; ?>
						</div>
					</div>
					<?php endforeach; ?>

				<?php endforeach; ?>
			<?php endif; ?>
			<?php endif; ?>
			<?php if (isset($filter_groups) && $sort_opencart_filters ==  $item) : ?>
				<?php foreach ($filter_groups as $filter_group) : ?>
					<div class="bf-attr-block">
					<div class="bf-attr-header bf-attr-<?php echo $filter_group['filter_group_id']; ?> <?php echo $collapse_opencart_filters?'collapse':'';?>"><?php echo $filter_group['name']; ?><span class="bf-arrow"></span></div>
					<div class="bf-attr-block-cont">
					<?php foreach ($filter_group['filter'] as $filter) : ?>
						<div class="bf-attr-filter bf-attr-f<?php echo $filter['filter_id']; ?> bf-row bf-opencart-filters">
							<span class="bf-cell bf-c-1">
								<input id="filter_<?php echo $filter['filter_id']; ?>"
									   type="checkbox" name="filter[]"
									   value="<?php echo $filter['filter_id']; ?>" <?php echo (in_array($filter['filter_id'], $selected_filter)) ? 'checked="true"' : ''; ?>/>
							</span>
							<span class="bf-cell bf-c-2">
								<span class="bf-hidden bf-attr-val"><?php echo $filter['filter_id']; ?></span>
								<label for="filter_<?php echo $filter['filter_id']; ?>"><?php echo $filter['name']; ?></label>
							</span>
							<span class="bf-cell bf-c-3"></span>
						</div>
					<?php endforeach; ?>						
					</div>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ($rating && $sort_rating ==  $item) : ?>
					<div class="bf-attr-block">
					<div class="bf-attr-header bf-attr-1 <?php echo $collapse_rating?'collapse':'';?>"><?php echo $lang_rating;?><span class="bf-arrow"></span></div>
					<div class="bf-attr-block-cont">
					<?php for ($i=5; $i >0 ; $i--): ?>
						<div class="bf-attr-filter bf-attr-r<?php echo $i; ?> bf-row">
							<span class="bf-cell bf-c-1">
								<input id="rating_<?php echo $i; ?>"
									   type="checkbox" name="bfrating[]"
									   value="<?php echo $i; ?>" <?php echo (in_array($i, $selected_rating)) ? 'checked="true"' : ''; ?>/>
							</span>
							<span class="bf-cell bf-c-2">
								<span class="bf-hidden bf-attr-val"><?php echo $i; ?></span>
								<label for="rating_<?php echo $i; ?>"><?php echo $i; ?></label>
							</span>
							<span class="bf-cell bf-c-3"></span>
						</div>
					<?php endfor; ?>						
					</div>
				</div>
			<?php endif; ?>

			<?php if ($options && count($options) && $sort_option == $item) : ?>
				<div class="bf-attr-group-header" style="display:none;"><?php echo $lang_option; ?></div>
				<?php foreach ($options as $optionId => $option) :?>
				<div class="bf-attr-block">
					<div class="bf-attr-header <?php //echo $collapse_option?'collapse':'';?>"><?php echo $option['name']; ?><span class="bf-arrow"></span></div>
					<div class="bf-attr-block-cont">
                        <?php if ($option['type'] == 'select') : ?>
                        <div class="bf-attr-filter bf-attr-o<?php echo $optionId; ?> bf-row">
                            <div class="bf-cell">
                                <select name="bfoption[]">
                                    <option value="" class="bf-default"><?php echo $default_value_select; ?></option>
                                    <?php foreach ($option['values'] as $k => $optValue) : ?>
                                        <option value="<?php echo $optValue['id']; ?>" class="bf-attr-val"><?php echo $optValue['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php else: ?>
                        <?php foreach ($option['values'] as $k => $optValue) : ?>
						<div class="bf-attr-filter bf-attr-o<?php echo $optionId; ?> bf-row">
							<span class="bf-cell bf-c-1">
								<input id="bfoption_<?php echo $optValue['id']; ?>"
									   type="<?php echo ($option['type'] == "radio") ? 'radio' : 'checkbox'; ?>" name="bfoption[]"
									   value="<?php echo $optValue['id']; ?>"  <?php echo (in_array($optValue['id'], $selected_option)) ? 'checked="true"' : ''; ?> />
							</span>
							<span class="bf-cell bf-c-2">
								<span class="bf-hidden bf-attr-val"><?php echo $optValue['id']; ?></span>
								<label for="bfoption_<?php echo $optValue['id']; ?>"><?php if ($attr_setting['image_and_label'] && $optValue['image']) : ?><img src="image/<?php echo $optValue['image'];?>"><?php endif ?><?php if ($attr_setting['image_and_label'] !=1 || !$optValue['image']) : ?><?php echo $optValue['name'];?><?php endif; ?>
								</label>
							</span>
							<span class="bf-cell bf-c-3"></span>
						</div>
                        <?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
                <?php endforeach; ?>
            <?php endif; ?>
			<?php endforeach; ?>

			<div class="box-content">
			<?php if (isset($attr_setting['submit_type']) && $attr_setting['submit_type'] == 'button') : ?>
				<input type="submit" value="<?php echo $lang_submit; ?>" class="button bf-buttonsubmit" onclick="$(BrainyFilter.filterFormId).submit();BrainyFilter.loadingAnimation();return false;" />
			<?php else : ?>
				<noscript>
					<input type="submit" value="<?php echo $lang_submit; ?>" class="button bf-buttonsubmit" />
				</noscript>
			<?php endif; ?>
   			<input type="reset" class="bf-buttonclear" onclick="BrainyFilter.reset();" value="<?php echo $reset; ?>" />
		</div>
		</form>
    </div>
</div>
<script>
 

	var bfLang = {
		show_more : '<?php echo $lang_show_more; ?>',
		show_less : '<?php echo $lang_show_less; ?>',
		vqm_error : '<?php echo $lang_vqmod_error; ?>' 
	};
	jQuery(function() {
		  
		BrainyFilter.init({
			min: <?php echo $priceMin; ?>, 
			max: <?php echo $priceMax; ?>, 
			lowerValue: <?php echo $lowerlimit; ?>, 
			higherValue: <?php echo $upperlimit; ?>,
			price_filter: <?php echo $attr_setting['price_filter'] ? 'true' : 'false'; ?>,
			productCounts: <?php echo $attr_setting['product_count'] ? 'true' : 'false'; ?>,
			currencySymbolSide: "<?php echo $cur_symbol_side; ?>",
			currencySymbol: "<?php echo $currency_symbol; ?>",
			enableSliding: <?php echo $sliding; ?>,
			limitHeight: <?php echo $limit_height; ?>,
			limitHeightOpts: <?php echo $limit_height_opts; ?>,
			visibleItems: <?php echo $slidingOpts; ?>,
			hideItems: <?php echo $slidingMin; ?>,
			urlSeparators: {
				attr: "<?php echo ModelModuleBrainyFilter::SEPARATOR_ATTR; ?>",
				val : "<?php echo ModelModuleBrainyFilter::SEPARATOR_VAL; ?>",
				valsOpen : "<?php echo ModelModuleBrainyFilter::SEPARATOR_VAL_OPEN; ?>",
				valsClose: "<?php echo ModelModuleBrainyFilter::SEPARATOR_VAL_CLOSE; ?>"
			},
			hidePanel: <?php echo $attr_setting['hide_panel'] ? 'true' : 'false'; ?>,
<?php if (isset($attr_setting['submit_button_type']) && !empty($attr_setting['submit_button_type'])) : ?>
			submitBtnType: "<?php echo $attr_setting['submit_button_type']; ?>",
<?php endif; ?>
<?php if (isset($attr_setting['submit_delay_time']) && !empty($attr_setting['submit_delay_time'])) : ?>
			submitDelay: <?php echo (int)$attr_setting['submit_delay_time'] ; ?>,
<?php endif; ?>
			submitType: "<?php echo $attr_setting['submit_type']; ?>"
		});
	});
</script>
<?php endif; ?>