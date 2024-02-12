App.controller('EditWhatCtrl', function($http, Spinner, $scope, $rootScope,scrollTo){
        var self=this;
        
        this.formData={};
        
        this.save=function(){ 
            Spinner.spin();
                self.formData.tags=$('#whatquery_value').val();
                window.console.log(self.formData);
                $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/edit-what/',
                    data: $.param(self.formData),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            window.console.log(data); 
                            Spinner.stop();
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                });            
        };
        
        
});
