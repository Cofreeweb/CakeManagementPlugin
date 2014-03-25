<?php
App::uses( 'AppHelper', 'View/Helper');
App::uses( 'Access', 'Management.Lib');

/**
 * InlineHelper
 * 
 * Se encarga de las tareas en la edición en el frontend
 *
 * Algunos métodos hacen uso de la class Access para tomar información
 *
 * @package management.view.helper
 **/

class InlineHelper extends AppHelper 
{

  public $helpers = array( 'Html', 'Form', 'Section.Section', 'Acl.Auth');
  

/**
 * Menú de edición del web
 *
 * @return string
 * @example $this->Inline->toolbar()
 */
  public function toolbar()
  {
    $this->Html->script( array(
        '/management/inline/js/jquery-1.10.2.min.js',
        '/management/inline/js/jquery-ui-1.10.4.custom.min.js',
        '/management/js/angular/app.js',
        '/management/js/angular/controllers.js',
        '/management/js/angular/routes.js',
        '/management/js/angular/directives/directives.js',
        '/management/js/angular/components/angular-nested-sortable.js',
        '/management/inline/js/ui-bootstrap-tpls-0.6.0.js',
        '/management/inline/js/dialogs.min.js'
        
  	), array(
  	  'inline' => false
  	));
  	
  	$this->Html->css( array(
  	    '/management/css/nested-sortable.css',
  	    '/management/inline/css/dialogs.min.css'
  	), array(
  	  'inline' => false
  	));
  	
  	
    return $this->_View->element( 'Management.inline/toolbar', array(
        'menu' => $this->menu()
    ));
  }
  
/**
 * Devuelve true si en el frontend hay ambiente de edición
 *
 * @return boolean
 * @example $this->Inline->isModeEditor()
 */
  public function isModeEditor()
  {
    return isset( $this->request->params ['edit']) || $this->request->plugin == 'angular';
  }
  
/**
 * Forma los elementos del menú del web
 * Para ello hace uso de la class Access, donde se encuentra la información de lo que se edita
 *
 * @return array
 */
  public function menu()
  {
    $return = array();
    $permission = Access::matchFromKey( array(
        'plugin' => $this->request->params ['plugin'],
        'controller' => $this->request->params ['controller'],
        'action' => $this->request->params ['action'],
    ));

    if( $permission)
    {
      $user_permissions = $this->Auth->user( 'Group.permissions');
      
      if( in_array( $permission, $user_permissions))
      {
        $info = Access::getPermission( $permission);
        
        if( !$this->isModeEditor() && isset( $info ['adminLinks']['noEdit']))
        {
          foreach( $info ['adminLinks']['noEdit'] as $url)
          {
            $route = Router::currentRoute();
            $link = array_merge( $route->defaults, $url ['url']);
            $return [] = array(
                'label' => $url ['label'],
                'url' => Router::url( $link)
            );
          }
        }
        elseif( $this->isModeEditor() && isset( $info ['adminLinks']['editMode']))
        {
          foreach( $info ['adminLinks']['editMode'] as $url)
          {
            $route = Router::currentRoute();
            $link = array_merge( $route->defaults, $url ['url']);
            $return [] = array(
                'label' => $url ['label'],
                'url' => Router::url( $link)
            );
          }
        }
      }
    }
    
    return $return;
  }
  
  
  
}