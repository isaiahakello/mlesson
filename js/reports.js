$(document).ready(function(){ 
 $("form > div > select[name=filter]").change(function(){
    var value = $("form > div > select[name=filter]").val();
    if(value =='class'){
        $("input#new_value").attr('placeholder','Enter Class (5,6,7 or 8)');
    }
    else if(value =='code'){
        $("input#new_value").attr('placeholder','Enter Subject Code');
    }
    else if(value =='start'){
        $("input#new_value").attr("placeholder","pick date");
        $("input#new_value").datepicker({dateFormat: 'yy-mm-dd'});
    }
    else if(value =='date'){
        $("input#new_value").attr("placeholder","pick date");
        $("input#new_value").datepicker({dateFormat: 'yy-mm-dd'});
    }
    else if(value =='end'){
        $("input#new_value").attr("placeholder","pick date");
        $("input#new_value").datepicker({dateFormat: 'yy-mm-dd'});
    }
    else if(value =='subject'){
        $("input#new_value").attr('placeholder','Enter Subject');
    }
     else if(value =='quizdate'){
        $("input#new_value").attr("placeholder","pick date");
        $("input#new_value").datepicker({dateFormat: 'yy-mm-dd'});
    }
    else if(value =='id'){
        $("input#new_value").attr('placeholder','Enter Question ID');
    }
    else if(value =='status'){
        $("input#new_value").attr('placeholder','1 for active,2 for incomplete,3 for cancelled');
    }
    else if(value =='number'){
        $("input#new_value").attr('placeholder','Enter Phone Number');
    }
     else if(value =='credit'){
        $("input#new_value").attr('placeholder','Enter Max. Credit');
    }
 });
 $("form > div > select[name=filter2]").change(function(){
    var value = $(this).val();
    if(value == 'custom'){
    $(".form2").css('margin-left','10px');
    $("input#new_value2").attr({ type:"text", placeholder:"start date" });
    $("input#new_value2").datepicker({dateFormat: 'yy-mm-dd'});
    $("input#new_value3").attr({ type:"text", placeholder:"end date" });
    $("input#new_value3").datepicker({dateFormat: 'yy-mm-dd'});
    }
 });
});
 