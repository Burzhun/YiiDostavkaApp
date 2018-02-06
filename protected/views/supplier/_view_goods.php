<?
/**
 * @var Partner $partner
 * @var Goods $goods
 * @var int $i
 */
?>
<? $default_image = 'default_' . $this->domain->id . '.jpg'; ?>

<?php $is_right = $i % 3; ?>
<div class="blok_order" id="button1">
    <?if($goods->action){?>
        <img src="/images/action.png" style="position: absolute;left: 0;top:0;">
    <?}?>
    <div style="text-align:-webkit-center">
        <? if (Yii::app()->user->role == 'admin' && !$goods->img) { ?>
            <span class="editor-image" data-name='<?php echo $goods->name ?>'
                  data-id='<?php echo $goods->id ?>'></span>
        <? } ?>

        <img class="img_order updateImg updateImgSuccess<?= $goods->id ?>" src="/images/<?= $default_image; ?>"
             data-id="<?php echo $goods->img == "" ? ($partner->img == "" ? "/images/{$default_image}" : "/upload/partner/" . $partner->img) : "/upload/goods/" . $goods->img; ?>"
            <?php if ($is_right) { ?>
                onmouseover="mouse_over_right(this)" onmouseout="mouse_out_right(this)"
            <?php } else { ?>
                onmouseover="mouse_over_left(this)" onmouseout="mouse_out_left(this)"
            <?php } ?>>
    </div>
    <h4 class="goodsNameText"><?php echo $goods->name ?></h4>
    <form>
        <p style="position: relative" class="product_counter_container" id="product_<?php echo $goods->id ?>_counter_container">
            <?if($goods->price){?>
                <span class="orig_price"><b><?php echo $goods->getOrigPrice($this->domain->id); ?></b> <?php echo City::getMoneyKod($this->domain); ?></span>
                <?if($goods->old_price){?>
                    <span class="old_price"><b style="text-decoration: line-through;color:#9f9d98"><?php echo $goods->getOldPrice($this->domain); ?></b> <?php echo City::getMoneyKod($this->domain); ?></span>
                    <style>
                        #product_<?php echo $goods->id ?>_counter_container .orig_price{
                            position: relative;
                            top: -7px;
                        }
                        #product_<?php echo $goods->id ?>_counter_container .old_price{
                            position: absolute;
                            left:0;
                            top:13px;
                        }
                    </style>
                <?php } ?>
                <?php if ($partner->status == 1 && $partner->self_status == 1) { ?>
                    <a class="showed_product_counter" href="javascript:void(0);">
                        <span class="showcount">1</span> <?php echo $goods->unit ?>
                    </a>
                <?php } ?>
            <?php } ?>
        </p>

        <div class="update_count_product_container">
            <span class="minusupdate"><img src="/images/basket_minus.png" style="padding:0" class="minus"></span>
            <input class="new_count" type="text" value="1" style="width:30px" maxlength="2">
            <span class="plusupdate" width="100px"><img src="/images/basket_plus.png" style="padding:0"
                                                        class="plus"></span>
            <button type="button" class="updatecount_ok" product_id="<?php echo $goods->id; ?>"> Ok</button>
        </div>
        <input class="productcounter" id="productcount_<?php echo $goods->id; ?>" type="hidden" value="1">

        <?php if ($partner->status == 1 && $partner->self_status == 1 && !$partner->soon_opening&&$goods->price) { // если пользователь зашел напрямую на страницу, то скрываем ему кнопку заказа, чтоб нехуя было блять, сначала пусть заплатять суки, на дворе кризис нахуй, а они деньги зажимаю, крысы блять!!!;?>
            <a class="button_order" href="javascript:void(0)" param_name="<?=$goods->name;?>" id_param="<?=$goods->id;?>" category="<?=$goods->tag->name;?>" price="<?=$goods->price;?>"
               onclick="order(<?php echo $goods->id; ?>);return true;">Заказать</a>
        <?php } ?>
    </form>
    <div
        class="<?php echo $is_right ? "productinfoblockright" : "productinfoblockleft"; ?>"><?php //появляющаяся сбоку карточка товара?>
        <?php if ($goods->img) { ?>
            <img class="updateImg updateImgSuccess<?= $goods->id ?>" src="/images/<?= $default_image; ?>"
                 data-id="/upload/goods/<?php echo $goods->img; ?>">
        <?php } else { ?>
            <?php if (!empty($partner->img)) { ?>
                <img class="updateImg" src="/images/<?= $default_image; ?>"
                     data-id="/upload/partner/<?php echo $partner->img; ?>">
            <?php } else { ?>
                <img src="/images/<?= $default_image; ?>">
            <?php } ?>
        <?php } ?>
        <div style="clear:both;float:none;"></div>
        <div>
            <p class="goodsInfoText"><?php echo $goods->text; ?></p>
        </div>
    </div>
</div>