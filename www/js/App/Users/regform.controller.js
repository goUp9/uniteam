App.controller('RegFormCtrl', function($http,$scope,Spinner){
        var self=this;
        $scope.validationMsg='';
        $scope.showError=false;
        $scope.success={flag:false,msg:''};
            
        this.formData={ 
            'newsletterSubscribed':true
        };
        
        var validate_passwordsMatch=function(){            
            if(self.formData.password===self.formData.password2){
                return true;
            }
            else {
                return false;
            }
        };
        
//        var validate_mobile_number=function(){
//            if(self.formData.mobile===undefined){
//                return false;
//            }
//            else {
//                return true;
//            }
//        };
        
        var validate_country=function(){
            if(self.formData.country===undefined){
                return false;
            }
            else {
                return true;
            }
        };
        
        var validate=function(){             
            if(!validate_passwordsMatch()){
                $scope.validationMsg='Passwords do not Match';
                $scope.showError=true;
                return false;
            }
//            if(!validate_mobile_number()){
//                $scope.validationMsg='Please provide a valid mobile phone number';
//                $scope.showError=true;
//                return false;
//            }
//             if(!validate_country()){
//                $scope.validationMsg='Please chose a valid country name';
//                $scope.showError=true;
//                return false;
//            }
            $scope.showError=false;            
            return true;            
        };        
        
        this.submit=function(){ 
            window.console.log(self.formData);
            Spinner.spin();
            if(validate()){
                $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/register/',
                    data    : $.param(self.formData),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {                            
                            if(data.status){
                                $scope.success.flag=true;
                                $scope.success.msg=data.msg;
                            }
                            else {
                                 $scope.validationMsg=data.msg;
                                 $scope.showError=true;
                            }
                            Spinner.stop();                        
                    }).error(function(data, status, headers, config) {
//                        window.console.log(status);
                });
            }
            else {
                Spinner.stop();
//                window.console.log(self.validationMsg);  
            }
        };
});