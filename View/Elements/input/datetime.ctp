

<?= $this->Form->input( $field, array(
    'type' => 'text',
    'class' => 'date-picker',
    'after' => '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>',
    'colsInput' => 2,
    'default' => $data['default'],
    'label' => __d( 'admin', $data ['title'])
)) ?>