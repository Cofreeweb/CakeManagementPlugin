<?
/**
 * Son las acciones del administrador para el listado y añadido de contenidos
 *
 */
?>
    <? if( $this->AdminNav->asActionButton( 'index') && $this->action != 'admin_index'): ?>
        <?= $this->Html->link( '<i class="icon-list align-top bigger-125"></i> ' . __d( 'admin', 'Listado'), $this->AdminUtil->url( array(
            'action' => 'index'
        )), array(
            'class' => 'btn btn-sm btn-primary',
            'escape' => false
        )) ?>

    <? endif ?>
    
    <? if( $this->AdminNav->asActionButton( 'add') && $this->action != 'admin_add'): ?>
        <?= $this->Html->link( '<i class="icon-plus align-top bigger-125"></i> ' . __d( 'admin', 'Crear'), $this->AdminUtil->url( array(
            'action' => 'add'
        )), array(
            'class' => 'btn btn-sm btn-primary',
            'escape' => false
        )) ?>
    <? endif ?>
