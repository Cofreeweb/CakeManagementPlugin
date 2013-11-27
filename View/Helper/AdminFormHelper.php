<?php
App::uses('FormHelper', 'View/Helper');
App::uses('Set', 'Utility');

class AdminFormHelper extends FormHelper 
{
  
  public function __construct(View $View, $settings = array()) 
  {
		$this->helpers [] = 'Js';
		parent::__construct($View, $settings);	
	}
	
	
/**
 * undocumented function
 *
 * @param string $fieldName 
 * @param string $options 
 * @return void
 */
  public function input($fieldName, $options = array())
  {
    $_defaults = array(
      'colsInput' => 6,
      'colsLabel' => 3
    );
    $this->setEntity($fieldName);
    $model = $this->_getModel( $this->model());
    
    $options = array_merge( $_defaults, $options);
    $options = $this->_parseOptions($options);
    $div_options = $options;    
    
    unset( $options ['colsInput']);
    unset( $options ['colsLabel']);
    
    
    if( in_array( $options ['type'], array( 'text', 'textarea', 'password', 'select')))
    {
      $options = $this->__optionsInputText( $fieldName, $div_options, $options);
    }
    elseif( $options ['type'] == 'select' && isset( $options ['multiple']) && $options ['multiple'] == 'checkbox')
    {
      $options = $this->__optionsInputMultipleCheckbox( $fieldName, $div_options, $options);
    }
    elseif( $options ['type'] == 'select')
    {
      $options = $this->__optionsInputSelect( $fieldName, $div_options, $options);
    }
    elseif( $options ['type'] == 'checkbox')
    {
      $options = $this->__optionsInputCheckbox( $fieldName, $div_options, $options);
    }

    if( isset( $div_options ['help']))
    {
      $options ['after'] = $options ['after'] . $this->help( $div_options ['help']);
    }
    
    // Verifica si el campo es traducible
    // Si lo es le coloca el idioma dentro 
    if( $this->hasTranslation( $model, $this->field()))
    {
      // Guarda el between para luego modificarlo en cada idioma
      $between = $options ['between'];
      
      $out = array();
      
      $div_class = isset( $options ['div']['class']) ? $options ['div']['class'] : '';
      $plural = Inflector::camelize( Inflector::pluralize( $this->field()));
      
      if( isset( $this->request->data [$plural]))
      {
        $values = Hash::combine( $this->request->data [$plural], '{n}.locale', '{n}.content');
      }
      
      // Recorre los idiomas para crear un input por cada uno de ellos
      foreach( Configure::read( 'Config.languages') as $key => $locale)
      {
        $options ['div']['class'] = $div_class .' locale locale-'. $locale;
        
        if( $key > 0)
        {
          $options ['div']['class'] .= ' hidden';
        }
        
        if( !empty( $values))
        {
          $options ['value'] = $values [$locale];
        }
        
        // Pone el nombre del idioma en cada input y le coloca un color de estilo
        if( $options ['type'] == 'text')
        {
          $color = Configure::read( 'Management.langcolors.'. $locale);
          $options ['between'] = $between . '<span class="input-group-addon '. $color . '">'. $locale .'</span>';
        }
        
        // Añade el input al array de salida
        $out [] = parent::input( $fieldName .'.'. $locale, $options) . '<div class="space-4"></div>';
      }
      
      return implode( "\n", $out);
    }
    else
    {
      $out = parent::input( $fieldName, $options) . '<div class="space-4"></div>';
      
      if( $options ['type'] == 'checkbox')
      {
        $pos = strpos( $out, '<input');
        $pos = $pos;
        preg_match_all("(<label.*>)siU", $out, $matching_data);
        $label = $matching_data [0][0];
        $out = str_replace( $label, '', $out);
        $out = substr( $out, 0, $pos) . $label . substr($out, $pos );
      }
      return $out;
    }
  }
  
/**
 * Botón de submit
 *
 * @param string $label 
 * @return HTML
 */
  public function submit( $label, $options = array())
  {
    $_options = array(
        'class' => 'btn btn-info',
        'icon' => 'icon-ok bigger-110'
    );
    
    $options = array_merge( $_options, $options);
    
    return '<button type="submit" class="'. $options ['class'] .'"><i class="'. $options ['icon'] .'"></i>'. $label .'</button>';
    
  }
  
/**
 * Modifica las options del input para un tipo de campo text, para darle el estilo bootstrap
 *
 * @param string $fieldName 
 * @param array $div_options 
 * @param array $options 
 * @return array
 */
  private function __optionsInputText( $fieldName, $div_options, $options)
  {
    if( !isset( $options ['div']) || $options ['div'] !== false)
    {
      // Coloca el class al div envoltorio
      $options ['div']['class'] = 'input form-group row';
      $options ['div'] = $this->addClass( $options ['div'], $options ['type']);
      
      if( !isset( $options ['after']))
      {
        $options ['after'] = '';
      }
      
      // Coloca el div envoltorio al <input>
      if( $div_options ['colsInput'])
      {
        $options ['between'] = '<div class="input-group col-sm-'. $div_options ['colsInput'] .' col-xs-'. $div_options ['colsInput'] .'">';
        $options ['after'] = $options ['after'] . '</div>';
      }
      
    }
    
    
    // El label
    if( !isset( $options ['label']))
    {
      $options ['label'] = $fieldName;
    }
    
    if( $options ['label'])
    {
      $options ['label'] = array(
        'text' => $options ['label'],
        'class' => 'col-sm-'. $div_options ['colsLabel'] .' control-label no-padding-right'
      );
    }
    
    if( empty( $options ['class']))
    {
      $options ['class'] = 'col-xs-12 col-sm-12';
    }
    
    return $options;
  }

/**
 * Modifica las options del input para un tipo de campo checkbox, para darle el estilo bootstrap
 *
 * @param string $fieldName 
 * @param array $div_options 
 * @param array $options 
 * @return array
 */  
  private function __optionsInputCheckbox( $fieldName, $div_options, $options)
  {
    // El label
    if( !isset( $options ['label']))
    {
      $options ['label'] = $fieldName;
    }

    $options ['label'] = array(
      'text' => '<span class="lbl"> '. $options ['label'] .'</span>',
    );
    
    $options ['class'] = 'ace';
    $options ['div']['class'] = 'input checkbox col-lg-offset-3';
    return $options;
  }
 
/**
 * Modifica las options del input para un tipo de campo multiple checkbox, para darle el estilo bootstrap
 *
 * @param string $fieldName 
 * @param array $div_options 
 * @param array $options 
 * @return array
 */							
  private function __optionsInputMultipleCheckbox( $fieldName, $div_options, $options)
  {
    // El label
    if( !isset( $options ['label']))
    {
      $options ['label'] = $fieldName;
    }
    
    $options ['label'] = array(
      'text' => '<span class="lbl">'. $options ['label'] .'</span>',
      'class' => 'control-label bolder blue'
    );
    
    return $options;
  }
  
