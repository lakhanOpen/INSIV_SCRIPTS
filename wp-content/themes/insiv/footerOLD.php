 <div class="footer">
     <script type="text/javascript">
     function echeck(str) {
	

    var at="@";
    var dot=".";
    var lat=str.indexOf(at);
    var lstr=str.length;
    var ldot=str.indexOf(dot);
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
   <div class="container_12">

  <div class="grid_1 footerLogo alpha"><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logoFooter.png"></a></div>

 

 <div class="footerLinks grid_7">

 <ul>

<!-- <li><a href="<?php //echo get_permalink( 9 ); ?>">About Us</a></li>-->
     
 <li><a href="<?php echo get_permalink( 1478 ); ?>">FAQ</a></li>
 
 <li><a href="<?php bloginfo('url'); ?>/blog/">Blog</a></li>

 <li><a href="<?php echo get_permalink( 13 ); ?>">Terms</a></li>

 <li><a href="<?php echo get_permalink( 15 ); ?>">Privacy</a></li>

<!--<li><a href="<?php //echo get_permalink( 86 ); ?>">Newsletter Subscription</a></li>-->

  </ul>

 

 </div>

   

   

   <div class="grid_3 fRight omega socialLinks">

   <ul>

   <li><a href="mailto:info@insiv.com" class="emailIco"></a></li>

   <li><a href="http://www.facebook.com/ItsNotSmokeItsVapor" target="_blank" class="fbIco"></a></li>

   <li><a href="http://www.twitter.com/INSIV" target="_blank" class="twitterIco"></a></li>

   <li class="mR0"><a href="http://www.instagram.com/vape_INSIV" target="_blank" class="instagramIco"></a></li>

   

   </ul>

   

   </div>

   

   </div>

   

   

   </div>

   </div>

   <!--Slider -->

<!--  	<script src="js/slider.js"></script>-->

	<script>

		//$('.full-width').fullWidth();

	</script>

<?php wp_footer(); ?>

</body>

</html>