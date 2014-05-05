adminApp.controller( 'GroupsCtrl', function( $scope, $routeParams, $http) {
    $http.get( '/admin/acl/groups/index.json').success( function(data) {
      $scope.groups = data.groups;
    });
});

adminApp.controller( 'GroupsEditCtrl', function( $scope, $routeParams, $http) {
    $http.get( '/admin/acl/groups/edit/' + $routeParams.id + '.json').success( function(data) {
      $scope.permissions = data.permissions;
      $scope.data = data.data;
    });
    
    $scope.submit = function( action){
      
      $http.post( '/admin/acl/groups/edit/' + $routeParams.id + '.json', $scope.data).success( function( data){

      })
    }
});

adminApp.controller( 'ConfigurationCtrl', function( $scope, $routeParams, $http) {
    $http.get( '/admin/managemetn/groups/edit/' + $routeParams.id + '.json').success( function(data) {
      $scope.permissions = data.permissions;
      $scope.data = data.data;
    });
    
    $scope.submit = function( action){
      
      $http.post( '/admin/acl/groups/edit/' + $routeParams.id + '.json', $scope.data).success( function( data){

      })
    }
});