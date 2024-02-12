App.controller('socialNetworksCtrl', function($scope,$http){
        var self=this;
        
        $scope.socialNetworkFlag=true;;
        
        window.on_sign_in=function(googleUser) {
            var profile = googleUser.getBasicProfile();
            var id_token = googleUser.getAuthResponse().id_token;
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/google-plus-auth/',
                data    : $.param({'id_token':id_token}),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        window.location.href='http://'+document.domain;
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
//            window.console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
//            window.console.log('Name: ' + profile.getName());
//            window.console.log('Image URL: ' + profile.getImageUrl());
//            window.console.log('Email: ' + profile.getEmail());
        };
        
        $scope.twitter_sign_in=function(){
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/ajax/twitter-auth/',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
            })
                .success(function(data) {
                        window.location=data;
                }).error(function(data, status, headers, config) {
                    window.console.log(status);
            });
        };
        
        /*FACEBOOK*/
        
        window.fbAsyncInit = function() {
              FB.init({
              appId      : '1633765200269831',
              cookie     : true,  // enable cookies to allow the server to access 
                                  // the session
              xfbml      : true,  // parse social plugins on this page
              version    : 'v2.2' // use version 2.2
            });
        };
        
        function handle_login(response){
            $http   // save the query to the DB
                ({ 
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/login-w-fb/',
                    data    : $.param(response.authResponse),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            if(data==0){
                                register();
                            }  
                            else{
                                window.location.href='http://'+document.domain; 
                            }
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        }
        
        function register(){
            FB.api('/me',{fields:'first_name, last_name, email'},function(response) {
                window.console.log(JSON.stringify(response));
                $http   // save the query to the DB
                ({ 
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/register-w-fb/',
                    data    : $.param(response),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            window.console.log(data);  
                            window.location.href='http://'+document.domain; 
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
            });
        }
        
        
        $scope.facebook_login=function(){
            window.console.log('m1');
            FB.login(function(response){
                window.console.log(response);
                if(response.status=='connected'){
                    handle_login(response);
                }
            }, {scope: 'public_profile,email'});
        }; 

});
