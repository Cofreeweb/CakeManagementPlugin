var adminApp = angular.module('adminApp', [
  'ngRoute', 
  'ngSlider',
  'ngSanitize', 
  'ui.tree', 
  'checklist-model', 
  'angular-flexslider', 
  'ui.bootstrap',
  'ui.sortable',
  'dialogs',
  'ui.router',
  'builder', 
  'builder.components',
  'validator.rules',
  'pagination',
  'multilingue'
]);

/**
* clickAnywhereButHere Directive
* 
* Directiva para que cuando se haga click fuera del elemento, éste desaparezca.
*/
adminApp.directive('clickAnywhereButHere', function($document, $location, $route){
  return {
    restrict: 'A',
    scope: {
      callback : '=clickAnywhereButHere'
    },
    link: function(scope, element, attr, ctrl) {

      element.on('click', function(e) {
        // this part keeps it from firing the click on the document.
        e.stopPropagation();
      });
      angular.element( 'a, .cke_dialog').on( 'click', function(e) {
        e.stopPropagation();
      });   
      
      
      
      $('body').delegate( '.modal-content', 'click', function(e) {
        // this part keeps it from firing the click on the document.
        e.stopPropagation();
      });
      
      var handler = function(event) {
        if (!element[0].contains(event.target)) {
            scope.callback(event);
            
            $location.path( '/');
            scope.$apply();
            if( scope.$parent.update) {
              scope.$parent.update();
            }
         }
         
      };

      $document.on('click', handler);
      scope.$on('$destroy', function() {
          $document.off('click', handler);
      });
    }
  }
});



adminApp.directive( 'closeWindow', function( $document, $location, $route){
  return {
    restrict: 'A',
    link: function(scope, element, attr, ctrl) {
      var button = angular.element( '<span>Close</span>');
      button.on( 'click', function(){
        $location.path( '/');
        scope.$apply();
      })
      element.prepend( button);
    }
  }
})


adminApp.run(function( $rootScope, $http, $location){
    
  /**
  * deleteUpload()
  *
  * Borrado de uploads
  */
  $rootScope.deleteUpload = function( el){
    var asset = el.asset;
    console.log( el);
    return;
    $http.post( '/upload/uploads/delete/' + asset.model + '/' + asset.filename + '/' + asset.id + '.json').success( function( data) {
      if( data.success) {
        var el = '#upload_' + asset.id;
        angular.element( el).remove();
      }
    });
    return false;
  }
  
  $rootScope.clickedSomewhereElse = function( $scope){
    
  }
});


