<div class="modal-footer no-margin-top">
  <div class="pagination pull-left no-margin">
  
    <? if( $this->Paginator->hasPrev()):?>
        <?= $this->Paginator->prev( 'Anterior') ?> 
    <? endif ?>
  
  	<?= $this->Paginator->numbers( array( 'separator' => '')); ?> 
	
  	<? if( $this->Paginator->hasNext()):?>
	      <?= $this->Paginator->next( 'Siguiente') ?> 
  	<? endif ?>
  </div>
</div>