<?php

class DivaController extends Controller
{
	
	public $currentPart = '';
	public $layout = 'diva';
	
	public function closeWindow()
	{
		echo '<script>window.close();</script>';
	}
	
	public function extendGridParams($model, $argParams = array()){
		
		$conrollerId = $this->getId();
		
		$idColumnValue = '
			Diva::popupLink("/admin/'.$conrollerId.'/item/".$data->id, "#".$data->id).
			($data->comment?\'<span title="\'.htmlspecialchars($data->comment).\'"> §</span>\':\'\');
		';
		
		$params = array(
			'id'=>'main-grid',
			'filter'=>$model,
			'itemsCssClass'=>'table table-bordered',
			//'ajaxUpdate'=>false,
				
			'summaryCssClass'=>'',
			'summaryText'=>'Всего: {count}',
			
			'pagerCssClass' => 'unknown-pager',	
			'pager' => array(
					'class'=>'CLinkPager',
					'nextPageLabel' => '→',
					'prevPageLabel' => '←',
					'header'=>'',
					'pageSize'=>20
			),
				
				
			'columns'=>array(
        'id' => array(            
            'name'=>'id',
						'value'=>$idColumnValue,	
						'type'=>'raw',
						'header'=>'ID',	
						'filterHtmlOptions'=>array('class'=>'filter-id'),
        )
			),
		);
		
		$res = array_merge_recursive($params, $argParams);
		
		if (isset($argParams['dataProvider'])){
			$res['dataProvider'] = $argParams['dataProvider'];
		} else {
			$res['dataProvider'] = $model->search();
		}
		
		return $res;
		
	}
	
	
	
}