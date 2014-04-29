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
    if( !$this->Auth->user())
    {
      return;
    }
    
    $this->Html->script( array(
        '/management/inline/js/jquery-ui-1.10.4.custom.min.js',
        '/management/js/bootstrap.min',
        '/angular/components/angular-ui-router.min',
        '/management/js/angular/app.js',
        '/management/js/angular/controllers.js',
        '/management/js/angular/routes.js',
        '/management/js/angular/directives/directives.js',
        '/dictionary/js/angular/routes.js',
        '/dictionary/js/angular/controllers.js',
        '/angular/components/angular-flexslider.js',
        '/angular/components/ui-bootstrap-custom-tpls-0.10.0.min',
        '/angular/components/dialogs',
        '/angular/components/sortable',
        '/angular/components/angular-ui-tree',
        '/angular/components/angular-validator.min',
        '/angular/components/angular-validator-rules.min',
        '/angular/components/angular-form-builder-components.min',
        '/angular/components/angular-form-builder.min',
        '/angular/components/angular-pagination',
        '/angular/components/angular-multilingue',
  	), array(
  	  'inline' => false
  	));
  	
  	$this->Html->css( array(
  	    '/angular/css/angular-ui-tree.min.css',
  	    '/angular/css/dialogs.css',
  	    '/angular/css/angular-form-builder'
  	), array(
  	  'inline' => false
  	));
  	
    // Setea los params del request en el javascript, en la variable $REQUEST
  	$request = json_encode( $this->request->params);
  	$script = '$REQUEST = '. $request;
  	
  	$this->Html->scriptBlock( $script, array(
        'inline' => false,
    	  'block' => 'scriptBottom'
    ));
    
    $script = '
      adminApp.run( function( $rootScope){
        $rootScope.languages = '. json_encode( Configure::read( 'Config.languages')) .'
      });
    ';
    
    $this->Html->scriptBlock( $script, array(
        'inline' => false,
    	  'block' => 'scriptBottom'
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
 * Esta información viene determinada por el fichero Config/access.php que estará en cada plugin
 *
 * @return array
 */
  public function menu()
  {
    $return = array();
    $links = array();
    
    $return [] = array(
        'label' => __d( "admin", "Traducción"),
        'url' => '#dictionaries/index/default'
    );
    
    $current_permission = Access::findKeyFromFrontUrl( array(
        'plugin' => $this->request->params ['plugin'],
        'controller' => $this->request->params ['controller'],
        'action' => $this->request->params ['action'],
    ));
    
    $user_permissions = $this->Auth->user( 'Group.permissions');
    
    if( $user_permissions)
    {
      foreach( $user_permissions as $permission)
      {
        $info = Access::getPermission( $permission);
        
        if( isset( $info ['alwaysLinks']))
        {
          foreach( $info ['alwaysLinks'] as $link)
          {
            $links += $info ['alwaysLinks'];
          }
        }
      }
    }
    
    
    if( $user_permissions && $current_permission)
    {
      if( in_array( $current_permission, $user_permissions))
      {
        $info = Access::getPermission( $current_permission);
        
        
        // Enlaces que se mostrarán en los contenidos públicos (no en modo edición)
        if( !$this->isModeEditor() && isset( $info ['adminLinks']['noEdit']))
        {
          $links = array_merge( $links, $info ['adminLinks']['noEdit']);
        }
        
        // Enlacers que se mostrarán cuando se está editando
        if( $this->isModeEditor() && isset( $info ['adminLinks']['editMode']))
        {
          $links = array_merge( $links, $info ['adminLinks']['editMode']);
        }
        
        // Enlaces que se mostrarán siempre, tanto cuando se esté editando como cuando se esté visualizando contenido público
        if( isset( $info ['adminLinks']['allMode']))
        {
          $links = array_merge( $links, $info ['adminLinks']['allMode']);
        }
        

        // Recorre todos los enlaces
        foreach( $links as $url)
        {
          $variable = Inflector::singularize( $this->request->controller);
          $model = Inflector::camelize( $variable);
          
          
          if( !is_array( $url ['url']) && strpos( $url ['url'], '#') !== false) 
          {
            if( isset( $this->_View->viewVars [$variable][$model]['id']))
            {
              $url ['url'] = String::insert( $url ['url'], array(
                  'id' => $this->_View->viewVars [$variable][$model]['id']
              ));
            }
            
            $return [] = array(
                'label' => $url ['label'],
                'url' => $url ['url']
            );
          }
          else
          {
            $route = Router::currentRoute();
            $keys = $route->keys;
            foreach( $keys as $key)
            {
              if( isset( $this->request->params [$key]))
              {
                $url ['url'][$key] = $this->request->params [$key];
              }
            }
            
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