 /**
 * Modifica las options del input para un tipo de campo select, para darle el estilo bootstrap
 *
 * @param string $fieldName 
 * @param array $div_options 
 * @param array $options 
 * @return array
 */
  private function __optionsInputSelect( $fieldName, $div_options, $options)
  {
    $options ['class'] = '';
    return $options;
  }
  

 
/**
* Modifica las options de un select para darle el estilo bootstrap
 *
 * @param array $elements 
 * @param array $parents 
 * @param string $showParents 
 * @param array $attributes 
 * @return array
 */
	protected function _selectOptions($elements = array(), $parents = array(), $showParents = null, $attributes = array()) {
		$select = array();
		$attributes = array_merge(
			array('escape' => true, 'style' => null, 'value' => null, 'class' => null),
			$attributes
		);
		$selectedIsEmpty = ($attributes['value'] === '' || $attributes['value'] === null);
		$selectedIsArray = is_array($attributes['value']);

		foreach ($elements as $name => $title) {
			$htmlOptions = array();
			if (is_array($title) && (!isset($title['name']) || !isset($title['value']))) {
				if (!empty($name)) {
					if ($attributes['style'] === 'checkbox') {
						$select[] = $this->Html->useTag('fieldsetend');
					} else {
						$select[] = $this->Html->useTag('optiongroupend');
					}
					$parents[] = $name;
				}
				$select = array_merge($select, $this->_selectOptions(
					$title, $parents, $showParents, $attributes
				));

				if (!empty($name)) {
					$name = $attributes['escape'] ? h($name) : $name;
					if ($attributes['style'] === 'checkbox') {
						$select[] = $this->Html->useTag('fieldsetstart', $name);
					} else {
						$select[] = $this->Html->useTag('optiongroup', $name, '');
					}
				}
				$name = null;
			} elseif (is_array($title)) {
				$htmlOptions = $title;
				$name = $title['value'];
				$title = $title['name'];
				unset($htmlOptions['name'], $htmlOptions['value']);
			}

			if ($name !== null) {
				$isNumeric = is_numeric($name);
				if (
					(!$selectedIsArray && !$selectedIsEmpty && (string)$attributes['value'] == (string)$name) ||
					($selectedIsArray && in_array((string)$name, $attributes['value'], !$isNumeric))
				) {
					if ($attributes['style'] === 'checkbox') {
						$htmlOptions['checked'] = true;
					} else {
						$htmlOptions['selected'] = 'selected';
					}
				}

				if ($showParents || (!in_array($title, $parents))) {
					$title = ($attributes['escape']) ? h($title) : $title;

					$hasDisabled = !empty($attributes['disabled']);
					if ($hasDisabled) {
						$disabledIsArray = is_array($attributes['disabled']);
						if ($disabledIsArray) {
							$disabledIsNumeric = is_numeric($name);
						}
					}
					if (
						$hasDisabled &&
						$disabledIsArray &&
						in_array((string)$name, $attributes['disabled'], !$disabledIsNumeric)
					) {
						$htmlOptions['disabled'] = 'disabled';
					}
					if ($hasDisabled && !$disabledIsArray && $attributes['style'] === 'checkbox') {
						$htmlOptions['disabled'] = $attributes['disabled'] === true ? 'disabled' : $attributes['disabled'];
					}

					if ($attributes['style'] === 'checkbox') {
						$htmlOptions['value'] = $name;

						$tagName = $attributes['id'] . Inflector::camelize(Inflector::slug($name));
						$htmlOptions['id'] = $tagName;
						$label = array('for' => $tagName);

						if (isset($htmlOptions['checked']) && $htmlOptions['checked'] === true) {
							$label['class'] = 'selected';
						}

						$name = $attributes['name'];

						if (empty($attributes['class'])) {
							$attributes['class'] = 'checkbox';
						} elseif ($attributes['class'] === 'form-error') {
							$attributes['class'] = 'checkbox ' . $attributes['class'];
						}
						$htmlOptions ['class'] = 'ace';
						$item = $this->Html->useTag('checkboxmultiple', $name, $htmlOptions);
            $label = $this->label(null, $item ."\n". $this->Html->tag( 'span', " ". $title, array(
              'class' => 'lbl'
            )), $label);
						$select[] = $this->Html->div($attributes['class'], $label);
					} else {
						$select[] = $this->Html->useTag('selectoption', $name, $htmlOptions, $title);
					}
				}
			}
		}

		return array_reverse($select, true);
	}
  
  
/**
 * Retorna true si el campo del model tiene traducción
 *
 * @param object $model 
 * @param string $field 
 * @return boolean
 */
  public function hasTranslation( $model, $field)
  {
    return isset( $model->actsAs ['Translate']) && isset( $model->actsAs ['Translate'][$field]);
  }
  
  
/**
 * Renderiza un CKEditor
 *
 * @param string $field 
 * @param array $attributes Los atributos clásicos del FormHelper de CakePHP
 * @param array $options Opciones de CKEditor (ver enlace)
 * @return HTML
 * @link http://ckeditor.com/ckeditor_4.3_beta/samples/plugins/toolbar/toolbar.html
 */
  public function ckeditor( $field, $attributes, $options = array())
  {  
    $_options = array(
        'toolbar' => array(
            array(
                'name' => 'clipboard',
                'groups' => array(
                    'clipboard', 'undo'
                ),
                'items' => array(
                    'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'
                )
            ),
            array(
                'name' => 'basicstyles',
                'items' => array(
                    'Bold', 'Italic', 'Underline'
                )
            ),
            array(
                'name' => 'links',
                'items' => array(
                    'Link', 'Unlink'
                )
            ),
            
            array(
                'name' => 'styles',
                'items' => array(
                    'Styles', 'Format'
                )
            ),
            array(
                'name' => 'paragraph',
                'groups' => array(
                    'list', 'indent', 'blocks', 'align'
                ),
                'items' => array(
                    'NumberedList', 'BulletedList'
                )
            ),
            array(
                'name' => 'document',
                'groups' => array(
                    'mode', 'document', 'doctools',
                ),
                'items' => array(
                    'Source'
                )
            ),
        ),
        'width' => '100%',
        'height' => '300px'
    );
    
    $options = array_merge( $_options, $options);

    // El ID al estilo Cake
    $id = $class = Inflector::camelize( str_replace( '.', '_', $field));

    $out [] = $this->input( $field, array(
        'type' => 'textarea',
        'id' => $id,
        'class' => $class,
        'label' => $attributes ['label'],
        'colsInput' => 8
    ));
    
    $json = $this->Js->object( $options);
    
    
    $js = <<<EOF
    $(".$class").ckeditor($json);
EOF;
    
    $this->Js->buffer( $js);
    return implode( "\n", $out); 
  }
  
  
/**
 * Devuelve la navegación de idiomas
 * Además, incluye el javascript necesario para el cambio de idioma en los campos
 *
 * @return void
 */
  public function localeNav()
  {
    $locales = Configure::read( 'Config.languages');

    $Locale = ClassRegistry::init( 'I18n.Locale');
    
    $languages = array();
    
    if( $Locale)
    {
      $langs = $Locale->find( 'list', array(
          'fields' => array(
              'iso3',
              'name'
          )
      ));
      
      foreach( $locales as $locale)
      {
        $languages [$locale] = $langs [$locale];
      }
    }
    else
    {
      $languages = array_combine( array_values( $locales), array_values( $locales));
    }
    
    $list = array();
    
    $first = true;
    
    foreach( $languages as $key => $language)
    {
      $color = Configure::read( 'Management.langcolorsnav.'. $key);
      $class = $first ? ' active' : '';
      $list [] = '<a class="btn btn-sm btn-' . $color . $class .'" data-lang="'. $key .'">'. $language .'</a>';
      $first = false;
    }
    
    $js = <<<EOF
    $("#locale-nav a").click(function(){
      changeLang( this);
    });
    
    function changeLang( el) {
      $("#locale-nav a").removeClass( "active");
      $(el).addClass( "active");
      $(".locale").hide();
      $(".locale-" + $(el).data( "lang")).removeClass( "hidden").show();
    }
    changeLang( $("#locale-nav a.active"))
EOF;
    $this->Js->buffer( $js);
    return '<div id="locale-nav" class="page-language"><div class="col-sm-2"><p class="blue"><strong>' . __d( 'admin', 'Seleccione un idioma:') .'</strong></p></div>'.implode( "\n", $list) .'</div>';
  }
  
  public function help( $text)
  {
    return '<span class="help-button icon icon-question" data-rel="popover" data-trigger="hover" data-placement="left" data-content="'. $text .'"></span>';
  }
}