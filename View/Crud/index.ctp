<?php
$this->Admin->setBreadcrumbs($model, null, $this->action);
$this->Paginator->options(array(
    'url' => array_merge($this->params ['named'], array('model' => $model->alias))
)); ?>

<? $this->AdminNav->setTitle( '<i class="icon-list"></i> ' . $model->admin ['namePlural'] .'  / Listado') ?>
<?= $this->AdminNav->setActionButtons( array( 'create')) ?>


<?= $this->element( 'filters') ?>

<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">

      <table id="" class="table table-striped table-bordered table-hover dataTable" aria-describedby="">
          <thead>
              <tr>
                  <? if ($model->admin ['batchProcess']) { ?>
                      <th class="hidden-480 header">
                          <span><input type="checkbox" id="check-all"></span>
                      </th>
                  <? }

                  if ($model->admin ['actionButtons']) { ?>
                      <th class="center"><?= __d( 'admin', 'Acciones')?></th>
                  <? }
                  foreach ($model->admin ['fieldsIndex'] as $field => $data) { ?>
                      <th class="hidden-480 header">
                          <?= $this->Paginator->sort( $field, $data ['title']); ?>
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
                                  <?= $this->Form->input( $model->alias . '.items.' . $id, array(
                                      'type' => 'checkbox',
                                      'value' => $id,
                                      'label' => false,
                                      'div' => false,
                                  )); ?>
                              </td>

                          <?php }

                          if ($model->admin['actionButtons']) { ?>

                              <td class="center">
                                  <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        							<?= $this->Html->link( '<i class="icon-pencil bigger-120"></i>', array(
                        							    'model' => Admin::modelName( $model),
                        							    'action' => 'update',
                              			      $result [$model->alias]['id']
                              			  ), array(
                              			      'class' => 'btn btn-xs btn-success',
                              			      'escape' => false
                              			  )) ?>

                              			  <?= $this->Html->link( '<i class="icon-trash bigger-120"></i>', array(
                              			      'model' => Admin::modelName( $model),
                        							    'action' => 'delete',
                              			      $result [$model->alias]['id']
                              			  ), array(
                              			      'class' => 'btn btn-xs btn-danger',
                              			      'escape' => false
                              			  ), __d( 'admin', "¿Estás seguro de que quieres borrarlo?")) ?>
                        						</div>
                              </td>

                          <?php }

                          foreach ($model->admin ['fieldsIndex'] as $field => $data) {
                              echo $this->element( 'field_cell', array(
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
      <?= $this->element( 'pagination', array( 'class' => 'top')) ?>

    </div>
  </div>
</div>


<script>
// Selection All Control
var isDeleteAll = false;

$( ".table-responsive #check-all" ).change( function() {
  isStatus = $( '.table-responsive input[id="check-all"]:checked' ).val();  // on or null
  if( isStatus == 'on' ) {
    $( ".table-responsive .checkbox input[type=checkbox]" )
      .prop( 'checked', true );
    isDeleteAll = true;
  } else {
      $( ".table-responsive .checkbox input[type=checkbox]" )
        .prop( 'checked', false );
      isDeleteAll = false;
  }
});

// Multiselect control
$( "#multiselect" ).change( function() {
  sOption = $( "#multiselect" ).val();
  if( sOption == 'delete' ) {
      if( isDeleteAll == true ) {
        deleteAll();
      }
      if( isDeleteAll == false ) {
        deleteSelected();
      }
  }
});

// MultiActions functions
function deleteAll() {
  if( confirm( '¿Estas seguro que quieres eliminar todos los registros?' ) ) {
    $.ajax({
      type: "POST",
      url: '/admin/management/crud/multiactions',
      data:{
        multiaction: 'deleteAll',
        model: '<?= $model->alias ?>',
        ids: 'empty',
      },
      success: function(data, textStatus, xhr) {
        document.location.href = location;
      },
      error: function(xhr, textStatus, errorThrown) {
        alert( 'Error deleiting');
      }
    });
  }
}

function deleteSelected() {

    var aUnitsArray = [];

    $( ".ace:checked" ).each( function() {
        aUnitsArray.push( $(this).val() );
    });

    if( aUnitsArray.length > 0) {
      if( confirm( '¿Estas seguro que quieres eliminar los registros seleccionados?' ) ) {
        $.ajax({
          type: "POST",
          url: '/admin/management/crud/multiactions',
          data:{
            multiaction: 'deleteSelected',
            model: '<?= $model->alias ?>',
            ids: aUnitsArray,
          },
          success: function(data, textStatus, xhr) {
            document.location.href = location;
          },
          error: function(xhr, textStatus, errorThrown) {
            alert( 'Error deleiting');
          }
        });
      }
    } else {
      alert( 'Debes seleccionar algún elemento');
    }
}
</script>