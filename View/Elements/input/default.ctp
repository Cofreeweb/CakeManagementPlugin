<?php

$params = array(
    'label' => $data ['title'],
    'type' => 'text',
    'default' => isset($data['default']) ? $data['default'] : $overwriteValue,
    'empty' => ($this->action === 'index')
);

switch ($data['type']) {
    case 'text':
        $params['type'] = 'textarea';
    break;
    case 'integer':
        $params['type'] = 'text';
    break;
    case 'boolean':
    case 'bool':
        $params['type'] = 'checkbox';
    break;
    case 'enum':
        $params['type'] = 'select';
        $params['options'] = $this->Utility->enum($model->qualifiedName, $field);
    break;
    case 'array':
    case 'list':
        $params['type'] = 'select';
    break;
    default:
    break;
}

if( !empty( $data ['options']))
{
  $params ['options'] = $data ['options'];
}

if( !empty( $data ['empty']))
{
  $params ['empty'] = $data ['empty'];
}

if ($this->action === 'index') {
    unset($params['class']);
}
echo $this->Form->input($field, $params);