App.controller('AskArchivedCtrl', function($http,Spinner,$scope){
        var self=this;
        this.userMsg="";
        
        $scope.details=[];
        $scope.detailsFlag=[];
        
        this.get_asks=function(sortBy) {            
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/myuin-get-asks-archived/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        if(data.length===0){
                            self.userMsg="No queries";
                            $scope.queries=[];
                        }
                        else {
                            $scope.queries=data;               
                            self.userMsg="";
                        }
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        this.get_asks();
        
        this.get_details=function(id,index){
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/myuin-get-ask-details/'+id+'/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        $scope.details[index]=data;
                        $scope.detailsFlag[index]=true;
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        this.unarchive=function(id){
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/myuin-ask-unarchive-query/'+id+'/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        self.get_asks();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
});
