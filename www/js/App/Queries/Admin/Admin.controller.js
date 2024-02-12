Admin.controller('QueriesCtrl', function($http, Spinner, $scope){
        var self=this;
        
        this.searchCheckboxes=[
            {'name':'username','id':'username'},
            {'name':'query id','id':'id'},
            {'name':'status','id':'status'},
            {'name':'type','id':'type'},
            {'name':'date (format: 2016-02-26)','id':'dateCreated'}
        ];
        
        this.feedbacksHideDeleted=[];
        
        this.postData={
            'page':1,
            'searchVal':'',
            fields:{
                'username':true,
                'id':true,
                'status':true,
                'type':true,
                'dateCreated':true
            }
        }; 
        
        this.lastRequestType='';
        this.queryType='';
        
        this.get_queries=function() {
            Spinner.spin();
            
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/get-queries/'+self.postData.page+'/',
//                data    : $.param(self.postData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) { 
//                        window.console.log($scope.queries);
                        $scope.queries=data.data;                        
                        $scope.pages=self.set_pages(data.totalPages);
                        
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        this.get_queries();
        
        this.search_queries=function() {
            Spinner.spin();
            self.queryType='';
            if(self.lastRequestType!=='search'){
                   self.postData.page=1;         
            }
            self.lastRequestType='search';
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/search-queries/',
                data    : $.param(self.postData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
//                          window.console.log(data);
                        $scope.queries=data.data.queries;
                        $scope.pages=self.set_pages(data.data.pages);

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
            if(self.postData.searchVal==''&&self.queryType==''){
                self.get_queries();
            }
            else if(self.postData.searchVal!=''){ 
                self.search_queries();
            }
            else if(self.postData.searchVal!=''){
                self.filter_queries(self.queryType);
            }
        };
        
        this.change_feedback=function(id){
            var feedbackData={val:self.feedbacks[id],id:id};
//            window.console.log(feedbackData);
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/edit-feedback/',
                data    : $.param(feedbackData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        this.delete_feedback=function(id){
            var feedbackData={id:id};
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/delete-feedback/',
                data    : $.param(feedbackData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        self.feedbacksHideDeleted[id]=true;
                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
});
