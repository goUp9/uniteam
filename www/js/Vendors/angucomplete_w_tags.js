/**
 * Angucomplete
 * Autocomplete directive for AngularJS
 * By Daryl Rowland
 */

angular.module('angucomplete', [] )
    .directive('angucomplete', function ($parse, $http, $sce, $timeout) {
    return {
        restrict: 'EA',
        scope: {
            "id": "@id",
            "placeholder": "@placeholder",
            "selectedObject": "=selectedobject",
            "initialvalue": "@initialvalue",
            "initialids": "@initialids",
            "url": "@url",
            "dataField": "@datafield",
            "titleField": "@titlefield",
            "descriptionField": "@descriptionfield",
            "imageField": "@imagefield",
            "imageUri": "@imageuri",
            "inputClass": "@inputclass",
            "userPause": "@pause",
            "localData": "=localdata",
            "searchFields": "@searchfields",
            "minLengthUser": "@minlength",
            "matchClass": "@matchclass"
        },
        template: '<div class="angucomplete-holder"><div ng-show="true" class="search-field-holder"><input ng-blur="search_show()" focus-here="searchActiveFlag" autocomplete="off" id="{{id}}_value" ng-model="searchStr" type="text" placeholder="{{placeholder}}" class="{{inputClass}}" ng-focus="resetHideResults()" ng-blur="hideResults()" /><div id="{{id}}_dropdown" class="angucomplete-dropdown" ng-if="showDropdown"><div class="angucomplete-searching" ng-show="searching">Searching...</div><div class="angucomplete-searching" ng-show="!searching && (!results || results.length == 0)"><p ng-show="tagAddedFlag" style="color:green">Tag has been added</p><p>Tag doesn\'t exist.<br/><span ng-show="logged" ng-click="add_tag()">Add a new tag</span><a ng-hide="logged" href="http://'+document.domain+'/registration/">Login or register to add tag.</a></p></div><div class="angucomplete-row" ng-repeat="result in results" ng-mousedown="selectResult(result)" ng-mouseover="hoverRow()" ng-class="{\'angucomplete-selected-row\': $index == currentIndex}"><div ng-if="imageField" class="angucomplete-image-holder"><img ng-if="result.image && result.image != \'\'" ng-src="{{result.image}}" class="angucomplete-image"/><div ng-if="!result.image && result.image != \'\'" class="angucomplete-image-default"></div></div><div class="angucomplete-title" ng-if="matchClass" ng-bind-html="result.title"></div><div class="angucomplete-title" ng-if="!matchClass">{{ result.title }}</div><div ng-if="result.description && result.description != \'\'" class="angucomplete-description">{{result.description}}</div></div></div></div><!--<div class="tags-holder" ng-hide="searchActiveFlag"><div class="tags-bar" ng-bind-html="$scope.tagsStr" ng-click="search_hide()"></div></div>--></div>',

        link: function($scope, elem, attrs) {
            this.is_logged=function(){
                $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/is-logged/',
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {                            
                            if(data==1){
                                $scope.logged=true;
                            }
                            else {
                                $scope.logged=false;
                            }
//                            window.console.log(data);
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                });                
            }
            this.is_logged();
            
            $scope.lastSearchTerm = null;
            $scope.currentIndex = null;
            $scope.justChanged = false;
            $scope.searchTimer = null;
            $scope.hideTimer = null;
            $scope.searching = false;
            $scope.pause = 500;
            $scope.minLength = 1;
            $scope.searchStr = null;
            $scope.searchActiveFlag=false;
            $scope.tagsStr = $scope.searchStr;
            $scope.tagAddedFlag=false;
//            $scope.idsArr=[];
            
            if ($scope.minLengthUser && $scope.minLengthUser != "") {
                $scope.minLength = $scope.minLengthUser;
                
            }

            if ($scope.userPause) {
                $scope.pause = $scope.userPause;
            }
            
            if($scope.initialvalue!==undefined && $scope.initialvalue.length!==0){
                $scope.searchStr=$scope.initialvalue;
                $scope.selectedObject={};
                $scope.selectedObject.title=$scope.initialvalue;
//                $scope.idsArr=$scope.initialids.split(',');                
            }            

//            $scope.search_hide=function(){
//                $scope.searchActiveFlag=true; 
//                setTimeout(function(){
//                    elem.find('input').focus();
//                    elem.find('input').select();
//                },50); 
//            }
            
            $scope.search_show=function(){
                $scope.searchActiveFlag=false; 
            }
            
            
            

            isNewSearchNeeded = function(newTerm, oldTerm) {                
                return newTerm.length >= $scope.minLength && newTerm != oldTerm
            }

            $scope.processResults = function(responseData, str) {
                if (responseData && responseData.length > 0) {
                    $scope.results = [];
                    
                    var titleFields = [];
                    if ($scope.titleField && $scope.titleField != "") {
                        titleFields = $scope.titleField.split(",");
                    }
                    
                    for (var i = 0; i < responseData.length; i++) {
                        // Get title variables
                        var titleCode = [];

                        for (var t = 0; t < titleFields.length; t++) {
                            titleCode.push(responseData[i][titleFields[t]]);
                        }

                        var description = "";
                        if ($scope.descriptionField) {
                            description = responseData[i][$scope.descriptionField];
                        }
                        
                        var imageUri = "";
                        if ($scope.imageUri) {
                            imageUri = $scope.imageUri;
                        }

                        var image = "";
                        if ($scope.imageField) {
                            image = imageUri + responseData[i][$scope.imageField];
                        }
                        
                        // this is the id of the object for further handling
                        var idData = "";
                        idData = responseData[i]['idData'];
                        

                        var text = titleCode.join(' ');
                        if ($scope.matchClass) {
                            var re = new RegExp(str, 'i');
                            var strPart = text.match(re)[0];
                            text = $sce.trustAsHtml(text.replace(re, '<span class="'+ $scope.matchClass +'">'+ strPart +'</span>'));
                        }

                        var resultRow = {
                            title: text,
                            description: description,
                            image: image,
                            originalObject: responseData[i],
                            id:idData
                        }

                        $scope.results[$scope.results.length] = resultRow;
                    }


                } else {
                    $scope.results = [];
                }
            }
            
            // added by Anastasia Sitnina for tags functionality
            function parse_search_str_tags(str){
                //str = str.replace(/\[.*\]/g, '');
                var prevTags= str.substr(0, str.lastIndexOf(','));
                var regExp = new RegExp(prevTags,"g");
                str = str.replace(regExp, '');                
                str=str.replace(/,/g,'','g');
                str=str.trim();                
                return str;
            };

            $scope.searchTimerComplete = function(str) {
                // Begin the search
                
                str=parse_search_str_tags(str);
               
                if (str.length >= $scope.minLength) {
                    if ($scope.localData) {
                        var searchFields = $scope.searchFields.split(",");

                        var matches = [];

                        for (var i = 0; i < $scope.localData.length; i++) {
                            var match = false;

                            for (var s = 0; s < searchFields.length; s++) {
                                match = match || (typeof $scope.localData[i][searchFields[s]] === 'string' && typeof str === 'string' && $scope.localData[i][searchFields[s]].toLowerCase().indexOf(str.toLowerCase()) >= 0);
                            }

                            if (match) {
                                matches[matches.length] = $scope.localData[i];
                            }
                        }

                        $scope.searching = false;
                        $scope.processResults(matches, str);

                    } else {  
                        $http.get($scope.url + str, {}).
                            success(function(responseData, status, headers, config) {
                                window.console.log(responseData);
                                $scope.searching = false;
                                $scope.processResults((($scope.dataField) ? responseData[$scope.dataField] : responseData ), str);
                            }).
                            error(function(data, status, headers, config) {
                                //console.log("error");
                            });
                    }
                }
            }

            $scope.hideResults = function() {
                $scope.hideTimer = $timeout(function() {
                    $scope.showDropdown = false;
                }, $scope.pause);
            };

            $scope.resetHideResults = function() {
                if($scope.hideTimer) {
                    $timeout.cancel($scope.hideTimer);
                };
            };

            $scope.hoverRow = function(index) {
                $scope.currentIndex = index;
            }

            $scope.keyPressed = function(event) {
                if(event.which==8 || event.which==46){
//                    clear_session_tags();
                      parse_tags_and_send();
                }
                if (!(event.which == 38 || event.which == 40 || event.which == 13)) {  
                    parse_tags_and_send();
                    if (!$scope.searchStr || $scope.searchStr == "") { 
                        $scope.showDropdown = false;
                        $scope.lastSearchTerm = null
                    } else if (isNewSearchNeeded($scope.searchStr, $scope.lastSearchTerm)) {
                        $scope.lastSearchTerm = $scope.searchStr
                        $scope.showDropdown = true;
                        $scope.currentIndex = -1;
                        $scope.results = [];
                        
                        if ($scope.searchTimer) {
                            $timeout.cancel($scope.searchTimer);
                        }
                        $scope.tagAddedFlag=false;
                        $scope.searching = true;

                        $scope.searchTimer = $timeout(function() {
                            $scope.searchTimerComplete($scope.searchStr);
                        }, $scope.pause);
                    }
                } else {
                    event.preventDefault();
                }
            }
            
            // added by Anastasia Sitnina for tags functionality
            // parses the string before adding a new tag
            function parse_sbar_replace(sbarStr, newTag){
                var prevTags= sbarStr.substr(0, sbarStr.lastIndexOf(','));  
                newTag=newTag.trim();
                if(prevTags.indexOf(newTag)===-1){
                    if(prevTags.length===0){
                        newTag=newTag+',';
                    }
                    else {
                        newTag=','+newTag+',';
                    }
                    var str=prevTags+newTag;
                }
                else {
                    var str=prevTags+',';
                }
                return str;
            }
            
            function parse_tags_and_send(){
                 $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/add-tags-to-session/',
                    data    : $.param({'tags':$scope.searchStr}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
//                            window.console.log(data); 
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                });   
            }           
            
            $scope.add_tag=function(){
                $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/add-tag/',
                    data    : $.param({'tag':parse_search_str_tags($scope.searchStr)}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
                            $scope.tagAddedFlag=true;
                            window.console.log(data);
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                });                
            }

            $scope.selectResult = function(result) {                
                if ($scope.matchClass) {
                    result.title = result.title.toString().replace(/(<([^>]+)>)/ig, '');
                }
                $scope.lastSearchTerm = result.title;
                $scope.searchStr=parse_sbar_replace($scope.searchStr,$scope.lastSearchTerm);                
                $scope.selectedObject = result;
                parse_tags_and_send();
                $scope.showDropdown = false;
                $scope.results = []; 
            }

            var inputField = elem.find('input');            

            inputField.on('keyup', $scope.keyPressed);

            elem.on("keyup", function (event) {
                if(event.which === 40) {
                    if ($scope.results && ($scope.currentIndex + 1) < $scope.results.length) {
                        $scope.currentIndex ++;
                        $scope.$apply();
                        event.preventDefault;
                        event.stopPropagation();
                    }

                    $scope.$apply();
                } else if(event.which == 38) {
                    if ($scope.currentIndex >= 1) {
                        $scope.currentIndex --;
                        $scope.$apply();
                        event.preventDefault;
                        event.stopPropagation();
                    }

                } else if (event.which == 13) {
                    if ($scope.results && $scope.currentIndex >= 0 && $scope.currentIndex < $scope.results.length) {
                        $scope.selectResult($scope.results[$scope.currentIndex]);
                        $scope.$apply();
                        event.preventDefault;
                        event.stopPropagation();
                    } else {
                        $scope.results = [];
                        $scope.$apply();
                        event.preventDefault;
                        event.stopPropagation();
                    }

                } else if (event.which == 27) {
                    $scope.results = [];
                    $scope.showDropdown = false;
                    $scope.$apply();
                } else if (event.which == 8) {
                    $scope.selectedObject = null;
                    $scope.$apply();
                }
            });

        }
    };
});
