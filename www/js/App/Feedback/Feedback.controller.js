App.controller('FeedbackCtrl', function($http,ngDialog,Spinner,Tpl){
        var self=this;
        
        this.formData={};
        
        this.open_form=function(){
            Tpl.setDefaults({template:'feedbackForm.html.twig'});
            var cb=function(data){
                ngDialog.open({
                    template: data,
                    plain: true
                });
            }
            Tpl.getTemplate(cb);
        };
        
        this.submit=function(){            
            Spinner.spin();
            $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/send-feedback/',
                    data: $.param(self.formData),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            window.console.log(data); 
                            Spinner.stop();
                            ngDialog.closeAll();
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                });   
        }
});
