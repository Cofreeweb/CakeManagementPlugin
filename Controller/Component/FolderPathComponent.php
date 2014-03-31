<?php
/**
 * FolderPathComponent
 * 
 * Operaciones de creación de Paths aleatorios
 *
 * @package management.component
 **/

App::uses( 'Component', 'Controller');
App::uses( 'Folder', 'Utility');

class FolderPathComponent extends Component 
{
  
  private $__path = '/files/';
  
  
  public function setPath( $path)
  {
    $this->__path = $path;
  }
  
/**
 * Crea y devuelve un path aleatorio que será creado en $this->__path
 *
 * @return array  'path' => El path creado sobre $this->__path
 *                'urlPath' => El path accesible desde http
 *                'serverPath' => El path del servidor
 */
  public function getPath() 
  {
		$endPath = null;
		$decrement = 0;
		$string = crc32( time());

		for ($i = 0; $i < 3; $i++) 
		{
			$decrement = $decrement - 2;
			$endPath .= sprintf("%02d" . DIRECTORY_SEPARATOR, substr('000000' . $string, $decrement, 2));
		}

		$destDir = $this->__path . $endPath;
		
		if( !$this->mkPath( $destDir))
		{
		  return false;
		}
    
		$endPath = substr( $endPath, 0, -1);
		
		$www_root = substr( WWW_ROOT, 0, -1);
		
		return array(
		    'path' => $endPath,
		    'urlPath' => $this->__path . $endPath,
		    'serverPath' => $www_root . $this->__path . $endPath,
		);
	}
	
  public function mkPath( $request) 
  {	  
    if( !$request) return;
    $paths = explode( '/', substr( $request, 0, -1));
    $paths = array_slice( $paths, count( $paths) - 3);

    $server_path = $this->__path;
    $route = $paths [0];

    foreach( $paths as $key => $path)
    {
      if( $key > 0)
      $route .= "/$path";

      $Folder = new Folder;
      
      $www_root = substr( WWW_ROOT, 0, -1);

      if( !$Folder->create( $www_root . $server_path . $route, 0777))
      {
        return false;
      }
      else
      {
        @chmod( $path, intval('0777', 8));
      }
    }
    
		return true;
	}

  
}
?>