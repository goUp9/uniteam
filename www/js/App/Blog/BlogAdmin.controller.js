Admin.controller('BlogAdminCtrl', function($http, $scope){
        var self=this;
        
        $scope.page=1;
        $scope.currentItemId=null;
        
        $scope.comments={};
        
        $scope.switch_page=function(page){
            $scope.page=page;
            $scope.get_items();
        };
        
        $scope.currentItem={
            'title':'',
            'text':'',
            'keywordsString':''
        };
        
        $scope.chose_to_edit=function($index){
            $scope.currentItem={
                'title':$scope.items[$index].title,
                'text':$scope.items[$index].text,
                'keywordsString':$scope.items[$index].keywordsString
            };
            $scope.currentItemId=$scope.items[$index].id;
        };
        
        $scope.clear_item=function(){
            $scope.currentItem={
                            'title':'',
                            'text':'',
                            'keywordsString':''
                        };
        };
        
        $scope.add_item=function(id){
            id=id||null;
            var url='http://'+document.domain+'/admin/manage-blog-article/';
            if(id!==null){
                url=url+id+'/';
            }
            if($scope.currentItem.title!=='',$scope.currentItem.text!=='',$scope.currentItem.keywordsString!==''){
                $http
                    ({ 
                        method  : 'POST',
                        url     : url,
                        data    : $.param($scope.currentItem),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    }).success(function(){
                        $scope.currentItem={
                            'title':'',
                            'text':'',
                            'keywordsString':''
                        };
                         $scope.get_items();
                    });                    
            }           
        };
        
        $scope.delete_item=function($index){
            var id=$scope.items[$index].id;
            var url='http://'+document.domain+'/admin/delete-blog-article/'; 
            $http
                ({ 
                    method  : 'POST',
                    url     : url,
                    data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                }).success(function(){                        
                     $scope.get_items();
                });                    
                      
        };
        
        $scope.get_comments=function(idArticle){
                if(typeof(idArticle)!=='undefined'){
                    $scope.idArticle=idArticle;
                }
                idArticle=idArticle||$scope.idArticle;                
                $http
                    ({ 
                        method  : 'GET',
                        url     : 'http://'+document.domain+'/admin/get-blog-comments/'+idArticle+'/',
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    }).success(function(data){
                        $scope.comments=data;
                    });                    
        };
        
        $scope.clear_comments=function(){
            $scope.idArticle=null;
            $scope.comments={};
        }
        
        $scope.delete_comment=function($index){
            var id=$scope.comments[$index].id;
            var url='http://'+document.domain+'/admin/delete-blog-comment/';
            $http
                ({ 
                    method  : 'POST',
                    url     : url,
                    data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                }).success(function(){                        
                     $scope.get_comments();
                });                    
                      
        };
        
        $scope.get_items=function(){
                $http
                    ({ 
                        method  : 'GET',
                        url     : 'http://'+document.domain+'/admin/get-blog-articles/'+$scope.page+'/',
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
});

Admin.directive('ckEditor', function () {
    return {
        require: '?ngModel',
        link: function (scope, elm, attr, ngModel) {
            var ck = CKEDITOR.replace(elm[0]);
            if (!ngModel) return;
            ck.on('instanceReady', function () {
                ck.setData(ngModel.$viewValue);
            });
            function updateModel() {
                scope.$apply(function () {
                ngModel.$setViewValue(ck.getData());
            });
        }
        ck.on('change', updateModel);
        ck.on('key', updateModel);
        ck.on('dataReady', updateModel);

        ngModel.$render = function (value) {
            ck.setData(ngModel.$viewValue);
        };
    }
};
});