/**
 * Checklist-model
 * AngularJS directive for list of checkboxes
 * http://vitalets.github.io/checklist-model/
 */

angular.module('checklist-model', [])
.directive('checklistModel', ['$parse', '$compile', function($parse, $compile) {
  // contains
  function contains(arr, item) {
    if (angular.isArray(arr)) {
      for (var i = 0; i < arr.length; i++) {
        if (angular.equals(arr[i], item)) {
          return true;
        }
      }
    }
    return false;
  }

  // add
  function add(arr, item) {
    arr = angular.isArray(arr) ? arr : [];
    for (var i = 0; i < arr.length; i++) {
      if (angular.equals(arr[i], item)) {
        return arr;
      }
    }    
    arr.push(item);
    return arr;
  }  

  // remove
  function remove(arr, item) {
    if (angular.isArray(arr)) {
      for (var i = 0; i < arr.length; i++) {
        if (angular.equals(arr[i], item)) {
          arr.splice(i, 1);
          break;
        }
      }
    }
    return arr;
  }

  // http://stackoverflow.com/a/19228302/1458162
  function postLinkFn(scope, elem, attrs) {
    // compile with `ng-model` pointing to `checked`
    $compile(elem)(scope);

    // getter / setter for original model
    var getter = $parse(attrs.checklistModel);
    var setter = getter.assign;

    // value added to list
    var value = $parse(attrs.checklistValue)(scope.$parent);

    // watch UI checked change
    scope.$watch('checked', function(newValue, oldValue) {
      if (newValue === oldValue) { 
        return;
      } 
      var current = getter(scope.$parent);
      if (newValue === true) {
        setter(scope.$parent, add(current, value));
      } else {
        setter(scope.$parent, remove(current, value));
      }
    });

    // watch original model change
    scope.$parent.$watch(attrs.checklistModel, function(newArr, oldArr) {
      scope.checked = contains(newArr, value);
    }, true);
  }

  return {
    restrict: 'A',
    priority: 1000,
    terminal: true,
    scope: true,
    compile: function(tElement, tAttrs) {
      if (tElement[0].tagName !== 'INPUT' || !tElement.attr('type', 'checkbox')) {
        throw 'checklist-model should be applied to `input[type="checkbox"]`.';
      }

      if (!tAttrs.checklistValue) {
        throw 'You should provide `checklist-value`.';
      }

      // exclude recursion
      tElement.removeAttr('checklist-model');
      
      // local scope var storing individual checkbox model
      tElement.attr('ng-model', 'checked');

      return postLinkFn;
    }
  };
}]);


/**
* columnResize Directive
* 
* Se encarga de crear los botones de aumento y disminución de la anchura de los bloques
*/
adminApp.directive('columnResize', ['$http', function( $http) {
  
/**
* Tamaño máximo de columna
*/
  var maxValue = 6;
  
/**
* Tamaño mínimo de columna
*/
  var minValue = 1;
  
/**
* Se encarga de enviar los datos al servidor
*/
  function sendData( $element, value) {
    $http.post( '/entry/blocks/resize.json', {
      id: $element.data( 'id'),
      value: value
    }).success( function( data){
      
    })
  }
  return {
    restrict: 'A',    
    replace: false,
    compile: function ($element, attrs) {
      // Botón de ampliar anchura de columna
      var enlarge = angular.element( ' <span>' + $element.data( 'text-increase') + '</span> ');
      angular.element( enlarge).on( 'click', function( event){
        if( attrs.columnResize >= maxValue) {
          return;
        }
        
        $element.removeClass( 'col-' + attrs.columnResize);
        attrs.columnResize++;
        $element.addClass( 'col-' + attrs.columnResize);
      });
      
      // Botón de disminuir anchura de columna
      var decrease = angular.element( ' <span>' + $element.data( 'text-decrease') + '</span> ');
      angular.element( decrease).on( 'click', function( event){
        if( attrs.columnResize <= minValue) {
          return;
        }
        
        $element.removeClass( 'col-' + attrs.columnResize);
        attrs.columnResize--;
        $element.addClass( 'col-' + attrs.columnResize);
        sendData( $element, attrs.columnResize);
      });
      angular.element($element).prepend( enlarge);
      angular.element($element).prepend( decrease);
    }
  };
}]);


adminApp.directive('ngConfirmClick', [
  function(){
    return {
      priority: -1,
      restrict: 'A',
      link: function(scope, element, attrs){
        element.bind('click', function(e){
          var message = attrs.ngConfirmClick;
          if(message && !confirm(message)){
            e.stopImmediatePropagation();
            e.preventDefault();
          }
        });
      }
    }
  }
]);

/**
* deleteContent directive
*
* Se encarga de generar un botón que al hacer click va a mostrar un cuadro de Confirm 
* y al aceptar va enviar un post a una URL dada, junto con un id
* También borrará, si existe, el elemento del DOM
* Opcionalmente, se puede pasar un mensaje para mostrar en el cuadro de confirm
* 
* @example <span delete-content="/url/to/delete" data-id="12" data-msg="¿Seguro que quieres borrar este contenido">Borrar</span>
*/
adminApp.directive( 'deleteContent', function( $document, $http, $rootScope, $dialogs){
  return {
    restrict: 'A',
    link: function( scope, element, attr, ctrl){
      element.on( 'click', function(e) {
        var header = attr.header || '¿Estás seguro';
        var msg = attr.msg || '';
        var dlg = $dialogs.confirm( header, msg);
        dlg.result.then( function( btn){
          // Si hay url, entonces se envia un post
          if( attr.deleteContent) {
            $http.post( attr.deleteContent, {id: attr.id}).success( function( data){
              if( data.success) {
                angular.element( attr.remove).remove();
              }
            })
          } else {
            // Borra el elemento del scope, si es que se ha dado
            if( attr.deleteScope && attr.deleteScopeIndex) {
              var cod = 'scope.' + attr.deleteScope + '.splice(' + attr.deleteScopeIndex +', 1)';
              eval( cod);
            }
            angular.element( attr.remove).remove();
          }
          
        });
      })
    }
  }
});