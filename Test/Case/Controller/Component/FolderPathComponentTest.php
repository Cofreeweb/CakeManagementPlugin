<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('FolderPathComponent', 'Management.Controller/Component');

/**
 * FolderPathComponent Test Case
 *
 */
class FolderPathComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() 
	{
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->FolderPathComponent = new FolderPathComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FolderPathComponent);

		parent::tearDown();
	}

/**
 * testGetPath method
 *
 * @return void
 */
	public function testGetPath() 
	{
	  $result = $this->FolderPathComponent->getPath();
	  
    // Tiene que ser un array
	  $this->assertEqual( is_array( $result), true);
	  
    // Tiene que contener la clave serverPath
    $this->assertEqual( array_key_exists( 'serverPath', $result), true);
    
    // Tiene que existir el directorio creado
    $this->assertEqual( is_dir( $result ['serverPath']), true);
    
    // Tiene que tener permisos 07777
    $perms = substr(sprintf('%o', fileperms( $result ['serverPath'])), -4);
    $this->assertEqual( $perms, '0777');
	}


}
