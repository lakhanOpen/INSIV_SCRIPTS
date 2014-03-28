<?php
  /*
  Template name: Forgot Password
  */
?>
<?php
$current_user = wp_get_current_user();
if ( 0 != $current_user->ID ) {
wp_redirect(home_url());
}
    ?>

<?php get_header('white'); ?>


 <div class="container_12 greylight">
      <div id="forgetDiv">
          
                 <div class="forgotetext">
     <h3>Forgot Password</h3>
     <p>To change your password,<br>
      enter the email address you provided when you<br>
         created the account.</p> </div>
   
                 <div class="boxmail">
                     <!--<form name="forgot-form" id="forgot-form" >-->
                     	<label>Email Address <input type="text" name="getEmail" id="getEmail"> </label>
                         <div id="emailError"></div>
                          <span class='errorIco' id="emailErrorIco"></span>
                         <label><input type="button" class="blueBtnBig" value="Submit" onclick="checkEmailAddress();" ></label>
                         <div id="getValidateautoLoadImg" class="AjaxautoLoadImg1" style="display: none;" > <img src="<?php bloginfo('stylesheet_directory'); ?>/images/Processing.gif"></div> 
                     <!--</form>-->
     </div>
      </div>
     <div id="confirmDiv" style="display: none;" >
            <div class="boxBack">
   <h3>Check your e-mail!</h3>
   <p>We have sent you an e-mail <br>
with a link and instructions to <br>
reset your password.</p>
    <div class="confirmBackBTN"><a class="blackBtnBig" href="<?php  echo home_url(); ?>">Back</a></div>
     </div>
         
         
     </div>
 </div>
       
<script type="application/javascript">
 $('#getEmail').keypress(function(event) { 
 		if(event.keyCode == 13)
		{
			checkEmailAddress();
		}
	});   
function checkEmailAddress()
{
        $('.errorIco').hide();      
        $('#emailError').hide();
	 var email               = 	document.getElementById("getEmail").value.replace(/\s/g, "");  
	 	
	    if(email == "" )
    {
        showErrorDiv('#emailError','Please enter e-mail address.');
 		return false;
    }
    if(email !="")
    {
        if (!echeck(email)) {
            	 showErrorDiv('#emailError','Please enter valid e-mail address.');
			    return false;
        }
		
    }
	$('#getValidateautoLoadImg').show();
	var logindata = $('#getEmail').serialize();;
	$.ajax({
type	: "POST",
cache	: false,
url     : ajaxurl,
dataType : 'json',
data: {
			'action' : 'userEmailForget',
			'signdata' : logindata
	  },
success: function(data) {

        
        if(data.response == "sucess"){
		$('#forgetDiv').hide();
                $('#confirmDiv').show();
	}else{
            $('#emailError').html('<span class ="'+data.response+'">'+data.message+'</span>'); 
            $('#emailError').show();
                       }
                 }
          });	
	
}
function showErrorDiv(divID,msg){
        $('#emailErrorIco').show();
	$(divID).text(msg); 
	$(divID).show();
      
}
function echeck(str) {
	

    var at="@"
    var dot="."
    var lat=str.indexOf(at)
    var lstr=str.length
    var ldot=str.indexOf(dot)
    var errors = [];
    if (str.indexOf(at)==-1){
       
        return false;
    }

    if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
       
        return false;
    }

    if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
        
        return false;
    }

    if (str.indexOf(at,(lat+1))!=-1){
      
        return false;
    }

    if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
        
        return false;
    }

    if (str.indexOf(dot,(lat+2))==-1){
       
        return false;
    }

    if (str.indexOf(" ")!=-1){
       
        return false;
    }

    return true;
}

</script>    


   


<?php get_footer(); ?>