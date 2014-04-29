<?php

App::uses( 'Controller', 'Controller');
App::uses( 'View', 'View');
App::uses( 'InlineHelper', 'Management.View/Helper');

class InlineHelperTest extends CakeTestCase 
{
  public function setUp() 
  { 
    parent::setUp();
    $Controller = new Controller();
    $View = new View($Controller);
    $this->Inline = new InlineHelper($View);
  }

  public function testVamos() 
  {
    $result = $this->Inline->vamos( 3);
    $this->assertContains( "12", $result);
  }
}