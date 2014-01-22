<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/admin/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/admin
 */
class Admin {

    /**
     * Cached data.
     *
     * @type array
     */
    protected static $_cache = array();
    
    protected static $_adminDefaults = array(
        'actions' => array(
            'create',
            'index'
        )
    );
    
    public function loadCruds()
    {
      $path = APP . 'Config' .DS. 'cruds.php';

      if( file_exists( $path))
      {
        Configure::load( 'cruds');
      }

      $plugins = App::objects( 'plugin');

      foreach( $plugins as $plugin)
      {
        if( CakePlugin::loaded( $plugin))
        {
          $path = App::pluginPath( $plugin) . 'Config'. DS. 'cruds.php';

          if( file_exists( $path))
          {
            Configure::load( $plugin .'.cruds');
          }
        }
      }
      
      $config = Configure::read( 'cruds');
      Configure::write( 'Management.crud', $config);
    }
    
    /**
     * Cache the result of the callback into the class.
     *
     * @param string|array $key
     * @param callable $callback
     * @return mixed
     */
    public static function cache($key, Closure $callback) {
        if (is_array($key)) {
            $key = implode('-', $key);
        }

        if (isset(self::$_cache[$key])) {
            return self::$_cache[$key];
        }

        self::$_cache[$key] = $callback();

        return self::$_cache[$key];
    }

    /**
     * Return a list of all models grouped by plugin.
     *
     * @return array
     */
    public static function getModels() {
        return self::cache(__METHOD__, function() {
            $plugins = array_merge(array(Configure::read('Admin.coreName')), App::objects('plugins'));
            $map = array();

            foreach ($plugins as $plugin) {
                $data = Admin::getPlugin($plugin);

                if ($data['models']) {
                    $map[$plugin] = $data;
                }
            }

            ksort($map);

            return $map;
        });
    }

    /**
     * Return meta information on a plugin while also including the model list.
     *
     * @param string $plugin
     * @return array
     */
    public static function getPlugin($plugin) {
        return self::cache(array(__METHOD__, $plugin), function() use ($plugin) {
            $path = null;

            if ($plugin !== Configure::read('Admin.coreName')) {
                if (!CakePlugin::loaded($plugin)) {
                    return null;
                }

                $path = CakePlugin::path($plugin);
            }

            return array(
                'title' => $plugin,
                'path' => $path,
                'slug' => Inflector::underscore($plugin),
                'models' => Admin::getPluginModels($plugin)
            );
        });
    }

    /**
     * Return a list of all models within a plugin.
     *
     * @param string $plugin
     * @return array
     */
    public static function getPluginModels($plugin) {
        return self::cache(array(__METHOD__, $plugin), function() use ($plugin) {
            $search = 'Model';
            $core = Configure::read('Admin.coreName') ?: 'Core';

            if ($plugin !== $core) {
                $search = $plugin . '.' . $search;
            }

            // Fetch models and filter out AppModel's
            $models = array_filter(App::objects($search), function($value) {
                return (mb_strpos($value, 'AppModel') === false);
            });

            // Filter out models that don't connect to the database or are admin disabled
            $map = array();
            $ignore = Configure::read('Admin.ignoreModels');

            foreach ($models as $model) {
                list($plugin, $model, $id, $class) = Admin::parseName($plugin . '.' . $model);

                if (in_array($id, $ignore)) {
                    continue;
                }

                $object = Admin::introspectModel($id);

                if (!$object) {
                    continue;
                }

                $map[] = array_merge($object->admin, array(
                    'id' => $id,
                    'title' => $object->pluralName,
                    'alias' => $model,
                    'class' => $class,
                    'url' => Inflector::underscore($id),
                    'installed' => Admin::isModelInstalled($id),
                    'group' => $object->useDbConfig
                ));
            }

            return $map;
        });
    }

    /**
     * Check to see if a user has specific CRUD access for a model.
     *
     * @param string $model
     * @param string $action
     * @param string $session
     * @param bool $exit - Exit early if the CRUD key doesn't exist in the session
     * @return bool
     */
    public static function hasAccess($model, $action, $session = 'Admin.crud', $exit = false) {
        if (!($model instanceof Model)) {
            $model = self::introspectModel($model);
        }

        $crud = Hash::get($_SESSION, $session);
        $exists = isset($crud[$model->qualifiedName][$action]);

        // Exit early
        if ($exit && !$exists) {
            return null;
        }

        $pass = ($exists && $crud[$model->qualifiedName][$action]);

        // Check editable
        if ($action === 'update') {
            return ($pass && $model->admin['editable']);
        }

        // Check deletable
        if ($action === 'delete') {
            return ($pass && $model->admin['deletable']);
        }

        return $pass;
    }

    /**
     * Check if a model has been installed into the ACO table.
     *
     * @param string $model
     * @return bool
     */
    public static function isModelInstalled($model) {
        return self::introspectModel('Admin.ControlObject')->hasAlias($model);
    }

    /**
     * Parse a model name to extract the plugin and fully qualified name.
     *
     * @param string $model
     * @return array
     */
    public static function parseName($model) {
        return self::cache(array(__METHOD__, $model), function() use ($model) {
            list($plugin, $model) = pluginSplit($model);
            $core = Configure::read('Admin.coreName');

            if (!$plugin) {
                $plugin = $core;
            }

            $plugin = Inflector::camelize($plugin);
            $model = Inflector::camelize($model);
            $class = $model;

            if ($plugin !== $core) {
                $class = $plugin . '.' . $class;
            }

            return array(
                $plugin, // plugin name, includes Core
                $model, // model class name
                $plugin . '.' . $model, // plugin and model, includes Core
                $class // plugin and model, excludes Core
            );
        });
    }

