<?php
/*
Template Name: Login form
*/

if ( is_user_logged_in() ) {
	
	
		wp_redirect(home_url());
	    //wp_redirect( home_url('?page_id=6'), 301 ); 
		exit();
}
get_header();


 ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div id="errorMessage"></div>
            <div id="codbox">
                          <form name="login" id="loginfrm" method="post"  >
                        <ul class="inPuT">
                        <li><input type="text" name="username" id="username" placeholder="Email Address"></li>
                        <li> <div id="userError"></div>	</li>
                        <li><input type="password" name="password" id="password" placeholder="Password"></li>
                          <li> <div id="passError"></div>	</li>
                        <?php wp_nonce_field('login_me','security',false); ?>
                        </ul>
                        </form>
            <button onClick="checkEmailAddress();" >login</button>	
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->
    
<script type="application/javascript">
function checkEmailAddress()
{
		$('#userError').hide();
		$('#passError').hide();
		$('#errorMessage').hide();
		
	 var email               = 	document.getElementById("username").value.replace(/\s/g, "");
	 var password            = 	document.getElementById("password").value.replace(/\s/g, ""); 
	 var passwordLength      =   password.length; 
	 var error 				 = 0;  
	 	
	    if(email == "" )
    {
        showErrorDiv('#userError','Please enter a e-mail address.');
 		error =1;
    }
    if(email !="")
    {
        if (!echeck(email)) {
            	 showErrorDiv('#userError','Please enter a valid e-mail address.');
			    error =2;
        }
		
    }
	
	if(password == "")
	{
				showErrorDiv('#passError','Please enter a Password.');
				error =3;
			    
	}
	if(password != ""){
				if(passwordLength < 9){
							showErrorDiv('#passError','Password at least 9 charter.');
							error =4;
				}
	}
	
	if(error > 0 ){
	return false;
	}
	var logindata = $('#loginfrm').serialize();;
	$.ajax({
type	: "POST",
cache	: false,
url     : ajaxurl,
dataType : 'json',
data: {
			'action' : 'ajaxUserLogin',
			'signupdata' : logindata
	  },
success: function(data) {
$('#errorMessage').html('<span class ="'+data.response+'">'+data.message+'</span>'); 
	$('#errorMessage').show();
	
	if(data.response == "sucess"){
		setTimeout(function() { location.reload(true); }, 2000);
	}
                       }
          });	
	
}
function showErrorDiv(divID,msg){
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

<?php get_sidebar(); ?>
<?php get_footer(); ?>