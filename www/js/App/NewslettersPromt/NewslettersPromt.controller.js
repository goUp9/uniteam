App.controller('NewslettersPromtCtrl', function($scope,$http){
        var self=this;
        
        $scope.showWindowFlag=true;
        
        $scope.submit=function(answer){
                $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/newsletter-prompted/',
                    data    : $.param({'answer':answer}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                }).success(function(){
                    $scope.showWindowFlag=false;
                });
        };
        
});