    /**
     * Introspect a model and append additional meta data.
     *
     * @param string $model
     * @return Model
     */
    public static function introspectModel($model) 
    {
        $default_admin = self::$_adminDefaults;
        
        return self::cache(array(__METHOD__, $model), function() use ($model, $default_admin) 
        {
            list($plugin, $model, $id, $class) = Admin::parseName($model);

            $pluginPath = ($plugin !== 'Core') ? $plugin . '.' : '';
            $object = ClassRegistry::init($pluginPath . $model);

            // Exit early if disabled
            if (!$object || empty($object->useTable) || (isset($object->admin) && $object->admin === false)) {
                return null;
            }

            // Override model
            $object->Behaviors->load('Containable');
            $object->Behaviors->load('Utility.Cacheable');
            $object->cacheQueries = false;
            $object->recursive = -1;
            
            // Default options
            $object->admin = array_merge( $default_admin, (array)$object->admin);

            if ($plugin !== 'Core') 
            {
              $object->plugin = $plugin;
            }

            // Inherit enums from parent classes
            if ($object->Behaviors->hasMethod('enum')) 
            {
              $object->enum = $object->enum();
            }

            // Generate readable names
            $object->urlSlug = Inflector::underscore($id);
            $object->qualifiedName = $id;
            $object->singularName = Inflector::humanize(Inflector::underscore($model));
            $object->pluralName = Inflector::pluralize($object->singularName);

            // Generate a list of field (database column) data
            $schema = $object->schema();
            
            foreach( $schema as $key => $data)
            {
              $schema [$key]['title'] = Inflector::humanize( $key);
            }
            
            $hideFields = array();
            
            if( empty( $object->fields))
            {
              $fields = array();
              
              foreach ($object->belongsTo as $alias => $assoc) 
              {
                $fields[$assoc['foreignKey']]['type'] = 'relation';
                $fields[$assoc['foreignKey']]['belongsTo'][$alias] = $assoc['className'];
              }

              $object->fields = $fields;
            }
            else
            {
              foreach( $object->fields as $field => $data)
              {
                if( is_numeric( $field))
                {
                  $object->fields [$data] = $schema [$data];
                  unset( $object->fields [$field]);
                  $field = $data;
                  $data = $object->fields [$field]; 
                }
                else
                {
                  $object->fields [$field] = array_merge( $schema [$field], $object->fields [$field]);
                }
                
                // Is a belongsTo foreign_key
                if( $object->isForeignKey( $field))
                {
                  foreach( $object->belongsTo as $_model => $info)
                  {
                    if( $field == $info ['foreignKey'])
                    {
                      $data ['type'] = 'list';
                      $data ['options'] = $object->$_model->find( 'list', $info);

                      if( ($data ['title'] == Inflector::humanize( $field) || empty( $data ['title'])) && isset( $object->$_model->admin ['nameSingular']))
                      {
                        $data ['title'] = $object->$_model->admin ['nameSingular'];
                      }
                      
                      $object->fields [$field] = $data;
                      break;
                    }
                  }
                }
              }
              
              foreach( $object->fields as $field => $data)
              {
                if( empty( $object->fields [$field]['title']))
                {
                  $object->fields [$field]['title'] = Inflector::humanize( $field);
                }
              }
            }
            
            // Files
            if( !empty( $object->admin ['fieldsFiles']))
            {
              foreach( $object->admin ['fieldsFiles'] as $field => $data)
              {
                $relatedModel = isset( $object->hasMany [$field]) 
                  ? $object->hasMany [$field]
                  : $object->hasOne [$field];
                
                $object->admin ['fieldsFiles'] [$field] = array_merge( $object->admin ['fieldsFiles'] [$field], $relatedModel);
              }
            }
            
            // Index
            if( !empty( $object->admin ['fieldsIndex']))
            {
              foreach( $object->admin ['fieldsIndex'] as $key => $field)
              {
                if( isset( $object->fields [$field]))
                {
                  $data = $object->fields [$field];
                }
                else
                {
                  $data = $schema [$field];
                }
                
                unset( $object->admin ['fieldsIndex'] [$key]);
                $object->admin ['fieldsIndex'] [$field] = $data;
              }
            }
            
            // Search
            if( !empty( $object->admin ['fieldsSearch']))
            {
              foreach( $object->admin ['fieldsSearch'] as $key => $field)
              {
                if( isset( $object->fields [$field]))
                {
                  $data = $object->fields [$field];
                }
                else
                {
                  $data = $schema [$field];
                }
                
                unset( $object->admin ['fieldsSearch'] [$key]);
                $object->admin ['fieldsSearch'] [$field] = $data;
              }
            }
            
            // Apply default admin settings
            $settings = isset( $object->admin) ? $object->admin : array();
            $defaultSettings = Configure::read( 'Admin.modelDefaults');

            if( is_array( $settings)) 
            {
              $settings = Hash::merge( $defaultSettings, $settings);
              $settings['fileFields'] = array_merge( $settings['fileFields'], $settings['imageFields']);
              $settings['hideFields'] = array_merge( $settings['hideFields'], $hideFields);

              $object->admin = $settings;
            } 
            else 
            {
              $object->admin = $defaultSettings;
            }

            // Update associated settings
            foreach( $object->hasAndBelongsToMany as &$assoc) 
            {
              $assoc = array_merge( array( 'showInForm' => true), $assoc);
            }

            return $object;
        });
    }
    
    
    public function modelName( $model)
    {
      $parents = class_parents( $model);
      
      $parent = current( $parents);
      
      if( $parent != 'AppModel')
      {
        return str_replace( 'AppModel', '', $parent) . '.'. $model->name;
      }
      
      return $model->name;
    }
}