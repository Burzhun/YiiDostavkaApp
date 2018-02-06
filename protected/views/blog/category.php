<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="blok">
        <p class="crumbs"><a href="/">главная</a> / Блог</a></p>
        <div id="filter">
            <h2>Рубрики</h2>
            <? $rubriks = TagInPost::model()->findAll(array('order' => 'pos, id DESC, name')) ?>
            <? foreach ($rubriks as $r) { ?>
                <? if (count($r->posts)) { ?>
                    <a href="/blog/category/<?= $r->tname ?>"><?= $r->name . " (" . count($r->posts) . ")"; ?></a>
                    <br><br>
                <? } ?>
            <? } ?>
        </div>
        <div class="page_the_category" id="suppliers">
            <? $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'summaryText' => '', //summary text
                'emptyText' => 'Нет записей',
            )); ?>
        </div>
    </div>
</div>