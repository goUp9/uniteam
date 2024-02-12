App.directive("geoHelper", function() {
    var runFn;
    runFn=function(scope,element,attrs){
        $(element).geocomplete().bind("geocode:result", function(event, result){
            scope.location=result;
            scope.$apply();
        });
    };    
    return {
        restrict: 'A',
        scope: {            
            "location":"=location"
        },
        link:runFn
    };
});
