
	<? foreach($options as $id => $text){ ?>
		<option 
			<? if ($this->object->id == $id){ ?> selected="selected" <? } ?>
		value="<?=htmlspecialchars($id)?>"><?=htmlspecialchars($text)?></option>
	<? } ?>
