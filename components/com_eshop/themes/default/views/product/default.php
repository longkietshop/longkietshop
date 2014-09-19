<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
$uri = JURI::getInstance();
?>
<script src="<?php echo JURI::base(); ?>components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".product-image").colorbox({rel:'colorbox'});
	});
</script>
<div class="row-fluid">
	<div class="span12">
		<h1><?php echo $this->item->product_name; ?></h1>
		<br />
	</div>
</div>
<div class="product-info">
	<div class="row-fluid">
		<div class="span4">
			<a class="product-image" href="<?php echo $this->item->popup_image; ?>">
				<img src="<?php echo $this->item->thumb_image; ?>" class="img-polaroid" />
			</a>
			<?php
			if (count($this->productImages) > 0)
			{
				?>
				<div class="image-additional">
					<?php
					for ($i = 0; $n = count($this->productImages), $i < $n; $i++)
					{
						?>
							<a class="product-image" href="<?php echo $this->productImages[$i]->popup_image; ?>">
								<img src="<?php echo $this->productImages[$i]->thumb_image; ?>" />
							</a>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
		<div class="span8">
        	<div class="row-fluid">
            	<div>
                    <div class="product-desc">
                        <address>
                            <strong><?php echo JText::_('ESHOP_BRAND'); ?>:</strong> <span><?php echo isset($this->manufacturer->manufacturer_name) ? $this->manufacturer->manufacturer_name : ''; ?></span><br />
                            <strong><?php echo JText::_('ESHOP_PRODUCT_CODE'); ?>:</strong> <span><?php echo $this->item->product_sku; ?></span><br />
                            <strong><?php echo JText::_('ESHOP_AVAILABILITY'); ?>:</strong> <span>
                                <?php echo $this->item->availability; ?>
                            </span>
                        </address>
                    </div>
                </div>
                <?php
                if (EshopHelper::showPrice())
				{
					?>
	                <div>
	                    <div class="product-price" id="product-price">
	                        <h2>
	                            <strong>
	                                <?php echo JText::_('ESHOP_PRICE'); ?>:
	                                <?php
	                                $productPriceArray = EshopHelper::getProductPriceArray($this->item->id, $this->item->product_price);
	                                if ($productPriceArray['salePrice'])
	                                {
	                                    ?>
	                                    <span class="base-price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['basePrice'], $this->item->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>&nbsp;
	                                    <span class="sale-price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['salePrice'], $this->item->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
	                                    <?php
	                                }
	                                else
	                                {
	                                    ?>
	                                    <span class="price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['basePrice'], $this->item->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
	                                    <?php
	                                }
	                                ?>
	                            </strong><br />
	                            <?php
	                            if (EshopHelper::getConfigValue('tax'))
								{
	                            	?>
	                            	<small>
		                                <?php echo JText::_('ESHOP_EX_TAX'); ?>:
		                                <?php
		                                if ($productPriceArray['salePrice'])
		                                {
											echo $this->currency->format($productPriceArray['salePrice']);
		                                }
		                                else
		                                {
											echo $this->currency->format($productPriceArray['basePrice']);
		                                }
		                                ?>
		                            </small>
	                            	<?php
	                            }
	                            ?>
	                        </h2>
	                    </div>
	                </div>
	                <?php
	                if (count($this->discountPrices))
	                {
	                    ?>
	                    <div>
	                        <div class="product-discount-price">
	                            <?php
	                            for ($i = 0; $n = count($this->discountPrices), $i < $n; $i++)
	                            {
	                                $discountPrices = $this->discountPrices[$i];
	                                echo $discountPrices->quantity.' '.JText::_('ESHOP_OR_MORE').' '.$this->currency->format($this->tax->calculate($discountPrices->price, $this->item->product_taxclass_id, EshopHelper::getConfigValue('tax'))).'<br />';
	                            }
	                            ?>
	                        </div>
	                    </div>
	                    <?php
	                }
				}
                if (count($this->productOptions))
                {
                    ?>
                    <div>
                        <div class="product-options">
                            <h2>
                                <?php echo JText::_('ESHOP_AVAILABLE_OPTIONS'); ?>
                            </h2>
                            <?php
                            for ($i = 0; $n = count($this->productOptions), $i < $n; $i++)
                            {
                                $option = $this->productOptions[$i];
                                $optionValue = $this->optionValues[$i];
                                if (EshopHelper::getConfigValue('catalog_mode') && ($option->option_type == 'Text' || $option->option_type == 'Textarea' || $option->option_type == 'File' || $option->option_type == 'Date' || $option->option_type == 'Datetime'))
								{
                                	continue;
                                }
                                ?>
                                <div id="option-<?php echo $option->product_option_id; ?>">
									<div>
										<?php
		                                if ($option->required && !EshopHelper::getConfigValue('catalog_mode'))
		                                {
		                                    ?>
		                                    <span class="required">*</span>
		                                    <?php
		                                }
		                                ?>
		                                <strong><?php echo $option->option_name; ?>:</strong>
		                                <?php
		                                if ($option->option_type == 'File')
										{
		                                	?>
		                                	<span id="file-<?php echo $option->product_option_id; ?>"></span>
		                                	<?php
		                                }
		                                if ($option->option_desc != '')
										{
		                                	?>
		                                	<p><?php echo $option->option_desc; ?></p>
		                                	<?php
		                                }
		                                else 
		                                {
		                                	?>
		                                	<br/>
		                                	<?php
		                                }
										echo EshopOption::renderOption($this->item->id, $option->id, $option->option_type, $this->item->product_taxclass_id);
		                                ?>
									</div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                if (!EshopHelper::getConfigValue('catalog_mode'))
				{
                	?>
                	<div>
	                	<div class="row-fluid">
	                        <div class="product-cart clearfix">
	                            <div class="span5 no_margin_left">
	                                <label><?php echo JText::_('ESHOP_QTY'); ?>:</label>
	                                <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
	                                <input type="text" class="span3" name="quantity" value="1" />
	                                <button id="add-to-cart" class="btn btn-primary" type="button"><?php echo JText::_('ESHOP_ADD_TO_CART'); ?></button>
	                            </div>
	                            <?php
	                            if (EshopHelper::getConfigValue('allow_wishlist') || EshopHelper::getConfigValue('allow_compare'))
	                            {
	                            	?>
	                            	<div class="span2"><?php echo JText::_('ESHOP_OR'); ?></div>
	                            	<div class="span5">
		                            	<?php
										if (EshopHelper::getConfigValue('allow_wishlist'))
										{
											?>
											<p><a style="cursor: pointer;" onclick="addToWishList(<?php echo $this->item->id; ?>)"><?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?></a></p>
											<?php
										}
										if (EshopHelper::getConfigValue('allow_compare'))
										{
											?>
											<p><a style="cursor: pointer;" onclick="addToCompare(<?php echo $this->item->id; ?>)"><?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?></a></p>
											<?php
										}
										?>
		                            </div>
	                            	<?php
	                            }
	                            ?>
	                        </div>
	                    </div>
	                </div>
                	<?php
                }
                elseif (EshopHelper::getConfigValue('allow_compare'))
                {
                	?>
                	<div>
	                	<p><a style="cursor: pointer;" onclick="addToCompare(<?php echo $this->item->id; ?>)"><?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?></a></p>
	                </div>
                	<?php
                }                
                if (EshopHelper::getConfigValue('allow_reviews'))
				{
                	?>
                	<div>
	                    <div class="product-review">
	                        <p>
	                            <img src="components/com_eshop/assets/images/stars-<?php echo round(EshopHelper::getProductRating($this->item->id)); ?>.png" />
	                            <a onclick="activeReviewsTab();" style="cursor: pointer;"><?php echo count($this->productReviews).' '.JText::_('ESHOP_REVIEWS'); ?></a> | <a onclick="activeReviewsTab();" style="cursor: pointer;"><?php echo JText::_('ESHOP_WRITE_A_REVIEW'); ?></a>
	                        </p>
	                    </div>
	                </div>	
                	<?php
                }
                if (EshopHelper::getConfigValue('social_enable'))
				{
                	?>
                	<div>
						<div class="product-share">
							<div class="ps_area clearfix">
								<?php
								if (EshopHelper::getConfigValue('show_facebook_button'))
								{
									?>
									<div class="ps_facebook_like">
										<div class="fb-like" data-send="true" data-width="<?php echo EshopHelper::getConfigValue('button_width', 450); ?>" data-show-faces="<?php echo EshopHelper::getConfigValue('show_faces', 1); ?>" vdata-font="<?php echo EshopHelper::getConfigValue('button_font', 'arial'); ?>" data-colorscheme="<?php echo EshopHelper::getConfigValue('button_theme', 'light'); ?>" layout="<?php echo EshopHelper::getConfigValue('button_layout', 'button_count'); ?>"></div>
									</div>
									<?php
								}
								if (EshopHelper::getConfigValue('show_twitter_button'))
								{
									?>
									<div class="ps_twitter">
										<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $uri->toString(); ?>" tw:via="ontwiik" data-lang="en" data-related="anywhereTheJavascriptAPI" data-count="horizontal">Tweet</a>
									</div>
									<?php
								}
								if (EshopHelper::getConfigValue('show_pinit_button'))
								{
									?>
									<div class="ps_pinit">
										<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($uri->toString()); ?>&media=<?php echo urlencode($this->item->thumb_image); ?>&description=<?php echo $this->item->product_name; ?>" count-layout="horizontal" class="pin-it-button">Pin It</a>
									</div>
									<?php
								}
								if (EshopHelper::getConfigValue('show_linkedin_button'))
								{
									?>
									<div class="ps_linkedin">
										<?php
										if (EshopHelper::getConfigValue('linkedin_layout', 'right') == 'no-count')
										{
											?>
											<script type="IN/Share"></script>
											<?php
										}
										else 
										{
											?>
											<script type="IN/Share" data-counter="<?php echo EshopHelper::getConfigValue('linkedin_layout', 'right'); ?>"></script>
											<?php
										}
										?>
									</div>
									<?php
								}
								if (EshopHelper::getConfigValue('show_google_button'))
								{
									?>
									<div class="ps_google">
										<div class="g-plusone"></div>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
                	<?php
                }
                ?>
           	</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="tabbable">
				<ul class="nav nav-tabs" id="productTab">
					<li class="active"><a href="#description" data-toggle="tab"><?php echo JText::_('ESHOP_DESCRIPTION'); ?></a></li>
					<li><a href="#specification" data-toggle="tab"><?php echo JText::_('ESHOP_SPECIFICATION'); ?></a></li>
					<?php
					if (EshopHelper::getConfigValue('allow_reviews'))
					{
						?>
						<li><a href="#reviews" data-toggle="tab"><?php echo JText::_('ESHOP_REVIEWS') . ' (' . count($this->productReviews) . ')'; ?></a></li>
						<?php
					}
					?>
					<li><a href="#related-products" data-toggle="tab"><?php echo JText::_('ESHOP_RELATED_PRODUCTS'); ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="description">
						<p><?php echo $this->item->product_desc; ?></p>
					</div>
					<div class="tab-pane" id="specification">
						<?php
						if (!$this->hasSpecification)
						{
							?>
							<div class="no-content"><?php echo JText::_('ESHOP_NO_SPECIFICATION'); ?></div>
							<?php
						}
						else
						{
							?>
							<table class="table table-bordered">
								<?php
								for ($i = 0; $n = count($this->attributeGroups), $i < $n; $i++)
								{
									if (count($this->productAttributes[$i]))
									{
										?>
										<thead>
											<tr>
												<th colspan="2"><?php echo $this->attributeGroups[$i]->attributegroup_name; ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											for ($j = 0; $m = count($this->productAttributes[$i]), $j < $m; $j++)
											{
												?>
												<tr>
													<td width="30%"><?php echo $this->productAttributes[$i][$j]->attribute_name; ?></td>
													<td width="70%"><?php echo $this->productAttributes[$i][$j]->value; ?></td>
												</tr>
												<?php
											}
											?>
										</tbody>
										<?php
									}
									?>
									<?php
								}
								?>
							</table>
							<?php
						}
						?>
					</div>
					<?php
					if (EshopHelper::getConfigValue('allow_reviews'))
					{
						?>
						<div class="tab-pane" id="reviews">
							<?php
							if (count($this->productReviews))
							{
								foreach ($this->productReviews as $review)
								{
									?>
									<div class="review-list">
										<div class="author"><b><?php echo $review->author; ?></b> <?php echo JText::_('ESHOP_REVIEW_ON'); ?> <?php echo JHtml::date($review->created_date, 'm-d-Y h:i A'); ?></div>
										<div class="rating"><img src="components/com_eshop/assets/images/stars-<?php echo $review->rating . '.png'; ?>" alt="" /></div>
										<div class="text"><?php echo nl2br($review->review); ?></div>
									</div>
									<?php
								}
							}
							else
							{
								?>
								<div class="no-content"><?php echo JText::_('ESHOP_NO_PRODUCT_REVIEWS'); ?></div>
								<?php
							}
							?>
							<div class="row-fluid">
								<legend id="review-title"><?php echo JText::_('ESHOP_WRITE_A_REVIEW'); ?></legend>
								<div class="control-group">
									<label class="control-label" for="author"><span class="required">*</span><?php echo JText::_('ESHOP_YOUR_NAME'); ?>:</label>
									<div class="controls docs-input-sizes">
										<input type="text" class="input-large" name="author" id="author" value="" />
									</div>
									<label class="control-label" for="author"><span class="required">*</span><?php echo JText::_('ESHOP_YOUR_REVIEW'); ?>:</label>
									<div class="controls docs-input-sizes">
										<textarea rows="5" cols="40" name="review"></textarea>
									</div>
									<label class="control-label" for="author"><span class="required">*</span><?php echo JText::_('ESHOP_RATING'); ?>:</label>
									<div class="controls docs-input-sizes">
										<?php echo $this->ratingHtml; ?>
									</div>
								</div>
								<input type="button" class="btn btn-primary pull-left" id="button-review" value="<?php echo JText::_('ESHOP_SUBMIT'); ?>" />
							</div>
							<?php
							if (EshopHelper::getConfigValue('show_facebook_comment'))
							{
								?>
								<div class="row-fluild">
									<legend id="review-title"><?php echo JText::_('ESHOP_FACEBOOK_COMMENT'); ?></legend>
									<div class="fb-comments" data-num-posts="<?php echo EshopHelper::getConfigValue('num_posts', 10); ?>" data-width="<?php echo EshopHelper::getConfigValue('comment_width', 400); ?>" data-href="<?php echo $uri->toString(); ?>"></div>
								</div>	
								<?php
							}
							?>
						</div>
						<?php
					}
					?>
					<div class="tab-pane" id="related-products">
						<?php
						if (count($this->productRelations))
						{
							?>
							<ul class="thumbnails related_products">
								<?php
								for ($i = 0; $n = count($this->productRelations), $i < $n; $i++)
								{
									$productRelation = $this->productRelations[$i];
									?>
									<li class="span3">
										<div class="thumbnail">
					            			<a href="<?php echo JRoute::_(EshopRoute::getProductRoute($productRelation->id, EshopHelper::getProductCategory($productRelation->id))); ?>">
					            				<img src="<?php echo $productRelation->thumb_image; ?>" />
					            			</a>
											<div class="caption">
					              				<a href="<?php echo JRoute::_(EshopRoute::getProductRoute($productRelation->id, EshopHelper::getProductCategory($productRelation->id))); ?>">
					              					<h5><?php echo $productRelation->product_name; ?></h5>
					              				</a>
												<?php
												if (EshopHelper::showPrice())
												{
													echo JText::_('ESHOP_PRICE'); ?>:
													<?php
													$productRelationPriceArray = EshopHelper::getProductPriceArray($productRelation->id, $productRelation->product_price);
													if ($productRelationPriceArray['salePrice'])
													{
														?>
														<span class="base-price"><?php echo $this->currency->format($this->tax->calculate($productRelationPriceArray['basePrice'], $productRelation->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>&nbsp;
														<span class="sale-price"><?php echo $this->currency->format($this->tax->calculate($productRelationPriceArray['salePrice'], $productRelation->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
														<?php
													}
													else
													{
														?>
														<span class="price"><?php echo $this->currency->format($this->tax->calculate($productRelationPriceArray['basePrice'], $productRelation->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
														<?php
													}
												}
												?>
					            			</div>
					          			</div>
					        		</li>
									<?php
								}
								?>
							</ul>
							<?php
						}
						else
						{
							?>
							<div class="no-content"><?php echo JText::_('ESHOP_NO_RELATED_PRODUCTS'); ?></div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	// Add to cart button
	jQuery('#add-to-cart').bind('click', function() {
		jQuery.ajax({
			type: 'POST',
			url: 'index.php?option=com_eshop&task=cart.add',
			data: jQuery('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#add-to-cart').attr('disabled', true);
				jQuery('#add-to-cart').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('#add-to-cart').attr('disabled', false);
				jQuery('.wait').remove();
			},
			success: function(json) {
				jQuery('.error').remove();
				if (json['error']) {
					if (json['error']['option']) {
						for (i in json['error']['option']) {
							jQuery('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
						}
					}
				}
				if (json['success']) {
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=cart&layout=mini&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#eshop-cart').html(html);
							jQuery('.eshop-content').hide();
							jQuery.colorbox({
								overlayClose: true,
								opacity: 0.5,
								width: '600px',
								height: '150px',
								href: false,
								html: json['success']['message']
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
		  	}
		});
	});
	// Function to active reviews tab
	function activeReviewsTab()
	{
		jQuery('#productTab a[href="#reviews"]').tab('show');
	}
	// Submit review button
	jQuery('#button-review').bind('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=product.writeReview',
			type: 'post',
			dataType: 'json',
			data: 'product_id=<?php echo $this->item->id; ?>&author=' + encodeURIComponent(jQuery('input[name=\'author\']').val()) + '&review=' + encodeURIComponent(jQuery('textarea[name=\'review\']').val()) + '&rating=' + encodeURIComponent(jQuery('input[name=\'rating\']:checked').val() ? jQuery('input[name=\'rating\']:checked').val() : ''),
			beforeSend: function() {
				jQuery('.success, .warning').remove();
				jQuery('#button-review').attr('disabled', true);
				jQuery('#button-review').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('#button-review').attr('disabled', false);
				jQuery('.wait').remove();
			},
			success: function(data) {
				if (data['error']) {
					jQuery('#review-title').after('<div class="warning">' + data['error'] + '</div>');
				}
				if (data['success']) {
					jQuery('#review-title').after('<div class="success">' + data['success'] + '</div>');
					jQuery('input[name=\'author\']').val('');
					jQuery('textarea[name=\'review\']').val('');
					jQuery('input[name=\'rating\']:checked').attr('checked', '');
				}
			}
		});
	});

	// Function to update price when options are added	
	function updatePrice()
	{
		jQuery.ajax({
			type: 'POST',
			url: 'index.php?option=com_eshop&view=product&id=<?php echo $this->item->id; ?>&layout=price&format=raw',
			data: jQuery('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
			dataType: 'html',
			success: function(html) {
				jQuery('#product-price').html(html);
			}
		});
	}
</script>
<?php
if (count($this->productOptions))
{
	?>
	<script type="text/javascript" src="<?php echo JURI::base(); ?>components/com_eshop/assets/js/ajaxupload.js"></script>
	<?php
	foreach ($this->productOptions as $option)
	{
		if ($option->option_type == 'File')
		{
			?>
			<script type="text/javascript">
				new AjaxUpload('#button-option-<?php echo $option->product_option_id; ?>', {
					action: 'index.php',
					name: 'file',
					data: {
						option : 'com_eshop',
						task : 'product.uploadFile'
					},
					autoSubmit: true,
					responseType: 'json',
					onSubmit: function(file, extension) {
						jQuery('#button-option-<?php echo $option->product_option_id; ?>').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
						jQuery('#button-option-<?php echo $option->product_option_id; ?>').attr('disabled', true);
					},
					onComplete: function(file, json) {
						jQuery('#button-option-<?php echo $option->product_option_id; ?>').attr('disabled', false);
						jQuery('.error').remove();
						if (json['success']) {
							alert(json['success']);
							jQuery('input[name=\'options[<?php echo $option->product_option_id; ?>]\']').attr('value', json['file']);
							jQuery('#file-<?php echo $option->product_option_id; ?>').html(json['file']);
						}
						if (json['error']) {
							jQuery('#option-<?php echo $option->product_option_id; ?>').after('<span class="error">' + json['error'] + '</span>');
						}
						jQuery('.wait').remove();
					}
				});
			</script>
			<?php
		}
	}
}