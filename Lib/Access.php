<?php

/**
* 
*/
class Access
{
  
/**
 * Aquí se va guardando la configuración de cada Plugin
 */
  protected static $_config = array();
  
  
/**
 * Lee la configuración de cada plugin
 *
 * @return void
 */
  public static function loadConfig()
  {
    $plugins = App::objects( 'plugin');
    foreach( $plugins as $plugin)
    {
      if( CakePlugin::loaded( $plugin))
      {
        $path = App::pluginPath( $plugin) . 'Config'. DS. 'access.php';

        if( file_exists( $path))
        {
          Configure::load( $plugin .'.access', 'default', false);
          $data = Configure::read( 'Access');
          self::$_config [$plugin] = $data;
        }
      }
    }
  }
  
/**
 * Devuelve un valor de la configuración de las Section, dado un hash tipo Plugin.key1.key2
 *
 * @param string $var 
 * @return void
 * @example Access::read( 'Blog.name') => devolverá el nombre humano del plugin
 */
  public function read( $var)
  {
    if( empty( self::$_config))
    {
      self::loadConfig();
    }
    
    $return = Hash::get( self::$_config, $var);

    return $return;
  }  
  
/**
 * Devuelve un array con la clave y con el nombre humano. Será usado en la edición de los permisos de los grupos
 *
 * @return void
 * @example Access::getAllOptions()
 */
  public static function getAllOptions()
  {
    $return = array();
    
    if( empty( self::$_config))
    {
      self::loadConfig();
    }
    
    foreach( self::$_config as $plugin)
    {
      foreach( $plugin as $key => $info)
      {
        $return [] = array(
            'id' => $key,
            'label' => $info ['name']
        );
      }
    }
    
    return $return;
  }
  
  public static function getAllPermissions()
  {
    $options = self::getAllOptions();
    
    return Hash::extract( $options, '{n}.id');
  }
  
  public static function getUserPermissions( $permissions)
  {
    if( in_array( '*', $permissions))
    {
      return self::getallPermissions();
    }
    
    return $permissions;
  }
  
  public function getPermission( $key)
  {
    if( empty( self::$_config))
    {
      self::loadConfig();
    }
    
    foreach( self::$_config as $plugin)
    {
      foreach( $plugin as $key2 => $info)
      {
        if( $key2 == $key)
        {
          return $info;
        }
      }
    }
    
    return false;
  }
  

  public static function acoFromUrl( $url)
  {
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
    
    return $aco;
  }
  
  public static function getAllUrls()
  {
    if( empty( self::$_config))
    {
      self::loadConfig();
    }
    
    $return = array();
    
    foreach( self::$_config as $plugin)
    {
      foreach( $plugin as $key => $info)
      {
        foreach( $info ['urls'] as $url)
        {
          $return [$key][] = self::acoFromUrl( $url);
        }
      }
    }
    
    return $return;
  }  

/**
 * Dada una URL del frontend, devuelve la Key del acceso
 *
 * @param array $url 
 * @return string
 */
  public function findKeyFromFrontUrl( $url)
  {
    if( empty( self::$_config))
    {
      self::loadConfig();
    }
        
    foreach( self::$_config as $plugin)
    {
      foreach( $plugin as $key => $info)
      {
        if( isset( $info ['front']))
        {
          if( in_array( $url, $info ['front']))
          {
            return $key;
          } 
        }
      }
    }
    
    return false;
  }
  

}
