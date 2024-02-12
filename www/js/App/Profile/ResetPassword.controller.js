App.controller('ResetPasswordCtrl', function($http,$scope){
        var self=this;
         var self=this;
        this.formData={};        
        $scope.usr_msg={show:false,error:false, msg:''};
        
               
        this.submit=function(){
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/reset-password/',
                data    : $.param(self.formData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                    $scope.usr_msg.msg=data.msg;
                    $scope.usr_msg.show=true;
//                    window.console.log(typeof(data.status));
//                    window.console.log(data.status===false);
                    if(data.status===false){
                        $scope.usr_msg.error=true;
                    }
                    else {                        
                        $scope.usr_msg.error=false;                        
                    }                    
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
});
