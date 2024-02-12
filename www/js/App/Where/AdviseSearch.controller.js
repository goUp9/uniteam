App.controller('AdviseSearchCtrl', function($http,$scope){
        var self=this;
        $http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
        $scope.geoData={};
        $scope.locs=[];
        this.anywhereFlag=false;
        this.locsExistFlag=false;
        $scope.geoData={geometry:{location:{}}};
        /*
         * form data of the search form
         */
        this.formData={
            'location':{},
            'radius':''
        };
        
        this.continueFlag=false; // gray out the continue button
        
        /* 
         * GET save search and proceed 
         */
        this.submit=function(){
            if(!self.anywhereFlag){
                prepare_formData();
                 $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/advise/where/',
                    data    : $.param(self.formData),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
//                            window.console.log(data); 
                            self.locs_exist();
                            self.get_existing_locations();
//                            window.location='http://'+document.domain+'/advise/when/';
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
            }
        };
        
        this.next_step=function(){
            if(self.locsExistFlag || self.anywhereFlag){
                window.location='http://'+document.domain+'/advise/when/';
            }
        };
        
        this.get_existing_locations=function(){            
            $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/supply/get-existing-locations/',
                    data    : $.param(self.formData),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            window.console.log(data[0].wheres);
                            $scope.locs=data[0].wheres;
                            self.locs_exist();    
                            self.set_continueFlag();
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        }
        self.get_existing_locations();
        
        
        this.remove_loc=function($index){
            var id=$scope.locs[$index].id;           
            $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/supply/remove-location/',
                    data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            window.console.log(data);
                            self.locs_exist();
                            self.get_existing_locations();
                            self.set_continueFlag();
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        };
        
        this.locs_exist=function(){            
            if($scope.locs.length>0){
                self.locsExistFlag=true;
            }
            else {
                self.locsExistFlag=false;
            }
        }
        
        this.clear_loc=function(){            
            $('input[name="place"]').val('');
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
        
        this.set_continueFlag=function(){            
            if(self.anywhereFlag||self.locsExistFlag){
                self.continueFlag=true;
            }
            else {
                self.continueFlag=false;
            }
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
            self.set_continueFlag();
        };
        
});
