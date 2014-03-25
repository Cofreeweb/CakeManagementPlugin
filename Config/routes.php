<?php

// foreach (Configure::read('Admin.menu') as $section => $menu) {
//  Router::connect('/admin/'. $section . '/:action/*', $menu['url'], array('section' => $section));
//  Router::connect('/admin/'. $section, $menu['url'] + array('action' => 'index'), array('section' => $section));
// }
// 
// Router::connect('/admin/:model/:action/*',
//  array('plugin' => 'admin', 'controller' => 'crud'),
//  array('model' => '[_a-z0-9]+\.[_a-z0-9]+'));
// 
// Router::connect('/admin/:action/*', array('plugin' => 'admin', 'controller' => 'admin'));


Router::connect( '/admin', array(
    'admin' => true,
    'plugin' => 'management',
    'controller' => 'mng_dashboard', 
    'action' => 'index', 
));

foreach( (array)Configure::read( 'Management.crud') as $model)
{
  foreach( array( 'index', 'create', 'read', 'update', 'delete') as $action)
  {    
    $controller = Inflector::tableize( $model);

    Router::connect( '/admin/'. str_replace( '.', '', $controller) .'/'. $action .'/*', array(
        'admin' => false,
        'plugin' => 'management', 
        'controller' => 'crud',
        'action' => $action,
        'model' => $model
    ));
  }
  
}


// Router::connect('/admin/:action/*', array('plugin' => 'admin', 'controller' => 'admin'));