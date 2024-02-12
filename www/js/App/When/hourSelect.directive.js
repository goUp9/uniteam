App.directive("hourSelect", function() {
    var runFn;
    
    function make_hours(){
        var hours=[];
        for(var i=0; i<24;i++){
            hours.push({'hour':i});
        }
        return hours;
    }
    
    runFn=function(scope,element,attrs){  
        scope.hours=make_hours();
    };    
    return {
        restrict: 'E',
        link:runFn,
        templateUrl: 'http://'+document.domain+'/templates/website/widgets/hourSelect.html',
        scope:{
            'selected':'=selected'
        }
    };
});
