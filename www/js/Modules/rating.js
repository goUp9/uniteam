App.directive("rateItem", function() {
     var runFn;
    runFn=function(scope,element,attrs){  
        var i=0;
        scope.rating=[];
        while(scope.ratingSteps.length>i){
            i++;
            scope.rating.push({'value':i});
        };
    };    
    return {
        restrict: 'E',
        link:runFn,
        templateUrl:'http://'+document.domain+'templates/website/widgets/rating.html',
        scope:{
            'ratingSteps':'@ratingSteps'
        }
    };
});
