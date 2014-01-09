<? $this->AdminNav->setTitle( '<i class="icon-cogs"></i> '. __d( 'admin', 'Administración del web')) ?>
<?= $this->AdminNav->setActionButtons() ?>

<div class="row">
  <? foreach( Configure::read( 'Management.nav') as $nav): ?>
      <div class="col-sm-2">
        <?= $this->Html->link( '<i class="icon icon-'. $nav ['icon'] .'"></i>' . $nav ['label'], $nav ['url'], array(
            'class' => 'btn btn-default btn-app btn-xs radius-4',
            'escape' => false
        )) ?>
      </div>
  <? endforeach ?>
</div>