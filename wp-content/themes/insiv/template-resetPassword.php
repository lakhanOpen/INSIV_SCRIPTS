<?php
  /*
  Template name: Reset Password
  */
?>
<?php
$token=$_GET['token'];
if(trim($token)=="")
{
	wp_redirect(home_url());
	exit();    
}
?>
<?php get_header('white'); ?>


 <div class="container_12 greylight">
      <div id="forgetDiv">
          
                 <div class="forgotetext">
     <h3>Reset Password</h3>
     <p>To change your password,<br>
      enter the new password.</p> </div>
   
                 <div class="boxmail">
                     <!--<form name="forgot-form" id="forgot-form" >-->
                     	<input type="password" name="new_password" id="new_password" placeholder="Password">
                        <div id="npError"></div>
                          <span class='errorIco' id="npErrorIco"></span>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                         
                          
                          <div id="cpError"></div>
                          <span class='errorIco' id="cpErrorIco"></span>
                          <input type="hidden" name="token_hidden" id="token_hidden" value="<?php echo $token; ?>" />
                         <label><input type="button" class="blueBtnBig" value="Submit" onclick="checkValidation();" ></label>
                         <span class="error" id="errorspan"></span>
                         <span class="success"  id="successspan"></span>
                     <!--</form>-->
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
function checkValidation()
{
        $('.errorIco').hide(); 
		$('#npError').hide();     
        $('#cpError').hide();
		$('#errorspan').hide();
		$('#successspan').hide();
		
		var newPassword=document.getElementById("new_password").value;
		var confirmPassword=document.getElementById("confirm_password").value;
		var tokendata=document.getElementById("token_hidden").value;
		
		if(newPassword=="")
		{
			showErrorDiv("#npError","#npErrorIco","Enter Password");
		}
		else if(newPassword.length < 7 )
		{
			showErrorDiv("#npError","#npErrorIco",'Password must be at least 7 characters');              
		}
		
		if(confirmPassword=="")
		{
			showErrorDiv("#cpError","#cpErrorIco","Enter Confirm Password");
		}
		else if(confirmPassword.length < 7 )
		{
			showErrorDiv("#cpError","#cpErrorIco",'Confirm Password must be at least 7 characters');              
		}
		else if(newPassword!=confirmPassword)
		{
			showErrorDiv("#cpError","#cpErrorIco",'Confirm Password should be same as new password');              
		}
		else
		{
			var logindata = $('#getEmail').serialize();;
			$.ajax({
					type	: "POST",
					cache	: false,
					url     : ajaxurl,
					dataType : 'json',
					data: {
					'action' : 'userResetForget',
					'newPassword': newPassword,
					'token' : tokendata
				},
				success: function(data) 
				{
					if(data.response == "sucess"){
						$('#successspan').html(data.message); 
						$('#errorspan').hide();
						$('#successspan').show();
					}else{
						$('#errorspan').html(data.message); 
						$('#successspan').hide();
						$('#errorspan').show();
					}
				}
			});
		}
}

function showErrorDiv(divID,imageID,msg){
    $(imageID).show();
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