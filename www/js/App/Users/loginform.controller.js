App.controller('LoginFormCtrl', function($http,$scope){
        var self=this;
        this.formData={'remember_me':true};
        this.loginFailed={'msg':'',flag:false};
        
               
        this.submit=function(){
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/login/',
                data    : $.param(self.formData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        window.console.log(data);
                        if(data!=="1"){
                            self.loginFailed.msg=data;
                            self.loginFailed.flag=true;
                        }            
                        else {
                            self.loginFailed.flag=false;
                            document.location.reload(true);
                        }
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        this.logout=function(){
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/logout/',
                data    : $.param(self.formData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        window.console.log(data);
                        document.location.reload(true);
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
});
