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


Router::connect( '/admin/crud/:model/:action/*', array(
    'plugin' => 'management',
    'controller' => 'crud',
));
