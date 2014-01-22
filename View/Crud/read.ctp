<?php
$this->Admin->setBreadcrumbs($model, $result, $this->action); ?>

<div class="title">
    <?= $this->element('crud/actions'); ?>

    <h2><?= $this->Admin->outputIconTitle( $model, $this->Admin->getDisplayField($model, $result)); ?></h2>
</div>

<div class="container">
    <?php
    echo $this->element('crud/read_table');
    echo $this->element('crud/read_extra'); ?>
</div>