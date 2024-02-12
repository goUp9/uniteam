App.directive("minutesSelect", function() {
     var runFn;
     
    function make_minutes(){
        var mins=[];
        for(var i=0; i<60;i++){
            mins.push({'min':i});
        }
        return mins;
    }
     
    runFn=function(scope,element,attrs){  
        scope.minutes=make_minutes();
    };    
    return {
        restrict: 'E',
        link:runFn,
        templateUrl: 'http://'+document.domain+'/templates/website/widgets/minutesSelect.html',
        scope:{
            'selected':'=selected'
        }
    };
});
