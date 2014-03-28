<?php
  /*
  Template name: Edit Profile
  */
?>
<?php
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
wp_redirect(home_url());
}
$errorMsg ='';
if(!empty($_SESSION['errorMsg']))
{
$errorMsg =$_SESSION['errorMsg'];
}
    ?>

<?php get_header('white'); ?>


  <div class="planPage">
     
 <div class="container_12 greylight">
      <?php if(!empty($errorMsg)){ echo "<div class='errorMsgTop'>".$errorMsg."</div>"; $errorMsg ='';}?>
<?php include_once 'userLeftSideBar.php'; ?>
   
           <?php 
  $current_user = wp_get_current_user();
   $all_meta_for_user = get_user_meta( $current_user->ID );
   
   $firstName = (!empty($all_meta_for_user['first_name'][0])) ? $all_meta_for_user['first_name'][0] : '';
   $lastName = (!empty($all_meta_for_user['last_name'][0])) ? $all_meta_for_user['last_name'][0] : '';
?>
     
    <div class="planRightpost">
     <div class="grid_8">
         <form method="post" action="<?php echo get_permalink( 127 ); ?>" name="userForm" id="userForm"  onsubmit="return checkUserform();">
             
                    <div class="planPostEdit tabPass ">
                    <h4>Profile Information</h4>
                    <label><p>First Name</p> <input type="text" placeholder="First Name" name="firstName" id="firstName" value="<?php echo $firstName; ?>"></label> 
              <label><p>Last Name</p> <input type="text" placeholder="Last Name" name="lastName" id="lastName" value="<?php echo $lastName; ?>" ></label> 
              <label><p>Email Address</p> <p><?php echo $current_user->data->user_email; ?></p></label> 

                    <div class="clear"></div>
                    </div>

                    <div class="planPostEdit tabPass "><h4>Change Password</h4>
                        <label><p>New Password</p> <input type="password" placeholder="New Password" name="newPassword" id="newPassword"></label> 
                        <label><p>Confirm Password </p> <input type="password" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword"></label> 

                     <div class="clear"></div>
                     </div>



                    <div class="confirmation">
                     <h4>To save these settings, please enter your<br>
                     current password</h4>
                        <label>
                        <input type="password" name="currentPassword" id="currentPassword" >   </label>
                        <input type="submit" class="blackBtn" name="userSubmit" value="Save">
                        <div class="clear"></div>
                        <div id="passwordErrorMsg" ></div>
                     <p><a href="<?php echo get_permalink( 63 ); ?>">Cancel changes made</a></p>
                     </div>
         </form>
     </div>
    </div>
   
 </div>

 <div class="clear"></div>
   </div>
   
       <!--CheckBox-->
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/checkbox.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function(){ 
		$('#default').betterCheckbox();
		$('#default-dis').betterCheckbox();
		$('#default-dis').betterCheckbox('disable');
		
 	});
        
        function checkUserform()
        {
        $('.errorMsg').hide();
        $('.errorIco').hide();
        
        var firstName           = $('#firstName').val().trim();
        var lastName            = $('#lastName').val().trim();
        var newPassword         = $('#newPassword').val().trim();
        var confirmPassword     = $('#confirmPassword').val().trim();
        var currentPassword     = $('#currentPassword').val().trim();
        
         var errors = [];
         var as =1;
         
             if(firstName == "" )
                {
                       showCheckOutMsg('firstName','Enter First Name');                 
                       errors[errors.length] =as++;
                }
                
                 if(lastName == "" )
                {
                       showCheckOutMsg('lastName','Enter Last Name');                 
                       errors[errors.length] =as++;
                }

              
              if(confirmPassword != "")
              {
                 if (newPassword == "")
                {	
                       showCheckOutMsg('newPassword','Enter New Password');                 
                       errors[errors.length] =as++;
                }                              
 
              }
              if(newPassword != confirmPassword)
                    {
                        showCheckOutMsg('confirmPassword','New Password And Confirm Password Do Not Match');                 
                        errors[errors.length] =as++; 
                    }   
              if (newPassword != "")
                {   
                    if(newPassword.length < 7 )
                            {
                                 showCheckOutMsg('newPassword','Password must be 7 characters');                 
                                 errors[errors.length] =as++;
                            }
                }                
                
               if (currentPassword == "")
                {	
                       showCheckOutPasswordMsg('currentPassword','Enter Password');                 
                       errors[errors.length] =as++;
                }

             if (currentPassword != "")
                {   
                    if(currentPassword.length < 7 )
                            {
                                 showCheckOutPasswordMsg('currentPassword','Password must be 7 characters');                 
                                 errors[errors.length] =as++;
                            }
                }

                 if (errors.length > 0) { 
                             return false;
                         }

                return true;
        }
        
        

    
function showCheckOutMsg(inputid,ermsg)
{
    var errormsgbox="<span class='errorIco' style='display: inline;'></span><div class='errorMsg'>"+ermsg+"</div>";
    $('#'+inputid).parent('label').append(errormsgbox);
  
}
    
function showCheckOutPasswordMsg(inputid,ermsg)
{
    var errormsgbox="<span class='errorIco' style='display: inline;'></span><div class='errorMsg'>"+ermsg+"</div>";
    $('#passwordErrorMsg').append(errormsgbox);
  
}

	</script>

<?php get_footer(); ?>