<?php
/* Template Name: Get Stared */
if (is_user_logged_in() || !empty($_SESSION['nowuserid'])) {

     $url = get_permalink(6);
    wp_redirect($url);
} get_header('brown');

?>
<div class="planPage"> 
    <div class="container_12 greylight">  
        <div class="stepTop"><h4>Before we begin...</h4> 
            <p>do you have a subscription code?</p>     
            <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/stepshow.png"></p>   </div>
        <div class="codebox">    
            <form action="" method="post" onsubmit="return checkCoode();"> 
                <label>         <input type="password" placeholder="Enter your code here" id="getCode" name="getCode">  </label>  
                <input type="submit"  class="blueBtn" id="codeSubmit" name="codeSubmit" value="Validate">
                <!--         <a class="blueBtn" href="javascript:void(0);" onClick="checkCoode();">Validate</a>-->
                <span class='errorIco' id="codeErrorIco"></span>   
                <div id="codeError" class="errorMsg"></div>  
            </form>  
        </div>  
        <div class="codebox2">     
            <form action="" method="post" onsubmit="return checkEmailAddress();"> 
                <h4>You don't have one? <span class="blueBdr"></span></h4>  
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p> 
                <label><input type="text" placeholder="Email" id="getEmail" name="getEmail">     </label>
                <!--        <a class="blackBtn" href="javascript:void(0);" onClick="checkEmailAddress();" >Get Code</a> -->
                <input type="submit"  class="blackBtn" id="emailSubmit" name="emailSubmit" value="Get Code">
                <span class='errorIco' id="emailErrorIco"></span>  
                <div id="emailError" class="errorMsg"></div>  
            </form>  
        </div>   </div> <div class="clear"></div>   </div>   
<script type="application/javascript">
    function checkEmailAddress(){	
    $('#emailError').hide();	
    var email               = 	document.getElementById("getEmail").value.replace(/\s/g, ""); 
    if(email == "" )    {  
    showErrorDiv('#emailError','Please enter e-mail address.');  
    $('#emailErrorIco').show(); 
    return false;   
    }  
    if(email !="")    { 
        if (!echeck(email)) {  
        showErrorDiv('#emailError','Invalid email.');  
        $('#emailErrorIco').show();	
        return false;        
        }		  
    }	
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
        }         
        });
      return false;
      }
      function showErrorDiv(divID,msg){
        $(divID).text(msg); 
        $(divID).show();
      }
      function echeck(str) {
      var at="@";  
      var dot=".";
      var lat=str.indexOf(at);
      var lstr=str.length;
      var ldot=str.indexOf(dot);
      var errors = []; 
      if (str.indexOf(at)==-1){ 
      return false;    } 
      if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
      return false;    }  
      if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){   
      return false;    }  
      if (str.indexOf(at,(lat+1))!=-1){    
      return false;    }   
      if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){ 
      return false;    }  
      if (str.indexOf(dot,(lat+2))==-1){    
      return false;    }  
      if (str.indexOf(" ")!=-1){   
      return false;    } 
      return true;}
      function checkCoode(){
      var ucode = $('#getCode').val().replace(/\s/g, "");
      var ucodeLength      =   ucode.length; 
      if(ucode == "" )    {     
      showErrorDiv('#codeError','Please enter code.'); 
      $('#codeErrorIco').show(); 
      return false; 
      }		
      if(ucodeLength < 12 ){    
            showErrorDiv('#codeError','Invalid Code');
            $('#codeErrorIco').show();
            return false;   
      }	
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
      if(data.response == "sucess"){	
      setTimeout(function() { location.reload(true); }, 2000);	} 
      }     
      });	
      return false;	
      }
     /* $('codebox').click(function() { 
      
    
    checkCoode();});*/
</script> 
   <?php get_footer(); ?>