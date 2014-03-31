<?php
/**
 * FileUploadsController
 * 
 * Sube ficheros al directorio uploads
 *
 * @package management.controllers
 **/

App::uses( 'Controller', 'AppController');

class FileuploadController extends AppController 
{
  public $helpers = array( 'Html', 'Form');
  
  public $components = array( 'Management.FolderPath');
/**
 * Este controller no usa models
 */
  public $uses = array();
  
  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->Auth->allow();
  }
  
  
  public function upload()
  {
    $this->layout = 'ajax';
    
    if( isset( $this->request->params ['form']['upload']['tmp_name']))
    {
      $upload = $this->request->params ['form']['upload'];
      
      $path = $this->FolderPath->getPath();
      $dir = $path ['serverPath'] .DS. $upload ['name'];
      
      if( $this->__moveUploadedFile( $upload ['tmp_name'], $dir))
      {
        $this->set( 'url', $path ['urlPath'] .'/'. $upload ['name']);
        $this->set( 'funcNum', $this->request->query ['CKEditorFuncNum']);
      }
    }
  }
  
  
  private function __moveUploadedFile( $tmp, $filePath)
  {
    if( is_uploaded_file( $tmp))
    {
      return @move_uploaded_file( $tmp, $filePath);
    }
    
    return false;
  }
  
}
?>