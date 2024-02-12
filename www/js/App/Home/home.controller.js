App.controller('HomeCtrl', function($http){
        var self=this;
        
        this.showAbout=true;
        
        this.toggleAbout=function(){
            if($(window).width()>800){
                self.showAbout=!self.showAbout;
                if(self.showAbout){
                    $('#search-tool').css('width','55%');
                }
                else {
                    $('#search-tool').css('width','100%');
                }
            }
        };
        
});
