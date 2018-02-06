<?php
/**
 * @var Partner $partner
 */
?>
<div id="pop-up-bascet">
    <div class="pop-header" style="height:75px;">
        <div id="close-pop-up1"></div>
    </div>
    <?
    $DaysString = Partner::getWorkDays($partner->id);
    $DaysString = str_replace('  ', ' ', $DaysString);
    $DaysString = Partner::getWorkDaysString($DaysString);
    ?>
    <div class="ex">
        <img src="../images/clock2.png">

        <p>
            К сожалению, <span>«<?= $partner->name ?>»</span> в настоящее время заказы не
            принимает.Возможен только предварительный заказ.</p>
    </div>
    <br>
    <br>

    <div class="rasp">
        <?= $DaysString ?>: с <?= substr($partner->work_begin_time, 0, strlen($partner->work_begin_time) - 3) ?> до
        <?= substr($partner->work_end_time, 0, strlen($partner->work_end_time) - 3) ?>
    </div>
    <div class="error-footer">


        <div class="ost"><?= $partner->howLongWill(); ?><br>
            <span>до открытия</span>
        </div>
        <a href="#" class='predzakaz'>Хочу сделать предварительный заказ</a>
        <a href="/restorany" class='findnextr'>Найти другой ресторан</a>
    </div>
</div>

<script type="text/javascript">

</script>

