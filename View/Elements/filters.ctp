<? if( empty( $model->admin ['fieldsSearch'])) return ?>
<div class="row">
  <div class="col-xs-12 widget-box">
    <div class="widget-header">
      <h5 class="green"><i class="icon-search icon-on-right bigger-110"></i> <?= __d( 'admin', "Buscar") ?></h5>
      <div class="widget-toolbar">
      	<a data-action="collapse" href="#">
					<i class="icon-chevron-up"></i>
				</a>
      </div>
    </div>
    <div class="widget-body">
      <div class="widget-main">
        <?= $this->Form->create( $model->alias, array(
            'type' => 'get'
        )) ?>

          <? foreach( $model->admin ['fieldsSearch'] as $field => $data): ?>
              <? $data ['empty'] = __d( 'admin', '-- Todos --') ?>

              <?= $this->element( 'Management.input', array(
                  'field' => $field,
                  'data' => $data
              )) ?>
          <? endforeach ?>
          <?= $this->Form->submit( __d( 'admin', 'Buscar')) ?>
        <?= $this->Form->end() ?>
      </div>
    </div>

      <? if ( $model->admin['batchProcess']): ?>
        <div class="widget-main">
          <?= $this->Form->input( 'Acciones múltiples', array(
            'type' => 'select',
            'empty' => 'Selecciona una acción',
            'options' => array(
              'delete' => 'Borrar',
            ),
            'id' => 'multiselect'
          ));?>
        </div>
      <? endif ?>

  </div>
</div>

