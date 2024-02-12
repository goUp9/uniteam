App.controller('RequestPasswordCtrl', function($http,$scope){
        var self=this;
        this.formData={};
        this.msg={'msg':'',errorFlag:false};
        $scope.usr_msg=false;
        
               
        this.submit=function(){
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/new-password-request/',
                data    : $.param(self.formData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                    if(data==="0"){
                        self.msg.msg="This email address isn't registred with us.";
                        self.msg.errorFlag=true;
                        $scope.usr_msg=true;
                    }
                    else {
                        self.msg.msg="The email has been send to your email address. Please follow the instructions in it to restore your password";
                        self.msg.errorFlag=false;
                        $scope.usr_msg=true;
                    }
                    window.console.log(data);
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
});
