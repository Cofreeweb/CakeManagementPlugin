<?php
/**
 * ManagerComponent
 * 
 * [Short Description]
 *
 * @package management.component
 * @author Alfonso Etxeberria
 **/

class ManagerComponent extends Component 
{


  var $components = array( 'Session');


  function initialize( Controller $controller, $settings = array()) 
  {
    if( isset( $controller->request->params ['admin']))
    {
      $controller->layout = 'admin';
    }
  }


  function startup( Controller $controller) 
  {
   
  }

  function beforeRender( Controller $controller) 
  {
    if( isset( $controller->request->params ['admin']))
    {
      $controller->helpers ['Form'] = array( 'className' => 'Management.AdminForm');
      $controller->helpers [] = 'Management.AdminUtil';
    }
  }
  
  
/**
 * Escribe un mensaje de flash de éxito
 * Este mensaje se escribirá en la vista con los métodos de CakePHP
 *
 * @param string $message 
 * @param array $params 
 * @return void
 */
  public function flashSuccess( $message, $params = array())
  {
    $params = array_merge( $params, array(
        'type' => 'success',
        'class' => 'success'
    ));

    $old = CakeSession::read('Message.' . 'flash');

    if( !empty( $old))
    {
      $message = $old ['message'] . '<br />'. $message;
    }

    $this->Session->setFlash( $message, 'Management.flash', $params, 'flash');
  }

/**
 * Escribe un mensaje de flash de error
 * Este mensaje se escribirá en la vista con los métodos de CakePHP
 *
 * @param string $message 
 * @param array $params 
 * @return void
 */
  public function flashError( $message, $params = array())
  {
    $params = array_merge( $params, array(
        'type' => 'error',
        'class' => 'danger'
    ));
    $this->Session->setFlash( $message, 'Management.flash', $params, 'flash');
  }
  
  
  public function flashInfo( $message, $params = array())
  {
    $params = array_merge( $params, array(
        'type' => 'notice',
        'class' => 'info'
    ));
    $this->Session->setFlash( $message, 'Management.flash', $params, 'flash');
  }
  
  
/**
 * Resetea los mensajes flash que hayan podido escribirse durante el script o scripts anteriores
 *
 * @return void
 */
  public function resetFlash()
  {
    $this->Session->delete( 'Message.flash');
  }

  
}
?>