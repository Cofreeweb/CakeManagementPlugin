<? if ($this->action === 'create'): ?>
  <? $this->AdminNav->setTitle( '<i class="icon-list"></i> ' . $model->admin ['namePlural'] .'  / Crear') ?>
  <?= $this->AdminNav->setActionButtons( array( 'index')) ?>

<? else: ?>
  <? $this->AdminNav->setTitle( '<i class="icon-list"></i> ' . $model->admin ['namePlural'] .'  / Editar') ?>
  <?= $this->AdminNav->setActionButtons( array( 'create', 'index')) ?>
<? endif ?>

<?#= $this->Admin->setBreadcrumbs($model, $result, $this->action) ?>


<div class="row">
  <div class="col-md-12">
    <?= $this->Form->create( $model->alias, array(
        'class' => 'form-horizontal', 
        'role' => 'form'
    )) ?>
    <? if( !empty( $model->admin ['fieldsFiles'])): ?>
        <div class="tabbable">
          <ul id="myTab" class="nav nav-tabs">
            <li class="active">
          		<a href="#manager-form" data-toggle="tab"><i class="green icon-edit bigger-110"></i> <?= __d( "admin", "Datos") ?></a>
          	</li>
            
            <? foreach( $model->admin ['fieldsFiles'] as $field => $info): ?>
                <li>
              		<a href="#manager-<?= $field ?>" data-toggle="tab"><i class="green icon-camera bigger-110"></i> <?= $info ['title'] ?></a>
              	</li>
            <? endforeach ?>
          </ul>
    <? endif ?>
    
    <? if( !empty( $model->admin ['fieldsFiles'])): ?>
        <div class="tab-content">
          <div class="tab-pane in active" id="manager-form">
    <? endif ?>
    
    
    <?= $this->Form->hidden( $model->alias .'.id') ?>
    <?= $this->element('crud/form_fields') ?>
    <?#= $this->element('crud/form_extra') ?>
    <?#= $this->element('form_actions') ?>
    
    
    <? if( !empty( $model->admin ['fieldsFiles'])): ?>
        </div>
        
        <? foreach( $model->admin ['fieldsFiles'] as $field => $info): ?>
            <div class="tab-pane in" id="manager-<?= $field ?>">
              <?= $this->Upload->multiple( array(
                  'key' => $info ['content_type'],
                  'model' => $model->alias,
                  'alias' => $field,
                  'limit' => isset( $info ['max']) ? $info ['max'] : 0,
                  'buttonLabel' => __( "Subir"),
                  'label' => $info ['title']
              )) ?>
            </div>
        <? endforeach ?>
    <? endif ?>
    
    <? if( !empty( $model->admin ['fieldsFiles'])): ?>
          </div>
        </div>
    <? endif ?>
    <?= $this->Form->submit( __( "Guardar")) ?>
    <?= $this->Form->end() ?>
  </div>
</div>

