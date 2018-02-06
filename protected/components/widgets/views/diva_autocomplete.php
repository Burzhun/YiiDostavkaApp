
<input type="hidden" class="autocomplete-input-id" name="<?=htmlspecialchars($this->inputName)?>" value="<?=$this->model->{$this->model->asa($this->behavior)->idField}?>" />

<? $this->widget('CAutoComplete', $options); ?>