<?php
/**
 * AdminUtilHelper
 * 
 * [Short Description]
 *
 * @package management.view.helper
 **/

class AdminUtilHelper extends AppHelper 
{

  public $helpers = array('Html', 'Form', 'Time');
  
/**
 * Devuelve una URL para el admin, dado unos parámetros
 * Usando la currentRoute se obtiene los parámetros necesarios usados en la URL actual.
 * Estos parámetros son omitidos por Router::url(), así que es necesario recurrir a Route para obtenerlos
 *
 * @param array $params 
 * @return array
 */
  public function url( $params = array())
  {
    $route = Router::requestRoute( true);

    // Las keys de la ruta, definidos en Router::connect( :key1/:key2)
    $keys = $route->keys;
    
    // Toma las rutas por defecto para la ruta actual
    $defaults = $route->defaults;
    
    // Recorre las keys definidas en la ruta actual y comprueba si están seteadas en CakesRequest::params
    foreach( $keys as $key)
    {
      if( isset( $this->request->params [$key]))
      {
        $defaults [$key] = $this->request->params [$key];
      }
    }
    
    if( !isset( $defaults ['admin']))
    {
      $defaults ['admin'] = false;
    }

    return array_merge( $defaults, $params);
  }

/**
 * Devuelve la fecha con un formato
 * @param  string  $date   La fecha
 * @param  mixed $format El formato
 * @return string          
 */
  public function date( $date, $format = false)
  {
    if( !$format) 
    {
      $format = __d( 'admin', '%d/%m/%Y');
    }

    return $this->Time->format( $date, $format);
  }

/**
 * Devuelve la fecha y hora con un formato
 * @param  string  $date   La fecha
 * @param  mixed $format El formato
 * @return string          
 */
  public function datetime( $date, $format = false)
  {
    if( !$format) 
    {
      $format = __d( 'admin', '%d/%m/%Y %H:%M:%S');
    }

    return $this->Time->format( $date, $format);
  }
  
}