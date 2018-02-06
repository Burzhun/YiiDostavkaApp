<? $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_reviewItem',
    'itemsTagName' => 'ul',
    'itemsCssClass' => 'review',
    'summaryText' => '', //summary text
    'emptyText' => 'Нет отзывов',
    //		    'template'=>',, {items} and {pager}.', //template
    'pagerCssClass' => 'pagers',//contain class
    'pager' => array(
        //	'class' => 'myPager',
        //   		'cssFile'=>false,//disable all css property
        'header' => '',//text before it
        // 'firstPageLabel'=>'',//overwrite firstPage lable
        // 'lastPageLabel'=>'',//overwrite lastPage lable
        // 'nextPageLabel'=>'&nbsp',//overwrite nextPage lable
        // 'prevPageLabel'=>'<li class="prev"><a href="#" title="">&nbsp</a></li>',

    )
)); ?>