Admin.controller('VideosCtrl', function($scope,$http){
        var self=this;
        
        $scope.itemData={
            'title':null,
            'video':null
        };
        
        $scope.get_videos=function(){
            $http({
                method  : 'GET',
                url     : 'http://'+document.domain+'/ajax/get-videos/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            }).success(function(data){
                $scope.items=data;
            });
        };
        
        $scope.set_videos=function(){
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/set-videos/',
                data    : $.param($scope.itemData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            }).success(function(){
                $scope.get_videos();
                $scope.itemData={
                    'title':null,
                    'video':null
                };
            });
        };
        
        $scope.edit_videos=function($index){
            var id=$scope.items[$index].id;
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/set-videos/'+id+'/',
                data    : $.param($scope.items[$index]),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            }).success(function(){
                $scope.get_videos();                
            });
        };
        
        $scope.delete_videos=function($index){
            var id=$scope.items[$index].id;
            $http({
                method  : 'GET',
                url     : 'http://'+document.domain+'/ajax/delete-videos/'+id+'/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            }).success(function(){
                $scope.get_videos();
            });
        };
        
        $scope.get_videos();
        
});
