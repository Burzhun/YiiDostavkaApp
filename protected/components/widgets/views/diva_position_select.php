
	<? foreach($options as $pos => $text){ ?>
		<option 
			<? if ($this->object->{$this->positionField} == $pos){ ?> selected="selected" <? } ?>
		value="<?=htmlspecialchars($pos)?>"><?=htmlspecialchars($text)?></option>
	<? } ?>
