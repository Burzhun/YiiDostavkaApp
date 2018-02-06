<?
if(!isset($warning)){
    $warning=false;
}
/**
 * @var Partner $partner
 */
$cart = CartItem::model()->find(array('condition' => "goods_id = '" . $goods->id . "'" . CartItem::getConditionForSelectCartItems())) ?>
<div class="mainBox food" id="food_<?=$goods->id;?>">
    <?if($goods->action){?>
        <img src="/images/action.png" style="position: absolute;left: 0;top:0;width: 60px;">
    <?}?>
    <div class="padding10">
        <div class="shopBlock">
            <a href="javascript:void(0);" class="shopImg">
                <img
                    src="<?php echo $goods->img == "" ? ($partner->img == "" ? "/images/default" . $this->domain->id . ".jpg" : "/upload/partner/" . $partner->img) : "/upload/goods/" . $goods->img; ?>"
                    style="max-width:70px;">
            </a>

            <div class="shopRight">
                <a href="javascript:void(0);" class="shopTitle"><?= $goods->name ?></a>

                <div class="shopShort"><?= $goods->text; ?></div>
                <div style="clear:both"></div>
                <? if($goods->price){?>
                    <div class="foodPrice">
                        <?php echo $goods->getOrigPrice($this->domain->id); ?> <?php echo City::getMoneyKod($this->domain); ?>
                    </div>
                    <? if($goods->old_price){?>
                        <div class="foodOldPrice">
                            <?php echo $goods->getOldPrice($this->domain->id); ?> <?php echo City::getMoneyKod($this->domain); ?>
                        </div>
                        <style>
                            #food_<?=$goods->id;?> .foodPrice{
                                margin-bottom: 30px;
                            }
                            #food_<?=$goods->id;?> .orderLink{
                                position: relative;
                                top:0px;
                            }
                            @media screen and (max-width:293px) {
                                #food_<?=$goods->id;?> .foodPrice{
                                    margin-bottom: 20px;
                                }
                                #food_<?=$goods->id;?> .orderLink{
                                    top:3px;
                                    position: relative;
                                }
                                #food_<?=$goods->id;?> .count-block{
                                    top:3px;
                                }
                            }                           
                        </style>
                    <?}?>
                    <?if(!$warning){?>
                    <div class='orderLinkBox <? if ($cart) {
                        if ($cart->quality) { ?>activeOrder<? }
                    } ?>'>
                        <div class="count-block">
                            <a class="minus" href="#" data-goodid="<?= $goods->id ?>">-</a>

                            <p id="num_<?= $goods->id ?>"><?= $cart ? $cart->quality : 1 ?></p>
                            <a class="plus" href="#" data-goodid="<?= $goods->id ?>">+</a>
                        </div>
                        <?php if ($partner->status == 1 && $partner->self_status == 1 && !$partner->soon_opening) { // если пользователь зашел напрямую на страницу, то скрываем ему кнопку заказа, чтоб нехуя было блять, сначала пусть заплатять суки, на дворе кризис нахуй, а они деньги зажимаю, крысы блять!!!;?>
                            <a href="#" class="orderLink" data-goodid="<?= $goods->id ?>" param_name="<?=$goods->name;?>" category="<?=$goods->tag->name;?>" price="<?=$goods->price;?>">Заказать</a>
                        <?php } ?>
                    </div>
                    <?}?>
                <?}?>

            </div>
        </div>
    </div>
</div>