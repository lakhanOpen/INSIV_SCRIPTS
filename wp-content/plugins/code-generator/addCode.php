<?php 
global $wpdb;
$msg='';

if(isset($_POST['addSet'])) {
    
    if(!empty($_POST['codeText']))
    {
        
        extract($_POST);
        $adddate = time();
        $codeText = esc_sql(trim($codeText));
        $selectsql = "SELECT id FROM ".$wpdb->prefix . "code_generator where codetext = '".$codeText."' and status='1'  LIMIT 1";
        $codearray = $wpdb->get_results($selectsql) ;
        if(empty($codearray)){
       $sql = "INSERT INTO ".$wpdb->prefix . "code_generator (codetext,uselimit,status,adddate) 
					VALUES('".$codeText."','".esc_sql(trim($uselimit))."','1','".esc_sql($adddate)."')";
    
             $insrtCode = $wpdb->query($sql);
             
              $msg['text']='Code successfully add.';
              $msg['class'] ='updated';
        }  
        else {
                $msg['text']='Code already exists.';
                $msg['class'] ='error';
             }
    }else{
        $msg['text']='Please fill all detail.';
        $msg['class'] ='error';
    }
  
}

?>
<style type="text/css">
    
    .lableCss{ margin-right: 10px; width:100px; display: inline-block; text-align: left;  }
    
    
</style>

<div class="wrap"><h2>Add Code Generator</h2>
    
    <?php if(!empty($msg)){ ?>
    <div id="mssage" class="<?php echo $msg['class']; ?>"><?php echo $msg['text']; ?></div>
    <?php }?>

    
    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="add_Code_set" name="add_Code_set" onsubmit="return checkAddCode(this);" >
    <ul>
        <li><label for="codeText" class="lableCss">Code<span> *</span>: </label>
        <input  type="text" name="codeText"  id="codeText" size="50" maxlength="50" value="" />
        <span class="button-secondary" onclick="callGetcode();">Get code</span>
        </li>    
        
             
        <li><label for="uselimit" class="lableCss">Max Use<span> *</span>: </label>
            <input type="text" value="50" name="uselimit" id="uselimit" size="50" maxlength="50" /></li>
        
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
			'action' : 'getNewCode',
			
	  },
success: function(data) {
                        jQuery('#codeText').val(data);	
                       }
          });	
}  

function checkAddCode(form)
{
    var uselimit = form.uselimit.value.replace(/\s/g, "");
    var codeText = form.codeText.value.replace(/\s/g, "");
    var codeTextLength      =   codeText.length; 
    var errors = [];
    var intRegex = /^\d+$/;
    
     if(codeText == "")
         {
           errors[errors.length] = "Please enter a code.";   
         }
       if(codeTextLength < 12)
           {
               errors[errors.length] = "Code should be at least 12 characters long.";   
           }
         if(uselimit == "")
         {
           errors[errors.length] = "Please enter a code use limit.";   
         }
         
          if(!intRegex.test(uselimit))
         {
           errors[errors.length] = "Please enter a numeric value.";   
         }else if(uselimit == 0)
             {
                  errors[errors.length] = "Please enter a value greater than zero.";  
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