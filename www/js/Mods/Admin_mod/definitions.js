var Admin=angular.module('Admin', ['ngUtils','ngDialog']);

$(document).ready(function(){
   if(typeof $('.ck').html()!=="undefined"){      
        CKEDITOR.replaceAll('ck');
   } 
});


