Admin.controller('GeneralTexts', function($http,$scope,$sce,ngDialog){         
        var self=this;
        $scope.options={
            
        };
        
        this.generatedFormData={
            
        };
        
        // form for the new item
        self.newItemForm='';
        
        //page number
        this.page=1;
        
        // container for doctrine repo
        this.repo;  
        
        this.editingMode={};
        
        
        // set options and get default repo
        $http({
            method  : 'POST',
            url     : 'http://'+document.domain+'/admin/ajax/general-texts-create-selects/',
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(data) {                    
                    $scope.options=data.texts;
                    self.repo=$scope.options[0].repo;
                    self.update();
            });
            
        this.generatedFormSubmit=function(){
            var post={
                    form:self.generatedFormData,
                    repo:self.repo
            }
            
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/general-texts-form-action/',
                data    : $.param(post),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function(data) {
                       self.update();
                       self.editingMode={};
                       self.generatedFormData={};
                       var savedSign=ngDialog.open({ plain:true,template: '<div class="popup"><p class="msg saved">Saved</p></div>' });
                       setTimeout(function(){ ngDialog.close(savedSign); }, 500);                       
                });
        };
            
        this.getTexts=function(){
            $http({
            method  : 'POST',
            url     : 'http://'+document.domain+'/admin/ajax/general-texts-get-texts/'+self.page+'/',
            data    : $.param({"repo":self.repo}),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(data) {
                    for(var i=0;i<data['data'].length;i++){
                        data['data'][i].listPreview=data['data'][i].text.replace(/<[^>]+>/gm, '');                        
                    }
                    $scope.texts=data['data'];                    
                    $scope.pages=[];
                    for(var i=0; i<data['totalPages'];i++){
                        $scope.pages.push(i+1);
                    }                    
            });
        }; 
        
        self.update=function(){
            self.getTexts();
            self.getForm();
        };
        
         this.getForm=function(){
            $http({
            method  : 'POST',
            url     : 'http://'+document.domain+'/admin/ajax/general-texts-get-form/',
            data    : $.param({"repo":self.repo}),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(data) {
                    self.generatedFormData={};
                    self.newItemForm=$sce.trustAsHtml(data);                    
            });
        };
        
        this.setPage=function(page){
            self.page=page;
            this.getTexts();
        };
        
        // OVERRIDING STANDARD DELETE ITEM
        this.delData={};
        
        this.del=function($index, link) { 
            self.delData={id:$scope.texts[$index].id,repo:self.repo};
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/delete-'+link+'/',
                data    : $.param(self.delData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function(data) {
//                        self.getTexts();
                        self.update();
                });
        };
        
        this.new_show_form=function(){                      
            if(self.editingMode.length!==0){
                self.editingMode={};                
                $scope.isFilterTypesOpen=true;
                self.generatedFormData={};
                self.getForm();
            }
            else {
                $scope.isFilterTypesOpen = !$scope.isFilterTypesOpen;
                self.generatedFormData={};
                self.getForm();
            }
            
            
        };
        
        this.edit=function($index) {            
            var item=$scope.texts[$index];  // get the item that is being edited         
            self.editingMode[$index]=true; // set the class for item that is being edited
            var fields=Object.keys(item);
            for(var i=0; i<fields.length;i++){                
                if(fields[i]!=='id'&&fields[i]!=='$$hashKey'&&fields[i]!=='listPreview'){
                    self.generatedFormData[fields[i]]=item[fields[i]];
                }    
                self.generatedFormData['id']=item['id'];
                $scope.isFilterTypesOpen=true;
            }
        };
        
       
});

Admin.directive("compileHtml", function($parse, $sce, $compile) {
    return {
        restrict: "A",
        link: function (scope, element, attributes) {
 
            var expression = $sce.parseAsHtml(attributes.compileHtml);
 
            var getResult = function () {
                return expression(scope);
            };
 
            scope.$watch(getResult, function (newValue) {
                var linker = $compile(newValue);
                element.html(linker(scope));
            });
        }
    }
});

Admin.directive('ckEditor', [function () {
    return {
        require: '?ngModel',
        link: function ($scope, elm, attr, ngModel) {
            var ck = CKEDITOR.replace(elm[0]);

            ck.on('pasteState', function () {
                $scope.$apply(function () {
                    ngModel.$setViewValue(ck.getData());
                });
            });

            ngModel.$render = function (value) {
                ck.setData(ngModel.$modelValue);
            };
        }
    };
}]);




