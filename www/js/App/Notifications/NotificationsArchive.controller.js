App.controller('NotificationsArchiveCtrl', function($http,Spinner,$scope){
        var self=this;
        
        $scope.get=function(sortBy){
            sortBy=sortBy||''; 
            Spinner.spin();
            $http({
                    method  : 'GET',
                    url     : 'http://'+document.domain+'/ajax/get-archived-notifications/'+sortBy+'/',
                    //data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                        Spinner.stop();
                        if(data.status===1){
                            $scope.notifications=data.data;
                            $scope.notificationsMsg='';
                        }
                        else {
                            $scope.notifications=[];
                            $scope.notificationsMsg=data.msg;
                        }
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        };
        
        $scope.get();
        
        this.unarchive=function(id){
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/myuin-unarchive-notification/'+id+'/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        $scope.get();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
});


App.filter('typeToLink',function() {
    return function(input) {
        if (input) {
            return input.replace(/\s+/g, '-');    
        }
    }
});