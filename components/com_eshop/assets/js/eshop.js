// Function to add a product to the cart
function addToCart(productId, quantity, site)
{
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;
	jQuery.ajax({
		url: 'index.php?option=com_eshop&task=cart.add',
		type: 'POST',
		data: 'id=' + productId + '&quantity=' + quantity,
		dataType: 'json',
		beforeSend: function() {
			jQuery('#add-to-cart-' + productId).attr('disabled', true);
			jQuery('#add-to-cart-' + productId).after('<span class="wait-' + productId + '">&nbsp;<img src="' + site + 'components/com_eshop/assets/images/loading.gif" alt="" /></span>');
		},
		success: function(json) {
			if (json['redirect'])
			{
				jQuery('#add-to-cart-' + productId).attr('disabled', false);
				jQuery('.wait-' + productId).remove();
				window.location.href = json['redirect'];
			}
			if (json['success'])
			{
				jQuery.ajax({
					url: 'index.php?option=com_eshop&view=cart&layout=mini&format=raw',
					dataType: 'html',
					success: function(html) {
						jQuery('#add-to-cart-' + productId).attr('disabled', false);
						jQuery('.wait-' + productId).remove();
						jQuery('#eshop-cart').html(html);
						jQuery('.eshop-content').hide();
						jQuery.colorbox({
							overlayClose: true,
							opacity: 0.5,
							width: '500px',
							height: '250px',
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
}

// Function to add a product to the wish list
function addToWishList(productId)
{
	jQuery.ajax({
		url: 'index.php?option=com_eshop&task=wishlist.add',
		type: 'post',
		data: 'product_id=' + productId,
		dataType: 'json',
		success: function(json) {
			if (json['success']) {
				jQuery.colorbox({
					overlayClose: true,
					opacity: 0.5,
					width: '500px',
					height: '250px',
					href: false,
					html: json['success']['message']
				});
			}
		}
	});
}

//Function to remove a product from the wish list
function removeFromWishlist(productId)
{
	jQuery.ajax({
		url: 'index.php?option=com_eshop&task=wishlist.remove',
		type: 'post',
		data: 'product_id=' + productId,
		dataType: 'json',
		success: function(json) {
			window.location.href = json['redirect'];
		}
	});
}

// Function to add product to compare
function addToCompare(productId)
{
	jQuery.ajax({
		url: 'index.php?option=com_eshop&task=compare.add',
		type: 'post',
		data: 'product_id=' + productId,
		dataType: 'json',
		success: function(json) {
			if (json['success']) {
				jQuery.colorbox({
					overlayClose: true,
					opacity: 0.5,
					width: '500px',
					height: '250px',
					href: false,
					html: json['success']['message']
				});
			}
		}
	});
}

//Function to remove a product from compare
function removeFromCompare(productId)
{
	jQuery.ajax({
		url: 'index.php?option=com_eshop&task=compare.remove',
		type: 'post',
		data: 'product_id=' + productId,
		dataType: 'json',
		success: function(json) {
			window.location.href = json['redirect'];
		}
	});
}