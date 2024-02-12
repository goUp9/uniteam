App.directive("LoginPopUp", function() {
     var runFn;
    runFn=function(scope,element,attrs){
        window.console.log('m1');
//        if(scope.showLoginPanel===true){
//            window.console.log('m2');
//            $(document).mouseup(function (e){
//                window.console.log('m3');
//                var container = $("login-popup");
//
//                if (!container.is(e.target) // if the target of the click isn't the container...
//                    && container.has(e.target).length === 0) // ... nor a descendant of the container
//                {
//                    scope.showLoginPanel=false;
//                    scope.$apply();
//                }
//            });
//        }
    };    
    return {
        restrict: 'A',
        link:runFn
    };
});
