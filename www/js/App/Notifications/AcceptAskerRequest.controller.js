App.controller('AcceptAskerRequestCtrl', function($http){
        var self=this;
        
        this.find_matches=function(id){  
            Spinner.spin();
            window.console.log('click');
//            $http({
//                    method  : 'GET',
//                    url     : 'http://'+document.domain+'/ajax/accept-asker-request/'+id+'/',
//                    //data    : $.param({'id':id}),
//                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
//                })
//                    .success(function(data) {
//                            window.console.log(data);
//                            self.get_asks();
//                    }).error(function(data, status, headers, config) {
//                        window.console.log(status);
//                }); 
        };
});
