App.controller('EditWhereCtrl', function($http, Spinner, $scope, $rootScope,scrollTo, Get){
        var self=this;
                
        $http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
        $scope.geoData={};
        $scope.geoData={geometry:{location:{}}};
        
        Get.set();
        this.type=Get.bag.type;
        
        /*
         * form data of the search form
         */
        this.formData={
            'location':{},
            'radius':0,
            'query_where_id':Get.bag.query_where_id,
            'query_id':Get.bag.query_id
        };
        
        /* 
         * GET save search and proceed 
         */
        this.submit=function(){            
            if(!self.anywhereFlag){
                prepare_formData();
                if(typeof(self.formData.location.lat)!=='undefined'){
                    Spinner.spin();
                    $http({
                       method  : 'POST',
                       url     : 'http://'+document.domain+'/ajax/edit-where/',
                       data    : $.param(self.formData),
                       headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                   })
                       .success(function(data) {

                               window.location='http://'+document.domain+'/myuin/'+self.type+'/';
//                               window.console.log(data); 
                       }).error(function(data, status, headers, config) {
                           window.console.log(status);
                   }); 
               }
            }
        };
        
        /* 
         * prepare the form data before sending 
         */
        var prepare_formData=function(){
            // radius
            self.formData.radius=parseFloat(self.formData.radius); 
            
            // geocoding
            if(!jQuery.isEmptyObject($scope.geoData)){
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
            }
            else {
                self.formData.location.place_id=null;
            }


        };
        
        
});
