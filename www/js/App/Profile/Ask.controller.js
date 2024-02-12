App.controller('AskCtrl', function($http,Spinner,$scope){
        var self=this;
        this.userMsg="";
        
        this.get_asks=function() {
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/myuin-get-asks/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                    window.console.log(data);
                        if(data.length===0){
                            self.userMsg="No messages";
                        }
                        else {
                            $scope.queries=data;                        
//                            $scope.pages=self.set_pages(data.totalPages);
                            self.userMsg="";
                        }
//                        window.console.log($scope.queries); 
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        this.get_asks();
        
        this.remove_loc=function(id){  
            Spinner.spin();
           
            $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/supply/remove-location/',
                    data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
//                            window.console.log(data);
                            self.get_asks();
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        };
        
        this.find_matches=function(id){
            id=id|null;
            Spinner.spin();
            var url;
            if(id===null){
                url='http://'+document.domain+'/ajax/match_suppliers/';
            }
            else {
                url='http://'+document.domain+'/ajax/match_suppliers/'+id+'/';
            }
            $http({
                    method  : 'GET',
                    url     : url,
                    //data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
//                            window.console.log(data);
                         window.location='http://'+document.domain+'/ajax/finalise/';
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        };
        
        this.archive=function(id){
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/myuin-ask-archive-query/'+id+'/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        self.get_asks();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        
        
});
