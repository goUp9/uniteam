App.controller('PersonalCtrl', function($http,Spinner,$scope){
        var self=this;
        this.formData={            
        };
        
        $scope.validationMsg='';
        $scope.showError=false;
        $scope.success={flag:false,msg:''};
        
        var validate_mobile_number=function(){
            if(self.formData.mobile===undefined){
                return false;
            }
            else {
                return true;
            }
        };
        
        var validate_country=function(){
            if(self.formData.country===undefined){
                return false;
            }
            else {
                return true;
            }
        };
        
        var validate=function(){
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
                Spinner.spin();
                if(validate()){
                    $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/edit-personal/',
                        data    : $.param(self.formData),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
                                window.console.log(data); 
                                Spinner.stop();
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
                }
                else {
                    Spinner.stop();
                }
        };
});
