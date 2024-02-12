App.controller('AskSearchCtrl', function($http,$scope,Spinner){
        var self=this;
        $http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
        $scope.geoData={geometry:{location:{}}};
        this.anywhereFlag=false;
        
        /*
         * form data of the search form
         */
        this.formData={
            'location':{},
            'radius':''
        };
        

        

        
        /* 
         * GET save search and proceed 
         */
        this.submit=function(){
            Spinner.spin();            
            if(!self.anywhereFlag){
                prepare_formData();
//                window.console.log(self.formData);
                $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/ask/where/',
                    data    : $.param(self.formData),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            window.location='http://'+document.domain+'/ask/when/';
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
            }
            else {
                window.location='http://'+document.domain+'/ask/when/'
            }
        };
        
        /* 
         * prepare the form data before sending 
         */
        var prepare_formData=function(){
            // radius            
            self.formData.radius=parseFloat(self.formData.radius); 
            
            // geocoding
            if(typeof($scope.geoData.geometry.location.lng)==='function'){
                self.formData.location.lng=$scope.geoData.geometry.location.lng();
            }
            else {
                self.formData.location.lng=$scope.geoData.geometry.location.lng;
            }
            if(typeof($scope.geoData.geometry.location.lat)==='function'){
                self.formData.location.lat=$scope.geoData.geometry.location.lat();
            }
            else {
                self.formData.location.lat=$scope.geoData.geometry.location.lat;
            }
            self.formData.location.place_id=$scope.geoData.place_id;
            self.formData.location.formatted_address=$scope.geoData.formatted_address;  

        };
        
        this.clear_loc=function(){            
            $('input[name="place"]').val('');
        };
        
        this.switch_anywhere=function(){
            self.anywhereFlag=!self.anywhereFlag;
            if(self.anywhereFlag){
                $('input[name="place"]').attr('disabled','disabled');
                $('input[name="radius"]').attr('disabled','disabled');
            }
            else {
                $('input[name="place"]').removeAttr('disabled');
                $('input[name="radius"]').removeAttr('disabled');
            }
        };
        
});
