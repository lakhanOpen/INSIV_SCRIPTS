<?php
  /*
  Template name: Edit View User Plan Detail 
  */
?>
<?php
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
wp_redirect(home_url());
}
    ?>

<?php get_header('white'); ?>


  <div class="planPage">
 <div class="container_12 greylight">
  
       <?php include_once 'userLeftSideBar.php'; ?>
    <div class="planRightpost">
     <div class="grid_8">
         
      <div class="planPostEdit">
      <h4>Order Summary</h4>
      <table class="orderSum" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">Plan<br>
    5 Flavors/month</td>
    <td width="70%"><div class="optionDrop">
    
<ul id="optionDrop">
		<li><a href="#" class="Drop boxSizing"></a>
			<ul class="boxSizing">
            <li><a href="#">Option 1</a></li>
            <li><a href="#">Option 2</a></li>
            <li><a href="#">Option 3</a></li>
             
      			</ul>
		</li></ul>
        </div></td>
  </tr>
  <tr>
    <td>Strength<br>
         20 mg</td>
    <td><div class="optionDrop">
    
<ul id="optionDrop">
		<li><a href="#" class="Drop boxSizing"></a>
			<ul class="boxSizing">
            <li><a href="#">Option 1</a></li>
            <li><a href="#">Option 2</a></li>
            <li><a href="#">Option 3</a></li>
             
      			</ul>
		</li></ul>
        </div></td>
  </tr>
   
</table>
<div class="btnSet">
<ul>
<li> <a class="blackBtn" href="#">Cancel</a></li>
 <li>  <a class="blueBtn" href="#">Save changes</a>
</li>
<li></li>
</ul>

</div>

      </div>
      
      <div class="planPostEdit"><h4>Shipping Address</h4>
       <div class="shiping">
      <ul>
      <li><input type="text" placeholder="First Name"></li>
      <li><input type="text" placeholder="Last name"></li>
      <li class="bigArea"><input type="text" placeholder="Address"></li>
      <li class="smallArea"><input type="text" placeholder="Apt/Suit"></li>
      <li class="smallArea"><input type="text" placeholder="City"></li>
      <li class="smallArea"><div class="stateDrop">
    
<ul id="stateDrop">
		<li><a href="#" class="Drop boxSizing">State</a>
			<ul class="boxSizing">
            <li><a href="#">State 1</a></li>
            <li><a href="#">State 2</a></li>
            <li><a href="#">State 3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea"><input type="text" placeholder="Zip code"></li>
      <div class="clear"></div>
      <li class="bigArea fLeft" style="margin-left:10px;"><div class="stateDrop">
    
<ul id="stateDrop">
		<li><a href="#" class="Drop boxSizing">Country</a>
			<ul class="boxSizing">
            <li><a href="#">Country 1</a></li>
            <li><a href="#">Country 2</a></li>
            <li><a href="#">Country 3</a></li>
             
      			</ul>
		</li></ul>
        </div> </li>
        <div class="clear"></div>
      <div class="btnSet">
<ul>
<li> <a class="blackBtn" href="#">Cancel</a></li>
 <li>  <a class="blueBtn" href="#">Save changes</a>
</li>
<li></li>
</ul>

</div>
      </ul>  
      
      </div>
       <div class="clear"></div>
       </div>
       
      <div class="planPostEdit"><h4>Billing Information</h4>
       <div class="shiping">
      <ul>
      <li class="bigArea"><input type="text" placeholder="Credit card number"></li>
      <li class="smallArea"><input type="text" placeholder="CVV"></li>
      <li class="smallArea"><p>Credit Card<br>
      Expiration date</p></li>
      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
		<li><a href="#" class="Drop boxSizing">12</a>
			<ul class="boxSizing">
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
		<li><a href="#" class="Drop boxSizing">Nove</a>
			<ul class="boxSizing">
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
             
      			</ul>
		</li></ul>
        </div></li>
      <li class="smallArea1"><div class="dateDrop">
    
<ul id="dateDrop">
		<li><a href="#" class="Drop boxSizing">2013</a>
			<ul class="boxSizing">
            <li><a href="#">2014</a></li>
            <li><a href="#">2015</a></li>
            <li><a href="#">2016</a></li>
             
      			</ul>
		</li></ul>
        </div></li>     
          <div class="clear"></div>

      <li><input id="default"   type="checkbox" name="default" value="default"> <p class="checkText">Billing address is the same as shipping address</p> </li>
      <div class="btnSet">
<ul>
<li> <a class="blackBtn" href="#">Cancel</a></li>
 <li>  <a class="blueBtn" href="#">Save changes</a>
</li>
<li></li>
</ul>

</div>
      </ul>  
      
      </div>
       <div class="clear"></div>
       </div>
       
      <div class="planPostEdit"><h4>Subscription Status</h4>
       <p>Active |  Purchased: 12 - Nov -2013 |  Charged Monthly</p>
      <h5><a href="#">Cancel Subscription</a></h5>
      </div>
      
  
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
	</script>

<?php get_footer(); ?>