<?php
/*
Template Name: Get Stared
*/
if ( is_user_logged_in() ) {
	
	$url = get_permalink( 6 );
		wp_redirect($url);
	    //wp_redirect( home_url('?page_id=6'), 301 ); 
		exit();
} 

get_header();


 ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			
            <div id="codbox">
            <input type="text" value="" id="getCode" name="getCode"> <button onClick="checkCoode();" >Validate</button>	
            <div id="codeError"></div>	
            </div>
            
            <div id="emailBox">
            
			<input type="text" value="" id="getEmail" name="getEmail"> <button onClick="checkEmailAddress();" >Get Code</button>	
            <div id="emailError"></div>		
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->
    
<script type="application/javascript">
function checkEmailAddress()
{
		$('#emailError').hide();
	 var email               = 	document.getElementById("getEmail").value.replace(/\s/g, "");  
	 	
	    if(email == "" )
    {
        showErrorDiv('#emailError','Please enter a e-mail address.');
 		return false;
    }
    if(email !="")
    {
        if (!echeck(email)) {
            	 showErrorDiv('#emailError','Please enter a valid e-mail address.');
			    return false;
        }
		
    }
	var logindata = $('#getEmail').serialize();;
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
function checkCoode()
{
	var ucode = $('#getCode').val().replace(/\s/g, "");
	var ucodeLength      =   ucode.length; 
	
	if(ucode == "" )
    {
        showErrorDiv('#codeError','Please enter a code.');
 		return false;
    }
	
	if(ucodeLength < 12 )
        {
          showErrorDiv('#codeError','Please enter a valid Code.');
		  return false;
        }
	var logindata = $('#getCode').serialize();;
	$.ajax({
type	: "POST",
cache	: false,
url     : ajaxurl,
dataType : 'json',
data: {
			'action' : 'userCodeData',
			'codedata' : logindata
	  },
success: function(data) {
$('#codeError').html('<span class ="'+data.response+'">'+data.message+'</span>'); 
	$('#codeError').show();
	if(data.response == "sucess"){
		setTimeout(function() { location.reload(true); }, 2000);
	}
                       }
          });		
		
}
</script>    

<?php get_sidebar(); ?>
<?php get_footer(); ?>