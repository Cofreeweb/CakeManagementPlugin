<?php
$classes = array($data['type']);
$hasError = isset($model->validationErrors[$field]);
$isRequired = false;
$isEditor = in_array($field, $model->admin['editorFields']);
$validate = false;

if ($hasError) {
    $classes[] = 'has-error';
}

if ($isEditor) {
    $classes[] = 'is-editor';
}

if (isset($model->validate[$field])) {
    $validate = $model->validate[$field];
} else if (isset($model->validations['default'][$field])) {
    $validate = $model->validations['default'][$field];
}

if ($validate) {
    $isRequired = true;

    if (isset($validate['allowEmpty']) && $validate['allowEmpty']) {
        $isRequired = false;
    } else if (isset($validate['required'])) {
        $isRequired = $validate['required'];
    }

    if ($isRequired) {
        $classes[] = 'is-required';
    }
} ?>



        <?php
        $element = 'default';

        if (!empty($data['habtm'])) {
            $element = 'has_and_belongs_to_many';

        } else if ($field === 'id') {
            $element = 'id';

        } else if (in_array($field, $model->admin['fileFields'])) {
            $element = 'file';
        
        } else if (in_array($data['type'], array( 'editor'))) {
            $element = 'editor';

        } else if (in_array($data['type'], array('datetime', 'date', 'time'))) {
            $element = 'datetime';
        }
         else if (in_array($data['type'], array('select'))) { 
            $element = 'select';
        }

        // Value from named param
        $overwriteValue = null;

        if (!empty($this->params['named'][$field])) {
            $overwriteValue = $this->params['named'][$field];
        }
        
        echo $this->element('Management.input/' . $element, array(
            'field' => $field,
            'data' => $data,
            'overwriteValue' => $overwriteValue
        ));

        ?>

    <?php // Include an element that may wrap the input with a wysiwyg
    if ($isEditor && $model->admin['editorElement']) {
        echo $this->element($model->admin['editorElement'], array(
            'inputId' => $this->Form->domId()
        ));
    } ?>
