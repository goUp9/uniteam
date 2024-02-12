Admin.controller('UsersCtrl', function($http,$scope,Spinner){
        var self=this;
        this.searchCheckboxes=[
            {'name':'username','id':'username'},
            {'name':'email','id':'email'},
            {'name':'first name','id':'fName'},
            {'name':'last name','id':'lName'},
            {'name':'phone','id':'phone'},
            {'name':'mobile','id':'mobile'},
            {'name':'city','id':'city'},
            {'name':'state','id':'state'},
            {'name':'country','id':'country'},
            {'name':'zip','id':'zip'},
            {'name':'date (format: 2016-02-26)','id':'dateCreated'}
        ];
        this.postData={
            'page':1,
            'searchVal':'',
            fields:{
                'username':true,
                'email':true,
                'fName':true,
                'lName':true,
                'phone':true,
                'mobile':true,
                'city':true,
                'state':true,
                'country':true,
                'zip':true,
                'dateCreated':true
            }
        }; 
        this.editMode=false;
        this.editItem={};
        this.editIndex=0; // store the index of the item in editing (since it's not the same as ID in DB)
        this.filterFirstCallFlag=true;
        this.lastRequestType='';
        this.queryType='';
        this.bulkPageFlag=false;
        
        this.get_users=function() {
            Spinner.spin();
            self.postData.searchVal='';
            self.queryType='';
            if(self.lastRequestType!=='all'){
                   self.postData.page=1;         
            }
            self.lastRequestType='all';
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/get-users/',
                data    : $.param(self.postData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
//                    window.console.log(data);
                        $scope.users=data.data;
                        $scope.pages=self.set_pages(data.totalPages);

                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        this.get_users();
        
        this.search_users=function() {
            Spinner.spin();
            self.queryType='';
            if(self.lastRequestType!=='search'){
                   self.postData.page=1;         
            }
            self.lastRequestType='search';
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/search-users/',
                data    : $.param(self.postData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                          window.console.log(data);
                        $scope.users=data.data.users;
                        $scope.pages=self.set_pages(data.data.pages);

                        Spinner.stop();
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        this.filter_users=function(type) {
            Spinner.spin(); 
            self.postData.searchVal='';
            if(self.lastRequestType!=='filter'){
                   self.postData.page=1;         
            }
            self.lastRequestType='filter';

            self.queryType=type;
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/filter-users/'+type+'/',
                data    : $.param(self.postData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        window.console.log(data.data);
                        $scope.users=data.data.users;                        
                        self.filterFirstCallFlag=false;
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
                self.get_users();
            }
            else if(self.postData.searchVal!=''){ 
                self.search_users();
            }
            else if(self.postData.searchVal!=''){
                self.filter_users(self.queryType);
            }
        };
        
        this.save_change=function($index){
            Spinner.spin();
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/save-user-change/',
                data    : $.param($scope.users[$index]),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                    Spinner.stop();
//                    window.console.log(data);                        
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        this.edit_mode=function($index){
            self.editItem=$scope.users[$index];
            self.editMode=true;
            self.editIndex=$index;
        };
        
        this.exit_edit_mode=function(){
            self.editItem={};
            self.editMode=false;
            self.editIndex=0;
        };
        
        this.edit=function(){
            $scope.users[self.editIndex]=self.editItem;
            self.save_change(self.editIndex);
            self.editIndex=0;
            self.editMode=false;
        };
        
        this.all_active=function(){
            for(var i=0;i<$scope.users.length;i++){                
                if($scope.users[i].bulk===true){                    
                    $scope.users[i].status=true;
                    self.save_change(i);
                }
            }            
        };
        
        this.all_disabled=function(){
            for(var i=0;i<$scope.users.length;i++){                
                if($scope.users[i].bulk===true){                    
                    $scope.users[i].status=false;
                    self.save_change(i);
                }
            }            
        };
        
        this.bulk_page=function(){
            self.bulkPageFlag=!self.bulkPageFlag;
            if(self.bulkPageFlag){
                for(var i=0;i<$scope.users.length;i++){                
                    $scope.users[i].bulk=true;          
                } 
            }
            else{
                for(var i=0;i<$scope.users.length;i++){                
                    $scope.users[i].bulk=false;                
                } 
            }
        };
        
});
