<?/**
 * @var string $region
 * @var Partner $partner
 */?>
<div id="address-mag">
    <p> адрес: <?= $region ?>, г.<? echo $partner->city->name; ?><br>
        <? echo $partner->address; ?>
        <br>
        рабочие дни:<? if ($partner->day1) { ?> пн <? } ?>
        <? if ($partner->day2) { ?> вт <? } ?>
        <? if ($partner->day3) { ?> ср <? } ?>
        <? if ($partner->day4) { ?> чт <? } ?>
        <? if ($partner->day5) { ?> пт <? } ?>
        <? if ($partner->day6) { ?> сб <? } ?>
        <? if ($partner->day7) { ?> вс <? } ?>
        <br>
        рабочее время: с <? echo date('H:i', strtotime($partner->work_begin_time)); ?>
        до <? echo date('H:i', strtotime($partner->work_end_time)); ?>
    </p>
</div>