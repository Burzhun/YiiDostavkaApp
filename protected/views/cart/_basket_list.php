<? if (!empty($cart)) { ?>
	<? foreach ($cart as $c) { ?>
		<li id="cart_item_<? echo $c->goods->id; ?>">
			<label style='width:265px;'>
				<img src="/images/close-product.png" width="15" height="16" class="close-list"
					 onclick="deleteProduct(<? echo $c->goods->id; ?>)">
				<div class="cart-item-img">
					<img src="<?= $c->goods->image ?>" style=" margin-right:5px">
				</div>

				<div class='cart_item_name'><? echo $c->goods->name; ?></div>
			</label>

			<div class="update_count_product_container popup-update">
				<span class="minusupdate" onclick="minusProduct(<? echo $c->goods->id; ?>)">
					<img src="/images/basket_minus.png" width="16" height="16" style="padding:0">
				</span>
				<input onchange="editProduct(<? echo $c->goods->id; ?>)"
					   id="form_number_<? echo $c->goods->id; ?>" class="new_count" type="text"
					   value="<? echo $c->quality; ?>" style="width:30px" maxlength="2">
				<span class="plusupdate" onclick="plusProduct(<? echo $c->goods->id; ?>)">
					<img src="/images/basket_plus.png" width="16" height="16" style="padding:0">
				</span>
			</div>
			<div class="price-in-bascet">
				<label><? echo $c->price; ?> <?php echo City::getMoneyKod(); ?></label><br><label
					id="item_price_<? echo $c->goods->id; ?>"><b><? echo $c->quality * $c->price; ?> <?php echo City::getMoneyKod(); ?></b></label>
			</div>
		</li>
	<? } ?>
<?}?>