<?php
require_once 'wp-load.php';

global $wpdb;
$currentDate = date("Y-m-d");
$sql = "SELECT * FROM ".$wpdb->prefix ."plan_payment_history where status = 1 and is_shipping = 1";
$queryArray = $wpdb->get_results($sql);  

$planArray = array();
$as = 1;
foreach($queryArray as $row)
{
   $planID      = $row->order_Id;
   $userID      = $row->user_Id;
   $payAmount   = $row->payAmount;
   
   $sql = "SELECT * FROM ".$wpdb->prefix ."plan_order where id =$planID  ";
   $orderArray= $wpdb->get_results($sql);    
  
   $planCode = str_replace('-', "", $orderArray[0]->plan_code);

   $planArray[$as]['history']['orderID']        = '<![CDATA['.$row->id.']]>';
   $planArray[$as]['history']['OrderNumber']    = '<![CDATA[insiv'.$planCode.$row->id.']]>';
   $planArray[$as]['history']['OrderDate']      = date("m/d/Y H:i",$row->add_date);
   $planArray[$as]['history']['OrderStatus']    = '<![CDATA[paid]]>';
   $planArray[$as]['history']['LastModified']   = date("m/d/Y H:i",$row->add_date);
   
   $plan_codeArray                      = explode("-", $orderArray[0]->plan_code);  
   $planSlug                            =  $plan_codeArray[0].'-'.$plan_codeArray[1];
   $getPlanArray                        = get_term_by('slug', $planSlug, 'plan'); 
   $planArray[$as]['PaymentMethod']     = '<![CDATA[Credit Card]]>';
   $planArray[$as]['OrderTotal']        = $payAmount;
   $planArray[$as]['ShippingAmount']    = '0.00';
   
   $user_info                                   = get_userdata( $userID );
   $all_meta_for_user                           = get_user_meta( $userID );
   $planArray[$as]['customer']['CustomerCode']  =  $userID;
   $planArray[$as]['customer']['email']         =  $user_info->user_email;
   $firstName = (!empty($all_meta_for_user['first_name'][0])) ? $all_meta_for_user['first_name'][0] : 'User';
   $lastName = (!empty($all_meta_for_user['last_name'][0])) ? $all_meta_for_user['last_name'][0] : '';
   
   $planArray[$as]['customer']['name'] = $firstName.' '.$lastName;
 
   $planArray[$as]['item']['SKU']='<![CDATA['.$orderArray[0]->plan_code.']]>';
   $planArray[$as]['item']['Name']='<![CDATA['.ucfirst($getPlanArray->name).' '.$plan_codeArray[2].']]>';
   $planArray[$as]['item']['Quantity']='1';
   $planArray[$as]['item']['UnitPrice']=$payAmount;
   
   

   
    
       $shipSql          = "SELECT * FROM ".$wpdb->prefix ."plan_order_address where id =$row->shipping_address_id";
       $shipAddressArray = $wpdb->get_results($shipSql);  
       $shipArray = unserialize($shipAddressArray[0]->value);
   
       $shipName = $shipArray['shippingFirstName'].' '.$shipArray['shippingLastName'];
       $planArray[$as]['ShipTo']['Name']        ='<![CDATA['.$shipName.']]>';
       $planArray[$as]['ShipTo']['Address1']    ='<![CDATA['.$shipArray['shippingAddress'].']]>';
       $planArray[$as]['ShipTo']['Address2']    ='<![CDATA['.$shipArray['shippingAptSuite'].']]>';
       $planArray[$as]['ShipTo']['City']        ='<![CDATA['.$shipArray['shippingCity'].']]>';
       $planArray[$as]['ShipTo']['PostalCode']  ='<![CDATA['.$shipArray['shippingZipCode'].']]>';
       
       $stateArray = getStateCode($shipArray['shippingState']);
       
       $stateCode                     = explode("-", $stateArray->ISO_Code); 
       $planArray[$as]['ShipTo']['State']  ='<![CDATA['.$stateCode[1].']]>';
       
       $countryArray = getCountryCode($shipArray['shippingCountry']);
       $planArray[$as]['ShipTo']['Country']  ='<![CDATA['.$countryArray->Short_Name.']]>';
           
   $as++;
}

header('Content-type: application/xml');

?>
<Orders>
    
    <?php foreach($planArray as $row){ ?>
    <Order>
        <OrderID><?php echo $row['history']['orderID']; ?></OrderID>
        <OrderNumber><?php echo $row['history']['OrderNumber']; ?></OrderNumber>
        <OrderDate><?php echo $row['history']['OrderDate']; ?></OrderDate>
        <OrderStatus><?php echo $row['history']['OrderStatus']; ?></OrderStatus>
        <LastModified><?php echo $row['history']['LastModified']; ?> </LastModified>
       
        <PaymentMethod><?php echo $row['PaymentMethod']; ?></PaymentMethod>
        <OrderTotal><?php echo $row['OrderTotal']; ?></OrderTotal>       
        <ShippingAmount><?php echo $row['ShippingAmount']; ?></ShippingAmount>
      <Customer>
            <CustomerCode><?php echo $row['customer']['CustomerCode']; ?></CustomerCode>
            <BillTo>
                <Name><?php echo $row['customer']['name']; ?></Name>              
                <Email><?php echo $row['customer']['email']; ?></Email>                
            </BillTo>
            <ShipTo>
                <Name><?php echo $row['ShipTo']['Name']; ?></Name>
                 <Address1><?php echo $row['ShipTo']['Address1']; ?></Address1>
                 <Address2><?php echo $row['ShipTo']['Address2']; ?></Address2>
                 <City><?php echo $row['ShipTo']['City']; ?></City>
                 <State><?php echo $row['ShipTo']['State']; ?></State>
                 <PostalCode><?php echo $row['ShipTo']['PostalCode']; ?></PostalCode>
                 <Country><?php echo $row['ShipTo']['Country']; ?></Country>
                
            </ShipTo>
        </Customer>
        <Items>
            <Item>
                <SKU><?php echo $row['item']['SKU']; ?></SKU>
                <Name><?php echo $row['item']['Name']; ?></Name>        
                <Quantity><?php echo $row['item']['Quantity']; ?></Quantity>
                <UnitPrice><?php echo $row['item']['UnitPrice']; ?></UnitPrice>                
            </Item>
        </Items>
    </Order>
    <?php }?>
    
</Orders>

<?php 
foreach($queryArray as $row)
{
    $history  = "UPDATE ".$wpdb->prefix ."plan_payment_history SET is_shipping=0 WHERE id =$row->id ";
    $wpdb->query($history);
}

?>