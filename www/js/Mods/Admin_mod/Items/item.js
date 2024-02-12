Admin.controller('item', function($http){         
        var self=this;
        this.data={};
        this.hide=false;
        this.del=function(id, link) {
            self.data={id:id};
            $http({
                method  : 'POST',
                url     : 'http://'+document.domain+'/admin/ajax/delete-'+link+'/',
                data    : $.param(self.data),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function(data) {
                        window.console.log(data);
                        self.hide=true;
                });
        };
});

