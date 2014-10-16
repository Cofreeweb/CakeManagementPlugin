<?php
/**
 * AdminNavHelper
 * 
 * [Short Description]
 *
 * @package default
 * @author Alfonso Etxeberria
 * @version $Id$
 * @copyright __MyCompanyName__
 **/

class AdminNavHelper extends AppHelper 
{
  public $helpers = array('Html', 'Form', 'Acl.Auth');
  
  public $isActive = false;
  
  public $isOpen = false;
  
  public $actionButtons = array( 'add', 'index');

  private $__actions = array();
  
  
  protected $_models = array();
  

  public function __construct(View $View, $settings = array()) 
  {
    parent::__construct( $View, $settings);

    $this->__setDefaultActions();
  }

/**
 * Marca por defecto las acciones de los botones de la administración
 * Estas acciones pueden ser modificadas por setAction()
 *     
 * @return
 */
  private function __setDefaultActions()
  {
    $this->__actions = array(
        'add' => array(
            'action' => 'add'
        ),
        'index' => array(
            'action' => 'index'
        ),
        'create' => array(
            'action' => 'create'
        ),
    );
  }
  
  public function setAction( $action, $route)
  {
    $this->__actions [$action] = $route;
  }

  public function getAction( $action)
  {
    return $this->__actions [$action];
  }

/**
 * Guess the location for a model based on its name and tries to create a new instance
 * or get an already created instance of the model
 *
 * @param string $model
 * @return Model model instance
 */
	protected function _getModel($model) {
		$object = null;
		if (!$model || $model === 'Model') {
			return $object;
		}

		if (array_key_exists($model, $this->_models)) {
			return $this->_models[$model];
		}

		if (ClassRegistry::isKeySet($model)) {
			$object = ClassRegistry::getObject($model);
		} elseif (isset($this->request->params['models'][$model])) {
			$plugin = $this->request->params['models'][$model]['plugin'];
			$plugin .= ($plugin) ? '.' : null;
			$object = ClassRegistry::init(array(
				'class' => $plugin . $this->request->params['models'][$model]['className'],
				'alias' => $model
			));
		} elseif (ClassRegistry::isKeySet($this->defaultModel)) {
			$defaultObject = ClassRegistry::getObject($this->defaultModel);
			if ($defaultObject && in_array($model, array_keys($defaultObject->getAssociated()), true) && isset($defaultObject->{$model})) {
				$object = $defaultObject->{$model};
			}
		} else {
			$object = ClassRegistry::init($model, true);
		}

		$this->_models[$model] = $object;
		if (!$object) {
			return null;
		}

		$this->fieldset[$model] = array('fields' => null, 'key' => $object->primaryKey, 'validates' => null);
		return $object;
	}
	
  public function out( $content)
  {
    return implode( "\n", $content);
  }
  
/**
 * Setea el título de la página, que será puesto en la cabecera de la página
 *
 * @param string $title 
 * @param string $subtitle 
 * @param string icon Pondrá un icono http://fontawesome.io/icons/
 * @return void
 */
  public function setTitle( $title, $subtitle = false, $icon = false)
  {
    if( $icon)
    {
      $title = '<i class="icon-'. $icon .'"></i> '. $title;
    }
    
    $this->_View->set( 'adminTitle', $title);
    
    if( $subtitle)
    {
      $this->_View->set( 'adminSubtitle', $subtitle);
    }
  }
  
  public function nav()
  {
    $return = $this->_nav( Configure::read( 'Management.nav'), true);
    return $return;
  }

