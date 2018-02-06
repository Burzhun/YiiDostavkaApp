<div id="pop-up-bascet">
	<div class="pop-header">
		<div id="close-pop-up1"></div>
		<img src="/images/bascet-img.png" width="64" height="65">

		<h3>Ваша корзина:</h3>
		<a class="clean-cart">очистить корзину</a>
	</div>
	<div class="popup-bascet ">
		<? if (!empty($cart)) { ?>
			<form action="" method="get" class="scroll-pane form-order">
				<ul class="bascet-list">
					<? foreach ($cart as $c) { ?>
						<li id="cart_item_<? echo $c->goods->id; ?>">
							<label style='width:265px;'>
								<img src="/images/close-product.png" width="15" height="16" class="close-list" param_id="<?=$c->goods->id;?>"
									param_name="<?=$c->goods->name;?>" param_category="<?=$c->goods->tag->name;?>" param_price="<?=$c->price;?>" param_num="<?=$c->quality;?>" onclick="deleteProduct(<? echo $c->goods->id; ?>)">
								<div class="cart-item-img">
									<img src="<?= $c->goods->getImage(); ?>" style=" margin-right:5px">
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
				</ul>

					<?if(CartItem::hasDrinks()){?>
					<ul class="more-cola">
						<? $default_image = 'default_' . $this->domain->id . '.jpg'; ?>
						<?$drinks = Goods::getDrinks($partner->id);?>
						<?if($drinks){?>
							<div class="drink-title">Возможно вы забыли добавить напитки</div>
							<?foreach ($drinks as $drink) {?>
								<li>
									<div class="more-cola-img">
										<?php if ($drink->img) { ?>
							                <img class="updateImg updateImgSuccess<?= $drink->id ?>" src="<?php echo $drink->getImage(); ?>">
							            <?php } else { ?>
							                    <img class="updateImg" src="<?php echo $partner->getImage(); ?>">
							            <?php } ?>
									</div>
									<div class="more-cola-price"><b><?php echo $drink->getOrigPrice($this->domain->id); ?></b> руб. </div>
									<div class="more-cola-add-to-cart">
										<a class="button_order" href="javascript:void(0)" onclick="orderDrink(<?=$drink->id?>)" param_name="<?=$drinks->name;?>" id_param="<?=$drinks->name;?>" price="<?=$drinks->price;?>">Заказать</a>
									</div>
									<div class="more-cola-name"><?=$drink->name?></div>
								</li>
							<?}?>
						<?}?>
					</ul>
					<?}?>
			</form>
		<? } ?>
	</div>
	<div class="popup-o-rightside">
		<p>Сумма заказа: <span id="sum_cart"><? echo $sum; ?></span> <?php echo City::getMoneyKod(); ?></p>
		<? $delivery_cost = $partner->delivery_cost; ?>
		<? if ($partner->free_delivery_sum && $sum >= $partner->free_delivery_sum) {
			$delivery_cost = 0;
		} ?>
		<p>Стоимость доставки: <span
				class="delivery_cost"><? echo $delivery_cost > 0 ? $delivery_cost . " ".City::getMoneyKod() : "бесплатно"; ?></span></p>
		<hr>
		<? $style = ""; ?>
		<? if (!($partner->free_delivery_sum - $sum >= 0 && $partner->free_delivery_sum - $sum < 151)) { ?>
			<? $style = "display: none"; ?>

		<? } ?>
		<span class="delivery_message" style="<?= $style; ?>">
				Дополните ваш заказ на <span class='value'><?= ($partner->free_delivery_sum - $sum); ?></span> рублей, чтобы не платить за доставку.
		</span>

		<p class="in-total">Итого: <span
				id="sum_itogo"><? echo $sum + $delivery_cost; ?></span> <?php echo City::getMoneyKod(); ?></p>


		<? if (Yii::app()->user->role == User::USER || Yii::app()->user->role == User::PARTNER) { ?>
			<p class='oBal'>Заказав, вы получите: <span><img src='/images/iconBal.png'><span
						id="bonus_sum"><? echo round($sum * User::BONUS_PROCENT_FROM_ORDER, 0); ?></span> баллов</span>
			</p>
		<? } ?>
		<div id="popup_order_errors" style="color:red;padding:7px 0 7px 0;"></div>

		<div id="cel">
			<a href="javascript:void(0);" class="checkout" onclick="return true;"></a>
			<span class="loader_cart"><img src="/images/loader2.gif" width="50px"></span>
		</div>
	</div>

</div>


<script type="text/javascript">
	var delivery_cost =<? echo $partner->delivery_cost;?>;
	var delivery_cost_total =<? echo $delivery_cost; ?>;
	var valuta = "<?php echo City::getMoneyKod($this->domain);?>";
	function plusProduct(p_id) {
		$.ajax({
			url: '<? echo CController::createUrl('/cart/plusProduct');?>',
			type: "post",
			dataType: "json",
			cache: false,
			data: {"product": p_id},
			success: function (data) {
				$("#form_number_" + p_id).val(data['count']);
				$("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod();?></b>");
				$("#sum_cart").html(data['sumCart']);
				$("#bonus_sum").html((data['sumCart'] *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed());
				if (data['sum_for_free_delivery'] >= 0) {
					if (data['sum_for_free_delivery'] <= 150) {
						$(".delivery_message .value").html(data['sum_for_free_delivery']);
						$(".delivery_message").show();
					} else {
						$(".delivery_message").hide();
					}
					$(".delivery_cost").html(delivery_cost + " " + valuta);
					delivery_cost_total = delivery_cost;
				} else {
					$(".delivery_cost").html("бесплатно");
					delivery_cost_total = 0;
					$(".delivery_message").hide();
				}
				$("#sum_itogo").html(data['sumCart'] + delivery_cost_total);
			}
		});
	}

	function minusProduct(p_id) {
		$.ajax({
			url: '<? echo CController::createUrl('/cart/minusProduct');?>',
			type: "post",
			dataType: "json",
			cache: false,
			data: {"product": p_id},
			success: function (data) {
				if (data['empty']) {
					$("#pop-up-bascet").remove();
					$("#bascet").html("");
					$("#parent_popup").css("display", "none");
				} else {
					$("#form_number_" + p_id).val(data['count']);
					$("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod();?></b>");
					$("#sum_cart").html(data['sumCart']);
					$("#bonus_sum").html((data['sumCart'] *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed());
					if (data['sum_for_free_delivery'] >= 0) {
						if (data['sum_for_free_delivery'] <= 150) {
							$(".delivery_message .value").html(data['sum_for_free_delivery']);
							$(".delivery_message").show();
						} else {
							$(".delivery_message").hide();
						}
						$(".delivery_cost").html(delivery_cost + " " + valuta);

						delivery_cost_total = delivery_cost;
					} else {
						$(".delivery_cost").html("бесплатно");
						delivery_cost_total = 0;

					}
					$("#sum_itogo").html(data['sumCart'] + delivery_cost_total);
				}
			}
		});
	}

	function editProduct(p_id) {
		$.ajax({
			url: '<? echo CController::createUrl('/cart/editProduct');?>',
			type: "post",
			dataType: "json",
			cache: false,
			data: {"product": p_id, "count": $("#form_number_" + p_id).attr('value')},
			success: function (data) {
				if (data['empty']) {
					$("#pop-up-bascet").remove();
					$("#bascet").html("");
					$("#parent_popup").css("display", "none");
				} else {
					$("#form_number_" + p_id).val(data['count']);
					$("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod();?></b>");
					$("#sum_cart").html(data['sumCart']);
					$("#bonus_sum").html((data['sumCart'] *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed());
					if (data['sum_for_free_delivery'] >= 0) {
						if (data['sum_for_free_delivery'] <= 150) {
							$(".delivery_message .value").html(data['sum_for_free_delivery']);
							$(".delivery_message").show();
						} else {
							$(".delivery_message").hide();
						}
						$(".delivery_cost").html(delivery_cost + " " + valuta);
						delivery_cost_total = delivery_cost;
					} else {
						$(".delivery_cost").html("бесплатно");
						delivery_cost_total = 0;
						$(".delivery_message").hide();
					}
					$("#sum_itogo").html(data['sumCart'] + delivery_cost_total);
				}
			}
		});
	}

	function deleteProduct(p_id) {
		$.ajax({
			url: '<? echo CController::createUrl('/cart/deleteProduct');?>',
			type: "post",
			dataType: "json",
			cache: false,
			data: {"product": p_id},
			success: function (data) {
				if (data['empty']) {
					$("#pop-up-bascet").remove();
					$("#bascet").html("");
					$("#parent_popup").css("display", "none");
				} else {
					$("#sum_cart").html(data['sumCart']);
					$("#cart_item_" + p_id).remove();
					$("#bonus_sum").html((data['sumCart'] *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed());
					if (data['sum_for_free_delivery'] >= 0) {
						if (data['sum_for_free_delivery'] <= 150) {
							$(".delivery_message .value").html(data['sum_for_free_delivery']);
							$(".delivery_message").show();
						} else {
							$(".delivery_message").hide();
						}
						$(".delivery_cost").html(delivery_cost + " " + valuta);
						delivery_cost_total = delivery_cost;
					} else {
						$(".delivery_cost").html("бесплатно");
						delivery_cost_total = 0;
						$(".delivery_message").hide();
					}
					$("#sum_itogo").html(data['sumCart'] + delivery_cost_total);
				}
			}
		});
	}


	$(document).on("click", ".clean-cart", function (event) {
		$.ajax({
			url: '<? echo CController::createUrl('/cart/deleteAllProduct');?>',
			type: "post",
			//dataType:"json",
			//cache:false,
			success: function (data) {
				if (data != false) {
					$("#pop-up-bascet").remove();
					$("#bascet").html("");
					$("#parent_popup").css("display", "none");
				}
			}
		});
	});

</script>
