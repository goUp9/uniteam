App.controller('SupplyCtrl', function($http,Spinner,$scope){
        var self=this;
        this.userMsg="";
        
        this.get_asks=function() {
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/myuin-get-supplies/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                    window.console.log(data);
                        if(data.length===0){
                            self.userMsg="No messages";
                        }
                        else {
                            $scope.queries=data;                        
                            //$scope.pages=self.set_pages(data.totalPages);
                            self.userMsg="";
                        }
//                        window.console.log($scope.queries); 
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        this.get_asks();
        
        this.remove_loc=function($index){  
            Spinner.spin();
            var id=$scope.queries[0].wheres[$index].id;
//            window.console.log(id)
            $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/supply/remove-location/',
                    data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            window.console.log(data);
                            self.get_asks();
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        };
        
});
