<select style="width:100%" name="Order[address]" id="Order_address">
    <?php foreach ($address as $a){ ?>
    <option value="<?php echo $a->id ?>"><?php echo $a->street; ?> <?php echo $a->house ?>
        <?php } ?>
</select>