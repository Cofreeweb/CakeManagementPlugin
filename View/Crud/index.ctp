<?php
$this->Admin->setBreadcrumbs($model, null, $this->action);
$this->Paginator->options(array(
    'url' => array_merge($this->params ['named'], array('model' => $model->urlSlug))
)); ?>

<? $this->AdminNav->setTitle( '<i class="icon-list"></i> ' . $model->admin ['namePlural'] .'  / Listado') ?>
<?= $this->AdminNav->setActionButtons( array( 'create')) ?>


<div class="row">
  <div class="widget-box">
    <div class="widget-header">
      <h5 class="green"><i class="icon-search icon-on-right bigger-110"></i> <?= __d( 'admin', "Buscar") ?></h5>
    </div>
    <div class="widget-body">
      <div class="widget-main">
        
      </div>
    </div>
  </div>
</div>

<div class="col-xs-12">
  <div class="table-responsive">
    <?= $this->element( 'filters') ?>
    <?= $this->element( 'pagination', array( 'class' => 'top')) ?>
    <table id="" class="table table-striped table-bordered table-hover dataTable" aria-describedby="">
        <thead>
            <tr>
                <? if ($model->admin ['batchProcess']) { ?>
                    <th class="hidden-480 sorting header">
                        <span><input type="checkbox" id="check-all"></span>
                    </th>
                <? }

                if ($model->admin ['actionButtons']) { ?>
                    <th class="hidden-480 sorting header"><?= __d( 'admin', 'Acciones')?></th>
                <? }
                foreach ($model->fieldsIndex as $field => $data) { ?>
                    <th class="hidden-480 sorting header">
                        <?= $this->Paginator->sort( $field, $data['title']); ?>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($results) {
                foreach ($results as $result) {
                    $id = $result [$model->alias][$model->primaryKey]; ?>

                    <tr>
                        <?php if( $model->admin['batchProcess']) { ?>

                            <td class="col-checkbox">
                                <?php echo $this->Form->input($model->alias . '.items.' . $id, array(
                                    'type' => 'checkbox',
                                    'value' => $id,
                                    'label' => false,
                                    'div' => false
                                )); ?>
                            </td>

                        <?php }

                        if ($model->admin['actionButtons']) { ?>

                            <td>
                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                      							<?= $this->Html->link( '<i class="icon-pencil bigger-120"></i>', array(
                      							    'model' => $model->alias,
                      							    'action' => 'update',
                            			      $result [$model->alias]['id']
                            			  ), array(
                            			      'class' => 'btn btn-xs btn-success',
                            			      'escape' => false
                            			  )) ?>

                            			  <?= $this->Html->link( '<i class="icon-trash bigger-120"></i>', array(
                            			      'model' => $model->alias,
                      							    'action' => 'delete',
                            			      $result [$model->alias]['id']
                            			  ), array(
                            			      'class' => 'btn btn-xs btn-danger',
                            			      'escape' => false
                            			  ), __( "¿Estás seguro de que quieres borrarlo?")) ?>
                      						</div>
                            </td>

                        <?php }

                        foreach ($model->fieldsIndex as $field => $data) {
                            echo $this->element('field_cell', array(
                                'result' => $result,
                                'field' => $field,
                                'data' => $data
                            ));
                        } ?>
                    </tr>

                <?php }
            } else { ?>

            <tr>
                <td colspan="<?php echo count($model->fields) + $model->admin['batchProcess'] + $model->admin['actionButtons']; ?>" class="no-results">
                    <?php echo __d('admin', 'No results to display'); ?>
                </td>
            </tr>

            <?php } ?>
        </tbody>
    </table>
    
  </div>
</div>
