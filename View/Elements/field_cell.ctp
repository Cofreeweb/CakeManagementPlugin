<td class="col-<?= $field; ?> type-<?php echo $data['type']; ?>">
    <?= $this->element('Management.field', array(
        'result' => $result,
        'field' => $field,
        'data' => $data,
        'value' => $result[$model->alias][$field]
    )); ?>
</td>