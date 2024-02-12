App.controller('WhatCtrl', function($http, Spinner, $scope, $rootScope,scrollTo){
        var self=this;
        
        this.prepare_data=function(){            
            if(typeof($scope.whatquery)==='undefined'||$scope.whatquery===null){                
                return false;
            }
            else {
                return true;
            }
        };
        
        this.ask=function(){
            window.console.log($scope.whatquery);
            if(self.prepare_data()){
                Spinner.spin();
                    $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/confirm-what/?type=ask',
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
//                                window.console.log(data);
                                if(data!=='blocked'){
                                    window.location='http://'+document.domain+'/ask/where/';
                                }
                                else {
                                      window.location='http://'+document.domain+'/feedback-blocked/';
                                }
                                
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
            }
            else {
                self.focus_on_search();
            }
        };
        
        this.supply=function(){ 
            if(self.prepare_data()){
                Spinner.spin();
                    $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/confirm-what/?type=supply',
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
                                    window.location='http://'+document.domain+'/supply/where/';
//                                window.console.log(data); 
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    }); 
            }
            else {
                self.focus_on_search();
            }
        };
        
        this.advice=function(){
            if(self.prepare_data()){
                Spinner.spin();
                    $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/confirm-what/?type=advice',
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
                            window.location='http://'+document.domain+'/advise/where/';
    //                            window.console.log(data); 
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    }); 
            }
            else {
                self.focus_on_search();
                $('#adviser-box .button').css('border-color','green');
                $('#adviser-box .button').css('background-color','#ebffea');
                $('#adviser-box .button p').css('color','green');
            }
        };
        
        this.focus_on_search=function(){
            $('#whatquery_value').focus();
            $('#whatquery_value').css('border-width','3px');
            scrollTo.scroll_to_el('#whatquery_value',100);
            setTimeout(function(){
                $('#whatquery_value').css('border-width','1px');
            },
                100
            )
        };
        
});
