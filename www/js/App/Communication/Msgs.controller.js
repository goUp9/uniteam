App.controller('MsgsCtrl', function($http,$scope,Spinner){
        var self=this;
        this.postData={'page':1};
        this.userMsg="";
        this.readMsg=false;
        this.readingMsg={};
        this.currentIndex;
        
        this.boxType='inbox';
        
        this.get_msgs=function(type) {
            Spinner.spin();
            var url;
            if(type=='inbox'){
                url='http://'+document.domain+'/ajax/get-pm/';
                self.boxType='inbox';
            }
            else if(type=='outbox') {
                url='http://'+document.domain+'/ajax/get-pm-outbox/';
                self.boxType='outbox';
            }
            $http({
                method  : 'POST',
                url     : url,
                data    : $.param(self.postData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) { 
//                        window.console.log(data);                    
                        if(data.length===0){
                            self.userMsg="No messages";
                        }
                        else {
                            $scope.msgs=data.data;                        
                            $scope.pages=self.set_pages(data.totalPages);
                            self.userMsg="";
                        }    
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        this.get_msgs('inbox');
        
        this.save_change=function($index){
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/update-msg-data/',
                data    : $.param($scope.msgs[$index]),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
//                    window.console.log(data); 
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
            self.postData={'page':$index+1};
            self.get_msgs();
        };
        
        this.read_msg=function($index){
            self.readingMsg=$scope.msgs[$index];
            if($scope.msgs[$index].read===false){
                $scope.msgs[$index].read=true;
                self.save_change($index);
            }
            self.currentIndex=$index;
            self.readMsg=true;            
        };
        
        this.mark_unread=function(){
            $scope.msgs[self.currentIndex].read=false;            
            self.save_change(self.currentIndex);
            self.back_to_msgs();
        };
        
        this.back_to_msgs=function(){
            self.readingMsg={};
            self.currentIndex=null;
            self.readMsg=false;            
        };
});
