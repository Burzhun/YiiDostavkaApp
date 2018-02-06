<div class="body-bg bg-none"></div>
<script>
    $(document).ready(function () {
        $(".page-up").click(function () {
            $('html, body').animate({scrollTop: 0}, 500);
        });
    });
</script>

<div id="page" class="page page_blog">
    <? if (isset($_GET['tname'])) { ?>
        <a href="/">Главная</a> / <a href="/blog">Блог</a> /
        <span><?= TagInPost::model()->find(array('condition' => "tname='" . $_GET['tname'] . "'"))->name; ?></span>
    <? } else { ?>
        <a href="/">Главная</a> / <span>Блог</span>
    <? } ?>
    <br><br>

    <div class='blog_content'>
        <? $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_view',
            'summaryText' => '', //summary text
            'emptyText' => 'Нет записей',
        )); ?>
    </div>


    <div class='blog_right_side'>
        <p class='blog_nav_header'>РУБРИКИ</p>
        <ul class='blog_nav'>
            <? $rubriks = TagInPost::model()->findAll(array('order' => 'pos, id DESC, name')) ?>
            <? foreach ($rubriks as $r) { ?>
                <? if (count($r->posts)) { ?>
                    <li>
                        <a href='/blog/category/<?= $r->tname ?>'>
                            <?= $r->name ?><span class='blog_nav_count'><?= count($r->posts) ?></span>
                        </a>
                    </li>
                <? } ?>
            <? } ?>
        </ul>

        <p class='blog_nav_header'>Популярное</p>

        <? $popular = Post::model()->findAll(array('order' => 'view DESC', 'limit' => '3')) ?>
        <? foreach ($popular as $p) { ?>
            <div class='popular_foods'>
                <a href="/blog/<?= $p->id ?>">
                    <img src='/upload/post/<?= $p->img ?>' style="max-width:223px;">
                    <?= $p->title ?>
                </a>
            </div>
        <? } ?>
    </div>
</div>