<?
/**
 * Elemento que se llama cuando se cargan los mensajes de flash
 *
 */
?>

    <div id="flash_message" class="alert alert-block alert-<?= $class ?>">
      <button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
      <p><?= $message ?></p>
    </div>
    
    <? $this->append( 'script') ?>
    <script type="text/javascript">
      $(function(){
          var height = $("#flash_message").height();
          $("#flash_message")
            .css( 'top', - ( height + $("#navbar").height()))
            .show()
            .click(function(){
              closeFlash();
            })
          $("#flash_message").animate({
              top: $(window).scrollTop() + $("#navbar").height() + "px"
          }, 1000);
          closeFlash = function() {
              $("#flash_message").fadeOut()
          }
          
          setInterval( closeFlash, 5000);          
      })
    </script>
    <? $this->end() ?>