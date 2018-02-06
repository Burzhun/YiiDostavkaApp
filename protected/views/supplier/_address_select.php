<select style="width:275px;margin-left:20px;" name="Order[address]" id="Order_address">
    <?php foreach ($address as $a){ ?>
    <option
        value="<?php echo $a->id ?>"><?= City::model()->findByPk($a->city_id)->name; ?> <?php echo $a->street; ?> <?php echo $a->house ?>
        <?php } ?>
</select>