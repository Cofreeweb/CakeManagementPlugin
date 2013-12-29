    <? foreach ($model->fields as $field => $data): ?>
        <? if(( $this->action === 'create' && $field === $model->primaryKey) || in_array($field, $model->admin['hideFields'])) {
            continue;
        } ?>
        
        <?= $this->element( 'Management.input', array(
            'field' => $field,
            'data' => $data
        )) ?>
    <? endforeach ?>
