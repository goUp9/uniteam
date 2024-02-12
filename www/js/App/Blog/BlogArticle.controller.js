App.controller('BlogArticleCtrl', function($http,$scope){
        var self=this;
        
        $scope.newComment={
            'text':''
        };
        
        $scope.get_comments=function(idArticle){
                $http
                    ({ 
                        method  : 'GET',
                        url     : 'http://'+document.domain+'/blog-get-comments/'+idArticle+'/',
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    }).success(function(data){                        
                        $scope.comments=data;
                    });                    
        };

         $scope.add_comment=function(idArticle){
                $scope.newComment.idArticle=idArticle;
                $http
                    ({ 
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/blog-add-comment/',
                        data    : $.param($scope.newComment),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    }).success(function(data){
                        $scope.newComment={
                            'text':''
                        };
                        $scope.get_comments(idArticle);
                    });                    
        };
        
});
