App.controller('VideoCtrl', function($rootScope){
        var self=this;
        $rootScope.showFlag=false;
        
        this.show_hide_video=function(){            
            $rootScope.showFlag=!$rootScope.showFlag; 
        };
});
