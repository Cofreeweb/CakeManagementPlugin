<?php
  $this->Paginator->options ['url'] = $this->AdminUtil->url()
?>
<div class="row">
  <div class="pull-left no-margin">
    <ul class="pagination">
      <? if( $this->Paginator->hasPrev()):?>
          <?= $this->Paginator->prev( 'Anterior', array(
              'tag' => 'li'
          )) ?> 
      <? endif ?>
  
    	<?= $this->Paginator->numbers( array(
            'separator' => '',
            'currentClass' => 'active',
            'currentTag' => 'span',
            'tag' => 'li',
    	)); ?> 
	
    	<? if( $this->Paginator->hasNext()):?>
  	      <?= $this->Paginator->next( 'Siguiente', array(
  	          'tag' => 'li'
  	      )) ?> 
    	<? endif ?>
  	</ul>
  </div>
</div>