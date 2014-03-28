<div class="main">
<div class="header">
<div class="container_12">

<div class="grid_2 logo alpha"><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png"></a></div>
<div class="grid_7 fRight omega login">
			<?php
            $current_user = wp_get_current_user();
            if ( 0 == $current_user->ID ) {
                ?>                        
               		 <div class="loginDrop">                
                                <ul id="loginDrop">
                                        <li><a href="javascript:void(0);" onclick="callLogin();" id="loginbut" class="Drop boxSizing">Login</a>
                                            <form name="login" id="loginfrm" method="post" onsubmit="return checkLoginEmailAddress();" >
                                            <ul class="boxSizing" id="loginBox">
                                               <div id="errorMessage"></div>
                                            <li><label>User Name</label>
                                                <input type="text"  name="username" id="username" placeholder="Email Address" class="boxSizing" tabindex="1" > 
                                         
                                            </li>
                                                <li><label>Password</label>  <a href="<?php echo get_permalink( 33 ); ?>">Forgot your password?</a>
                                            <input type="password"  name="password" id="password" placeholder="Password" class="boxSizing" tabindex="2" >
                                          
                                            
                                                <li><input type="submit" value="Login" class="boxSizing blueBtn" tabindex="3" ></li>
                                             <?php wp_nonce_field('login_me','security',false); ?>
                                            <li class="lastLi">Not a registered user?   <a href="<?php echo get_permalink( 24 ); ?>">Get Started!</a></li>
                                                </ul>
                                           </form>
                                        </li></ul>
                    </div>
                                            
            
              <?php } else {?>
        
               
               <div class="loginDropIn">
    
            <ul id="loginDropIn">
                    <li><a href="javascript:void(0);" class="Drop boxSizing">Hi, <?php echo $current_user->data->display_name; ?> </a>
                        <ul class="boxSizing">
<!--                        <li><a href="javascript:void(0);">My Account</a></li>-->
                        <li><a href="<?php echo get_permalink( 66 ); ?>">My Profile</a></li>
                        <li><a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a></li>                         
                        </ul>
                    </li>
                 </ul>
        </div>
        
            <?php }   ?>  
 </div>
</div>

</div>
 <?php if ( 0 == $current_user->ID ) {?>   
    <script type="text/javascript">
    function callLogin()
    {
       $('.errorMsg').hide();
        $('.errorIco').hide();
        $( "#loginBox" ).toggle();
		$('#loginbut').toggleClass('DropAct');
		 
    }
    
    
function checkLoginEmailAddress()
{
        $(".errorMsg").remove();
        $('.errorIco').remove();
		
	 var email               = 	document.getElementById("username").value.replace(/\s/g, "");
	 var password            = 	document.getElementById("password").value.replace(/\s/g, ""); 
	 var passwordLength      =   password.length; 
	 var error 				 = 0;  
	 	
	    if(email == "" )
    {
        showloginErrorDiv('#username','Please enter e-mail address');		
 		error =1;
    }
    if(email !="")
    {
        if (!echeck(email)) {
            	 showloginErrorDiv('#username','Please enter valid e-mail address');				
			    error =2;
        }
		
    }
	
	if(password == "")
	{
				showloginErrorDiv('#password','Please enter Password');				 
				error =3;
			    
	}
	if(password != ""){
				if(passwordLength < 7){
							showloginErrorDiv('#password','Password at least 7 characters');							
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
	return false;
}
function showloginErrorDiv(divID,msg){

	/*$(divID).text(msg); 
	$(divID).show();*/        
    var errormsgbox="<span class='errorIco' style='display: inline;'></span><div class='errorMsg'>"+msg+"</div>";
    $(divID).parent('li').append(errormsgbox);
}

$('body').click(function() {
    
    if($('#loginBox').css('display') == 'block')
  callLogin();
});

$('#loginBox').click(function(event){
   event.stopPropagation();
}); 

$('#loginDrop').click(function(event){
   event.stopPropagation();
}); 

</script>    
 <?php } ?>