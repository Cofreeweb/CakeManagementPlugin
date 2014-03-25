var adminApp = angular.module('adminApp', ['ngRoute', 'ngSlider','ngSanitize', 'ui.nestedSortable', 'checklist-model', 'dialogs']);



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
      angular.element( 'a').on('click', function(e) {
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


// Borrado de secciones
adminApp.run(function( $rootScope, $http, $location){
  $rootScope.deleteUpload = function( el){
    var asset = el.asset;
    $http.post( '/upload/uploads/delete/' + asset.model + '/' + asset.filename + '/' + asset.id + '.json').success( function( data) {
      if( data.success) {
        var el = '#upload_' + asset.id;
        angular.element( el).remove();
      }
    });
    return false;
  }
  // 
  // $rootScope.submit = function( $scope){
  //   console.log( $scope.data);
  //   $http.post( url, $scope.data).success( function( data){
  //     $rootScope.inline  = 'Hoooooooooola'
  //   })
  // }
  
  $rootScope.clickedSomewhereElse = function( $scope){
    
  }
})