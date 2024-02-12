App.controller('SupplyFinalCtrl', function($http,Get,Spinner){
        var self=this;
        
        Get.set();
        
        this.experience=0;
        this.prev_experience=this.experience;
        this.qualification=0;
        
        this.validate_exp=function(){           
            if(self.experience<0){
                self.experience=self.prev_experience;
            }
            var patt = new RegExp(/[0-9]+/);
            var is_number = patt.test(self.experience);
            if(!is_number&&self.experience!==null){
                self.experience=self.prev_experience;
            }
            self.prev_experience=self.experience;
        };
        
        this.submit=function(){
            if(self.experience!==null){ 
                Spinner.spin();
                var postData={'experience':self.experience,'qualification':self.qualification};
                if(typeof(Get.bag['query_id'])!=='undefined'){
                    postData.query_id=Get.bag['query_id'];
                }
//                window.console.log(postData);
                 $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/supply/final/',
                        data    : $.param(postData),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
//                                window.console.log(data); 
                                window.location='http://'+document.domain+'/ajax/finalise/?type=supply';
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
            }
        };
        
});
