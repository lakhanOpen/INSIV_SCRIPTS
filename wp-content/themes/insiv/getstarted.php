<?php
/* Template Name: Get Stared */
if (is_user_logged_in() || !empty($_SESSION['nowuserid'])) {

     $url = get_permalink(6);
    wp_redirect($url);
} get_header('brown');

?>
<div class="planPage"> 
    <div class="container_12 greylight" style="height:800px;">  
        <div class="stepTop"><h4>Before we begin...</h4> 
            <p>do you have a subscription code?</p>     
            <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/stepshow.png"></p>   </div>
        <div class="codebox">    
            <form action="" method="post" onsubmit="return checkCoode();"> 
                <label>  
                <input type="password" placeholder="Enter your code here" id="getCode" name="getCode">  </label>  
                <span id="getValidateautoLoadImg" class="AjaxautoLoadImg" style="display: none;" > <img src="<?php bloginfo('stylesheet_directory'); ?>/images/Processing.gif"></span> 
                <input type="submit"  class="blueBtn" id="codeSubmit" name="codeSubmit" value="Validate">   
           
                <div  id="codeError" ></div>  
            </form>  
        </div>  
        <div class="codebox2">     
            <form action="" method="post" onsubmit="return checkEmailAddress();"> 
                <h4>You don't have one? <span class="blueBdr"></span></h4>  
                <p>Enter your email to request an invitation. </p> 
                <label><input type="text" placeholder="Email" id="getEmail" name="getEmail">     </label>
              <span id="getCodeautoLoadImg" class="AjaxautoLoadImg" style="display: none;"  > <img src="<?php bloginfo('stylesheet_directory'); ?>/images/Processing.gif"></span> 
                <input type="submit"  class="blackBtn" id="emailSubmit" name="emailSubmit" value="Get Code">
               
                <div id="emailError" ></div>  
            </form>  
        </div>   </div> <div class="clear"></div>   </div>   
<script type="application/javascript">
    function checkEmailAddress(){
        
        $(".errorMsg").remove();
        $('.errorIco').remove();
        $('.sucess').remove();
        $('.error').remove();
   
    var email               = 	document.getElementById("getEmail").value.replace(/\s/g, ""); 
    if(email == "" )    {  
    showErrorDiv('#emailSubmit','Please enter e-mail address');  
   
    return false;   
    }  
    if(email !="")    { 
        if (!echeck(email)) {  
        showErrorDiv('#emailSubmit','Invalid email');  
      
        return false;        
        }		  
    }	
    $('#getCodeautoLoadImg').show();
    var logindata = $('#getEmail').serialize();
    $.ajax({
    type	: "POST",
    cache	: false,
    url     : ajaxurl,
    dataType : 'json',
    data: {	
        'action' : 'userEmailRegister',
        'signdata' : logindata	
        },
        success: function(data) {
            $('#emailError').html('<span class ="'+data.response+'">'+data.message+'</span>');
            $('#emailError').show(); 
              $('#getCodeautoLoadImg').hide();
        }         
        });
        
      
      return false;
      }
      function showErrorDiv(divID,msg){
          var errormsgbox="<span class='errorIco' style='display: inline;'></span><div class='errorMsg'>"+msg+"</div>";
         $(divID).parent('form').append(errormsgbox);
      }

      function checkCoode(){
       
        $(".errorMsg").remove();
        
        $('.errorIco').remove();
        
      var ucode = $('#getCode').val().replace(/\s/g, "");
      var ucodeLength      =   ucode.length; 
      
      if(ucode == "" )    {     
      showErrorDiv('#codeSubmit','Please enter code'); 

      return false; 
      }		
      if(ucodeLength < 12 ){    
            showErrorDiv('#codeSubmit','Invalid Code');
       
            return false;   
      }	
      $('#getValidateautoLoadImg').show();
      var logindata = $('#getCode').serialize();
      $.ajax({type	: "POST",
      cache	: false,
      url     : ajaxurl,
      dataType : 'json',
      data: {			
      'action' : 'userCodeData',
      'codedata' : logindata	  },
      success: function(data) {
                   $('#codeError').html('<span class ="'+data.response+'">'+data.message+'</span>');
                   $('#codeError').show();
                    $('#getValidateautoLoadImg').hide();
      if(data.response == "sucess"){	
      setTimeout(function() { location.reload(true); }, 2000);	} 
      }
      
      });
     
      return false;	
      }
     
</script> 
   <?php get_footer(); ?>