  public function _nav( $els, $first = false)
  {
    $ul = array();

    foreach( $els as $el)
    {
      if( array_key_exists( 'html', $el))
      {
        if( $this->hasPermissions( $el ['url']))
        {
          $ul [] = $el ['html'];
        }
        
        continue;
      }

      $li = array();
      $url = isset( $el ['url']) ? $el ['url'] : $el;

      if( !isset( $url ['plugin']) && $url)
        $url ['plugin'] = false;


      // if( !is_array( $el) || !isset( $el ['level']) || $this->hasPermission( $url))
      if( empty( $url) || $this->hasPermissions( $url))
      {
        $a = '';
        
        if( $icon = $this->icon( $el))
        {
          $a [] = $icon;
        }
        
        $a [] = '<span class="menu-text">'. $el ['label'] .'</span>';
  			
  			if( !empty( $el ['children']))
  			{
  			  $a [] = '<b class="arrow icon-angle-down dropdown-toggle"></b>';
  			}
  			
        $li [] = $this->Html->link( $this->out( $a), $url, array( 
            'escape' => false,
            // 'class' => !empty( $el ['children']) ? 'dropdown-toggle' : false
        ));
        
        if( isset( $el ['children']) && is_array( $el ['children']))
        {
          $li [] = $this->_nav( $el ['children']);
        }

        $class = '';
        
        $this->_isCurrentNav( $el, $el ['label'], $first, $els);
        
        if( $this->isActive)
        {
          $class .= ' active';
        }
        
        if( $this->isOpen)
        {
          $class .= ' open';
        }
        
        $ul [] = $this->Html->tag( 'li', $this->out( $li), array(
            'class' => $class
        ));
      }
    }

    return $this->Html->tag( 'ul', implode( "\n", $ul), array(
        'class' => $first ? 'nav nav-list' : 'submenu'
    ));
  }
  
/**
 * Devuelve una etiqueta <i> con el class para el icon
 * @param  array $el El array del elemento definido en la configuración de la navegación
 * @param  string $css El CSS extra
 * @return HTML
 */
  public function icon( $el, $css = '')
  {
    if( !isset( $el ['icon']))
    {
      return false;
    }

    if( strpos( $el ['icon'], 'fa') === false) 
    {
      $el ['icon'] = 'icon-'. $el ['icon'];
    }
    else
    {
      $el ['icon'] = 'fa '. $el ['icon'];
    }

    return '<i class="'. $el ['icon'] .' '. $css .'"></i>';
  }

  public function _isCurrentNav( $el, $label, $first, $els)
  {
    $this->isActive = false;
    $this->isOpen = false;
    $url = isset( $el ['url']) ? $el ['url'] : $el;

    if( ($url && $url ['controller'] == $this->request->params ['controller']))
    {
      if( $first)
      {
        $this->isActive = true;
        $this->isOpen = true;
      }

      $has_other = false;

      foreach( $els as $key => $el2)
      {
        if( array_key_exists( 'html', $el2))
        {
          continue;
        }

        $url2 = isset( $el2 ['url']) ? $el2 ['url'] : $el2;
        
        if( $key == $label)
        {
          $has_other = true;
        }

        if( $url2 ['controller'] == $this->request->params ['controller'])
        {
          $has_other = true;
        }
      }

      if( !$has_other || ($has_other && isset( $url ['action']) && $url ['action'] == str_replace( 'admin_', '', $this->request->params ['action'])))
      {
        $this->isActive = true;
        $this->isOpen = true;
      }

    }

    if( $this->_navIsChildrenCurrent( $el))
    {
      $this->isActive = true;
      $this->isOpen = true;
    }
    
    $event = new CakeEvent( 'Management.Helper.isCurrentNav', $this, array(
        'el' => $el
    ));
		$this->_View->getEventManager()->dispatch($event);
  }
  
  public function hasPermissions( $url)
  {
    $aro = array( 'model' => 'Group', 'foreign_key' => $this->Auth->user( 'group_id'));
    $aco = 'controllers';
    
    if( !empty( $url ['plugin']))
    {
      $aco .= '/'. Inflector::camelize( $url ['plugin']);
    }
    
    if( !empty( $url ['controller']))
    {
      $aco .= '/'. Inflector::camelize( $url ['controller']);
    }
    
    if( !empty( $url ['action']))
    {
      $action = !empty( $url ['admin']) ? 'admin_'. $url ['action'] : $url ['action']; 
      $aco .= '/'. $action;
    }
    $permission = $this->_getModel( 'Permission')->check( $aro, $aco);
    return $permission;
  }
  
  public function _navIsChildrenCurrent( $el)
  {
    if( empty( $el ['children']))
    {
      return false;
    }

    foreach( $el ['children'] as $el2)
    {
      $url = isset( $el2 ['url']) ? $el2 ['url'] : $el2;
      
      if( $url ['controller'] == $this->request->params ['controller'])
      {
        return true;
      }
    }
  }
  
  public function link( $title, $url = null, $options = array(), $confirmMessage = false)
  {
    if( is_array( $url) && !isset( $url ['admin']))
    {
      $url ['admin'] = true;
    }
    
    if( $this->hasPermission( $url))
    {
      return $this->Html->link( $title, $url, $options, $confirmMessage);
    }
    else
    {
      return '';
    }
  }
  
  public function asActionButton( $action)
  {
    return in_array( $action, $this->actionButtons);
  }
  
  public function setActionButtons( $actions = array())
  {
    $this->actionButtons = $actions;
  }
  
  public function contentsButtons( $options = array())
  {
    foreach( $options as $option)
    {
      
    }
  }

  public function setSaveButton( $id = '#form-main')
  {
    $this->_View->set( 'save_button', $id);
  }
}