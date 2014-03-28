<?php 
global $wpdb;
$msg='';

if(isset($_POST['addSet'])) {
    
    if(!empty($_POST['codeText']))
    {

        extract($_POST);
        $adddate = time();
        $codeText = esc_sql(trim($codeText));
        $selectsql = "SELECT id FROM ".$wpdb->prefix . "coupon_generator where coupontext = '".$codeText."' and status='1'  LIMIT 1";
        $codearray = $wpdb->get_results($selectsql) ;
                
        if(empty($codearray)){
            $check=0;
 
          try {
              
                $coupon = new Recurly_Coupon();
                $coupon->coupon_code        = $codeText;
                $coupon->redeem_by_date     = $redeemDate;
                $coupon->name               = $couponName;
                $coupon->discount_type      = 'percent';
                $coupon->discount_percent   = $discount;
                $coupon->create();


              $check  = 1;
          }catch (Recurly_NotFoundError $e) {                 
                            $msg['text']="Record could not be found!";
                            $msg['class'] ='error';
                      }
                      catch (Recurly_ValidationError $e) {
                          $messages = explode(',', $e->getMessage());                      
                          $msg['text']='Validation problems:'. implode("\n", $messages);
                            $msg['class'] ='error';
                      }
                      catch (Recurly_ServerError $e) {                                    
                            $msg['text']="Oops, Something wrong!";
                            $msg['class'] ='error';
                      }
                      catch (Exception $e) {               
                            $msg['text']=get_class($e) . ': ' . $e->getMessage();
                            $msg['class'] ='error';
                      } 
            
                      if($check == 1){
       $sql = "INSERT INTO ".$wpdb->prefix . "coupon_generator (coupontext,couponName,redeemDate,discount,status,adddate) 
					VALUES('".$codeText."','".esc_sql(trim($couponName))."','".esc_sql(trim($redeemDate))."','".esc_sql(trim($discount))."','1','".esc_sql($adddate)."')";
    
             $insrtCode = $wpdb->query($sql);
             
              $msg['text']='Coupon successfully add.';
              $msg['class'] ='updated';
            }
        }  
        else {
                $msg['text']='Coupon already exists.';
                $msg['class'] ='error';
             }
    }else{
        $msg['text']='Please fill all detail.';
        $msg['class'] ='error';
    }
  
}

?>
<style type="text/css">
    
    .lableCss{ margin-right: 10px; width:150px; display: inline-block; text-align: left;  }
    
    
</style>
<?php 


wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
echo '<script>';
echo ' jQuery(document).ready(function() { ';
echo " jQuery('#redeemDate').datepicker({ ";
echo " changeMonth: true,changeYear: true,minDate: -0,dateFormat : 'yy-mm-dd',yearRange: 'c:+1'";
echo ' });';
echo ' }); ';
echo '</script>';


?>
<div class="wrap"><h2>Add Coupon Generator</h2>
    
    <?php if(!empty($msg)){ ?>
    <div id="mssage" class="<?php echo $msg['class']; ?>"><?php echo $msg['text']; ?></div>
    <?php }?>

    
    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="add_Coupon_set" name="add_Coupon_set" onsubmit="return checkAddCoupon(this);" >
    <ul>
        
        <li><label for="uselimit" class="lableCss">Coupon Name <span> *</span>: </label>
            <input type="text" value="" name="couponName" id="couponName" size="50" maxlength="50" placeholder="Coupan Name" /></li>
        
        <li><label for="codeText" class="lableCss">Coupon Code<span> *</span>: </label>
            <input  type="text" name="codeText"  id="codeText" size="50" maxlength="50" value=""  placeholder="Coupan Code"/>
        <span class="button-secondary" onclick="callGetcode();">Get code</span>
        </li>    
        
        <li><label for="uselimit" class="lableCss">Last Date<span> *</span>: </label>
            <input type="text" value="" name="redeemDate" id="redeemDate" size="50" placeholder="Last Date To Redreem" /></li>
            
        <li><label for="uselimit" class="lableCss">Discount<span> *</span>: </label>
        <input type="text" value="" name="discount" id="discount" size="50" maxlength="3" placeholder="Discount is Percent" />%</li>
              

        
        <li><label for="addSet" class="lableCss"></label>
            <input type="submit" name="addSet" id="addSet" value="Add" class="button-primary addBut"/></li>
    </ul>
</form
</div>



<script type="text/javascript">
function callGetcode() {
jQuery.ajax({
type	: "POST",
cache	: false,
url     : '<?php echo admin_url( 'admin-ajax.php' ) ?>',
dataType : 'json',
data: {
			'action' : 'getNewCoupon',
			
	  },
success: function(data) {
                        jQuery('#codeText').val(data);	
                       }
          });	
}  

function checkAddCoupon(form)
{
    var couponName = form.couponName.value.replace(/\s/g, "");
    var redeemDate = form.redeemDate.value.replace(/\s/g, "");
    var discount = form.discount.value.replace(/\s/g, "");
    var codeText = form.codeText.value.replace(/\s/g, "");
    var codeTextLength      =   codeText.length; 
    var errors = [];
    var intRegex = /^\d+$/;
    
      if(couponName == "")
         {
           errors[errors.length] = "Please enter a coupon Name.";   
         }
     if(codeText == "")
         {
           errors[errors.length] = "Please enter a coupon.";   
         }else{
            if(codeTextLength < 6)
                {
                    errors[errors.length] = "Coupon code should be at least 6 characters long.";   
                }
         }
         if(redeemDate == "")
             {
                  errors[errors.length] = "Please select last date to redreem coupon.";   
             }
       
         if(discount == "")
             {
                  errors[errors.length] = "Please enter a discount value.";   
             }else{
                    if(!intRegex.test(discount))
                    {
                      errors[errors.length] = "Please enter a numeric value in discount.";   
                    }
                    
                    if(discount >100)
                        {
                             errors[errors.length] = "Discount maximum value is 100.";   
                        }
                    
             }
       if (errors.length > 0) {
            reportErrors(errors);
            return false;
        }
}

function reportErrors(errors){
    var msg = "";
     var msg = "You forgot to fill out the following required info!\n\n";
  
    for (var i = 0; i<errors.length; i++) {
        var numError = i + 1;
       
        msg += "\n" + numError + ". " + errors[i];
    }
 
    alert(msg);
}

</script>