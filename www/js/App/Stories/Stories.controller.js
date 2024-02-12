App.controller('StoriesCtrl', function($http,$scope){
        var self=this;
        $scope.page=1;
        
        $scope.get_items=function(){
                $http
                    ({ 
                        method  : 'GET',
                        url     : 'http://'+document.domain+'/admin/get-stories-articles/'+$scope.page+'/',
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    }).success(function(data){
                        $scope.items=data.data;
                        $scope.pages=[];
                        for(var i = 0; i<data.totalPages; i++){
                            $scope.pages.push({'page':i+1});
                        }
                    });                    
        };
        $scope.get_items();
        
        $scope.switch_page=function(page){
            $scope.page=page;
            $scope.get_items();
        };
});


