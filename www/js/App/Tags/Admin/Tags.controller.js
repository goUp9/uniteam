Admin.controller('TagsCtrl', function($http,$scope,Spinner){
        var self=this;
        this.postData={'page':1,'searchVal':'','orderBy':'t.tag'};
        this.bulkPageFlag=false;
        this.tagGroupData={};
        
        this.get_tags=function() {
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/get-tags/',
                data    : $.param(self.postData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                    window.console.log(data);                  
                        $scope.tags=data.data;                        
                        $scope.pages=self.set_pages(data.totalPages);
                        self.tagGroupData=data.data;                        
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        this.get_cats=function() {
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/get-taggroups/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) { 
//                    window.console.log(data);
                        $scope.cats=data.data;
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        }; 
        
        this.get_cats();        
        this.get_tags();
        
        this.add_tag=function(){
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/add-tag/',
                data    : $.param({'tag':self.postData.searchVal}),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                    window.console.log(data);
                    self.get_tags();
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        this.set_pages=function(numPages){
            var pages=[];
            for(var i=0;i<numPages;i++){
                pages.push(i+1);
            }
            return pages;
        };
        
        this.go_to_page=function($index){
            self.postData.page=$index+1;
            self.get_tags();
        };
        
        this.save_change=function($index){
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/save-tag-change/',
                data    : $.param($scope.tags[$index]),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {                    
                    $scope.tags[$index].old=$scope.tags[$index].tag;    
                    window.console.log(data); 
                    Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        this.all_active=function(){
            for(var i=0;i<$scope.tags.length;i++){                
                if($scope.tags[i].bulk===true){                    
                    $scope.tags[i].status=true;
                    self.save_change(i);
                }
            }            
        };
        
        this.all_disabled=function(){
            for(var i=0;i<$scope.tags.length;i++){                
                if($scope.tags[i].bulk===true){                    
                    $scope.tags[i].status=false;
                    self.save_change(i);
                }
            }            
        };
        
        this.bulk_page=function(){
            self.bulkPageFlag=!self.bulkPageFlag;
            if(self.bulkPageFlag){
                for(var i=0;i<$scope.tags.length;i++){                
                    $scope.tags[i].bulk=true;          
                } 
            }
            else{
                for(var i=0;i<$scope.tags.length;i++){                
                    $scope.tags[i].bulk=false;                
                } 
            }
        };
        
        this.set_cat=function($index){
//            window.console.log($scope.tags[$index]);
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/save-taggroup-change/',
                data    : $.param($scope.tags[$index]),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {    
//                    window.console.log(data);
                    self.get_tags();
                    Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        /* sorting */        
        this.order = function(predicate) {
            
        };
        
});
