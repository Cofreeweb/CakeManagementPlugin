<?= $this->Form->input( 'check', array(
  'type' => 'select',
  'multiple' => 'checkbox',
  'label' => 'Las opciones de la muelte',
  'options' => array(
    'uno' => 'Uno',
    'dos' => 'Dos'
  )
)) ?>
<div class="control-group">
	<label class="control-label bolder blue">Checkbox</label>

	<div class="checkbox">
		<label>
			<input name="form-field-checkbox" type="checkbox" class="ace" />
			<span class="lbl"> choice 1</span>
		</label>
	</div>

	<div class="checkbox">
		<label>
			<input name="form-field-checkbox" type="checkbox" class="ace" />
			<span class="lbl"> choice 2</span>
		</label>
	</div>

	<div class="checkbox">
		<label>
			<input name="form-field-checkbox" class="ace ace-checkbox-2" type="checkbox" />
			<span class="lbl"> choice 3</span>
		</label>
	</div>

	<div class="checkbox">
		<label class="block">
			<input name="form-field-checkbox" disabled="" type="checkbox" class="ace" />
			<span class="lbl"> disabled</span>
		</label>
	</div>
</div>