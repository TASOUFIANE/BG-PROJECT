$(function(){
   var passField=$('.password');
   $('.showpass').hover(function(){
     passField.attr('type','text');}
     ,function(){
      passField.attr('type','password');
     }
   );


});



/* $('input').each(function(){
        if($(this).attr('required')==='Nrequired'){
          $(this).after('<span class="asterisk" style="font-size:25px;color:red;">*</span>');

        }
    })*/