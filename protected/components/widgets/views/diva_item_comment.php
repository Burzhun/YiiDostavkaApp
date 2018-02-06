<?
	$value = $this->object->{$commentField};
	$show = (bool)$value;
?>

<div class="comment">
	<? if (!$show) ?>
	<button 
		type="button"
		class="btn btn-mini" 
		style="<? if ($show){ ?>display:none<? } ?>"
		onclick="$(this).hide().next().show();"
	>добавить комментарий</button>
	<textarea name="<?=htmlspecialchars($this->inputName)?>" style="<? if ($show){ ?>display:block<? } ?>"><?=@htmlspecialchars($value)?></textarea>
</div>