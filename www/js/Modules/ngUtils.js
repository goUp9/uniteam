(function (window, angular, undefined) {
    var Utils = angular.module('ngUtils', []);    

    Utils.service('Tpl',function($http){        
        var self=this;        
        this.defaults={
            url:'http://'+document.domain+'/cwd-system-ajax-gettemplate/',
            template:''
        };
        this.setDefaults = function (newDefaults) {
			angular.extend(self.defaults, newDefaults);
	};
        this.getTemplate=function(callback){
            $http({
                method  : 'POST',
                url     : self.defaults.url,
                data    : $.param({template:self.defaults.template}),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).success(function(data){                
                callback(data);
            });
        };        
    });
    
    Utils.service('Spinner',function(){        
        var self=this;        
        
        this.spin=function(){
                var windowHeight=$(window).height();
                if($("#spinner-overlay").length===0){
                    $('body').prepend('<div id="spinner-overlay"><div id="spinner"></div></div>');
                    $('#spinner').html('<div class="throbber-loader">Loading…</div>');
                    $("#spinner-overlay").css('height',windowHeight);
                    $("#spinner-overlay").css('width','100%');
                    $("#spinner-overlay").css('z-index','999');
                    $("#spinner-overlay").css('position','absolute');
                    $("#spinner-overlay").css('background-color','rgba(255,255,255,0.7)'); 
                    $("#spinner").css('position','absolute');
                    $("#spinner").css('left','50%');
                    $("#spinner").css('top','50%');
                }
        };  
        
        this.stop=function(){
            $("#spinner-overlay").remove();
        };
    });
    
    Utils.service('scrollTo',function(){        
        var self=this;        
        
        this.scroll_to_el=function(el,speed){            
            var speed=speed||'slow';
            var y=$(el).position().top;      
            $('html, body').animate({scrollTop:y}, speed);
        };        
    });
    
    Utils.directive('scrollTo',function(){
        var runFn;     
        runFn=function(scope,element,attr){            
            $(element).on('click',function(el){                 
                var speed=scope.scrollSpeed.replace(/['"]+/g, '')|'slow';
                var scrollEl=scope.scrollTo.replace(/['"]+/g, ''); 
                var y=$(scrollEl).position().top;      
                $('html, body').animate({scrollTop:y}, speed);
            });
        };
        return {
            restrict: 'A',
            link:runFn,
            scope:{
                'scrollTo':'@',
                'scrollSpeed':'@scrollSpeed'
            }
        };
    });
    
    Utils.service('changeAddressBar',function(){        
        var self=this;        
        
        this.change=function(param,title) {
            param=param||null; 
//            title=title||'title';            
            window.history.pushState("", title, '#page-'+param);
            $('title').html(title);
        }
    });
    
    Utils.service('Get',function(){        
        var self=this; 
        this.bag={};
        
        this.set=function() {
            var parts = window.location.search.substr(1).split("&");
            for (var i = 0; i < parts.length; i++) {
                var temp = parts[i].split("=");
                self.bag[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
            }
        }
    });
    
    Utils.service('LocationFinder',function(){        
        var self=this;        
        
        this.getLocation=function(){
            var loc=window.location.pathname;        
            var locArray=loc.split('/'); 
            locArray.shift();
            locArray.pop();
            return locArray;
        };        
    });
    
    
        
    Utils.directive('pHolder',function(){
        var pholderFn;     
        pholderFn=function(scope,element,attr){
            var focus,blur;
            var default_val=$(element.context).attr('placeholder');    
            var default_color=$(element.context).css('color');

            focus=function(){
                if($(this).val()==='' || $(this).val()===default_val){
                    $(this).css('color','black');
                    $(this).attr('placeholder','');
                    $(this).val('');
                }
            };

            blur=function(element){        
                if($(this).val()==='' || $(this).val()===default_val){
                    $(this).css('color',default_color); 
                    $(this).val(default_val);
                }
            };

            $(element).on('focus',focus);
            $(element).on('blur',blur);
        };
        return {
            restrict: 'A',
            link:pholderFn
        };
    });
    
    Utils.directive('clickable', function() {
        var loadFn;
        loadFn=function(scope,element,attrs){

                    var run=function(){
                        $(element).css('cursor','pointer');
                            $(element).on('click', function(){
                                var linked="";
                                if (typeof $(this).attr('data-url')==="undefined"){
                                    linked=$(this).find('a').attr('href');
                                }
                                else{
                                    linked=$(this).attr('data-url');
                                }
                                window.location.href = linked;
                            });  
                    }

            run();      
        };    
        return {
            restrict: 'A',
            link:loadFn
        };
    });
    
    Utils.factory('$remember', function() {
        return function(name, values) {
            var cookie = name + '=';

            cookie += values + ';';

            var date = new Date();
            date.setDate(date.getDate() + 365);

            cookie += 'expires=' + date.toString() + ';';

            document.cookie = cookie;
        };
    });
    
    Utils.factory('$forget', function() {
        return function(name) {
            var cookie = name + '=;';
            cookie += 'expires=' + (new Date()).toString() + ';';

            document.cookie = cookie;
        }
    });
    
    Utils.directive('focusHere', function () {
        return {
            link: function(scope, element, attrs) {
                scope.$watch(attrs.focusHere, function(value) {
                    if(value === true) {
                        element[0].focus();
                        element[0].select();
                    }
                });
            }
        };
    });
    
    


    
})(window, window.angular);



