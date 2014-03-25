adminApp.config( ['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/groups', {
        templateUrl: '/angular/template?t=Acl.groups/angular_index',
        controller: 'GroupsCtrl'
      }).
      when('/groups/edit/:id', {
        templateUrl: '/angular/template?t=Acl.groups/angular_edit',
        controller: 'GroupsEditCtrl'
      })
  }
]);