<?
	$buttonPresets = array(
			'save'=>'<button class="btn" type="submit" name="action" value="save">Сохранить</button>',
			'apply'=>'<button class="btn" type="submit" name="action" value="save">Применить</button>',
			'delete'=>'<button class="btn" type="button" onclick="$(this).next().toggle()">Удалить</button><button name="action" value="delete" class="btn" style="display: none;"><i class="icon-ok"></i></button>',
			'close'=>'<button class="btn" type="button" onclick="window.close();">Закрыть</button>',
			'cancel'=>'<button class="btn" type="button" onclick="window.close();">Отмена</button>',
	)
?>


<footer>
	
	<? foreach(array('left', 'right') as $bar){ ?>
	<ol class="<?=$bar?>">
		<? foreach($this->{$bar.'Bar'} as $button){ 
			if ($button == 'separator') {
				echo '<li class="separator"></li>';
				continue;
			}
			if(empty($button['type']))
			{
				$button['type'] = 'button';
			}
			switch ($button['type']){
				case 'button':
					if (isset($button['preset'])){
						echo '<li>'.$buttonPresets[$button['preset']].'</li>';
					}
					break;
				case 'html':
					echo $button['text'];
					break;

			}
		} ?>
	</ol>
	<? } ?>

</footer>