<?  $options = !empty( $data ['editorConfig']) ? Configure::read( 'Management.ckeditor.'. $data ['editorConfig']) : array() ?>
<?= $this->Form->ckeditor( $field, array(
    'label' => $data ['title']
), $options) ?>