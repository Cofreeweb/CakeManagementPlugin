<span class="type string"><?= $this->Html->link( h($value), array(
    'model' => $model->plugin ? $model->plugin .'.'. $model->alias : $model->alias,
    'action' => 'update',
    $result [$model->alias]['id']
)) ?></span>