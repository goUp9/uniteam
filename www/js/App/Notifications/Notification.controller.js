App.controller('NotificationCtrl', function($http,Spinner,$scope){
        var self=this;
        
        $scope.unread=function(id){
            Spinner.spin();
            $http({
                    method  : 'GET',
                    url     : 'http://'+document.domain+'/ajax/set-unread-notification/'+id+'/',
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                        window.location.href='http://'+document.domain+'/notifications/';
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        };
});