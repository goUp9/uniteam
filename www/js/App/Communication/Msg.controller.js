App.controller('MsgCtrl', function($http,$scope,Spinner){
        var self=this;
        this.msgData={};
        this.errorMsg="";
        $scope.errorShow=false;
        
        this.validate_msg=function(){
            if(self.msgData.msg!==undefined && self.msgData.msg!==""){
                return true;
            }
            else {
                this.errorMsg="Message can't be empty.";
                 $scope.errorShow=true;
                return false;                
            }
        };
        
        this.validate_recipient=function(){           
            if($scope.recipient!==undefined && $scope.recipient!==""){
                return true;
            }
            else {
                this.errorMsg="Please chose a valid recipient of your message";
                $scope.errorShow=true;
                return false;
            }
        };
        
        this.validate=function(){
            if(self.validate_msg()){
                if(self.validate_recipient()){
                    this.errorMsg="";
                    $scope.errorShow=false;
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        };
        
        this.submit=function(){ 
            Spinner.spin();
            if(self.validate()){
                self.msgData.recipient=$scope.recipient.title;
                $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/send-pm/',
                    data    : $.param(self.msgData),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            window.console.log(data); 
                            
                            window.location='http://'+document.domain+'/myuin/msgs/';
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                });
            }
            else {
                Spinner.stop();
            }
        };
});
