<?php
//Our class extends the WP_List_Table class, so we need to make sure that it's there


class Coupon_Generator_List_Table extends WP_List_Table {

	/**
	 * Constructor, we override the parent to pass our own arguments
	 * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
	 */
    var $tableName =  "coupon_generator"; 
	 function __construct() {
		 parent::__construct( array(
		'singular'=> $this->tableName, //Singular label
		'plural' => $this->tableName.'s', //plural label, also this well be one of the table css class
		'ajax'	=> false //We won't support Ajax for this table
		) );
	 }

	/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ){
			//The code that goes before the table is here
			echo"";
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
			//echo"Hi, I'm after the table";
		}
	}
 
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'col_id'=>__('#'),
                        'col_couponName'=>__('Name'),
			'col_coupontext'=>__('Code'),
			'col_redeemDate'=>__('Date'),
			'col_discount'=>__('Discount'),
			'col_status'=>__('Status'),
                        'col_action'=>__('Action')
		);
	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		return $sortable = array(
			'col_couponName'=>array('couponName'),
                        'col_coupontext'=>array('coupontext'),
                        'col_redeemDate'=>array('redeemDate'),
			'col_discount'=>array('discount'),
            'col_status'=>array('status')
		);
	}

        function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }
  
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		/* -- Preparing your query -- */
	    $query = "SELECT * FROM ".$wpdb->prefix.$this->tableName;

              $search = !empty($_GET["s"]) ? mysql_real_escape_string($_GET["s"]) : '';
              
            $where = "";
              if(!empty($search)){               
                     $where = " WHERE (couponName LIKE '%{$search}%' OR coupontext LIKE '%{$search}%'OR discount LIKE '%{$search}%')";
              }
              
              $query .=$where;
		/* -- Ordering parameters -- */
	    //Parameters that are going to be used to order the result
	    $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
	    $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
	    if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

		/* -- Pagination parameters -- */
        //Number of elements in your table?
       
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 10;
        //Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        //Page Number
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        //How many pages do we have in total?
        $totalpages = ceil($totalitems/$perpage);
        //adjust the query to take pagination into account
		if(!empty($paged) && !empty($perpage)){
			$offset=($paged-1)*$perpage;
	    	$query.=' LIMIT '.(int)$offset.','.(int)$perpage;
		}

		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page" => $perpage,
		) );
		//The pagination links are automatically built according to those parameters
		
		/* — Register the Columns — */
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		/* -- Fetch the items -- */
		$this->items = $wpdb->get_results($query);
	}

	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display_rows() {
		//Get the records registered in the prepare_items method
		$records = $this->items;

		//Get the columns registered in the get_columns and get_sortable_columns methods
		list( $columns, $hidden ) = $this->get_column_info();

		//Loop for each record
                $as =1;$currentDate = date("Y-m-d");
		if(!empty($records)){foreach($records as $rec){
                    $addClass='';
                              if($as % 2 != 0){ $addClass = 'class="alternate"';}
			//Open the line
	        echo '<tr id="record_'.$rec->id.'" '. $addClass.'>';
			foreach ( $columns as $column_name => $column_display_name ) {

				//Style attributes for each col
				$class = "class='$column_name column-$column_name'";
				$style = "";
				if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
				$attributes = $class . $style;

				//edit link
				//$editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$rec->id;

				//Display the cell
				switch ( $column_name ) {
					case "col_id":	echo '<td '.$attributes.'>'.$as.'</td>';	break;
                                        case "col_couponName": echo '<td '.$attributes.'>'.$rec->couponName.'</td>'; break;
					case "col_coupontext": echo '<td '.$attributes.'>'.$rec->coupontext.'</td>'; break;
					case "col_redeemDate": echo '<td '.$attributes.'>'.$rec->redeemDate.'</td>'; break;
					case "col_discount": echo '<td '.$attributes.'>'.$rec->discount.'</td>'; break;
                                        case "col_status":  $codeStatus ='';                                                             
                                                            if($rec->status == '1'){ $codeStatus ='active';}else{$codeStatus ='deactivate';}
                                                            $attributes = "class='$column_name column-$column_name col_$codeStatus'";
                                                            
                                                            echo '<td '.$attributes.'>'.ucfirst($codeStatus).'</td>';
                                                            break;
                                        case "col_action":
                                                            
                                                             if($rec->status == '1'){ $codeStatus ='deactivate';}else{$codeStatus ='active';}
                                                          echo '<td '.$attributes.'><a href="'.WP_SITEURL.'/wp-admin/admin.php?page=callcouponGenerator&&action=delete&codeId='.$rec->id.'">Delete</a>';
                                                          if($rec->redeemDate > $currentDate ){
                                                          echo'| <a href="javascript:void(0);" onclick="return callActionCoupon(\''.$codeStatus.'\','.$rec->id.');">'.ucfirst($codeStatus).'</a>';}                           
                                                          echo '</td>';
                                                            
                                                        
                                                        break;
				}
			}

			//Close the line
			echo'</tr>';
                        $as++;
		}}
	}
}
$msg = '';
global $wpdb;
if($_GET['action'] == 'delete')
{    
    if((int)$_GET['codeId'])
    {
   
     $cid = $_GET['codeId'];
    $selectsql = "SELECT * FROM ".$wpdb->prefix . "coupon_generator WHERE id = $cid";
        $codearray = $wpdb->get_results($selectsql) ;
      if(!empty($codearray)){               
          try {
         
                    $coupon = Recurly_Coupon::get($codearray[0]->coupontext);
                    $coupon->delete();

                    $dsql=  "DELETE FROM ".$wpdb->prefix . "coupon_generator WHERE id = $cid";
                    if($wpdb->query($dsql)){
                    $msg['text']='Coupan successfully delete.';
                    $msg['class'] ='updated';
                    }
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
         }
    }
}
elseif($_GET['action'] == 'active'){
    
    if((int)$_GET['codeId'])
    {
  
     $cid = $_GET['codeId'];
      
        $updateSql = "UPDATE ".$wpdb->prefix ."coupon_generator SET status = '1' WHERE id =$cid;";
        if($wpdb->query($updateSql)){
        $msg['text']='Coupon successfully Active.';
        $msg['class'] ='updated';
        }
    }
}
elseif($_GET['action'] == 'deactivate'){
    
    if((int)$_GET['codeId'])
    {
      
     $cid = $_GET['codeId'];
      
        $updateSql = "UPDATE ".$wpdb->prefix ."coupon_generator SET status = '0' WHERE id =$cid;";
        if($wpdb->query($updateSql)){
        $msg['text']='Coupon successfully Deactivate.';
        $msg['class'] ='updated';
        }
    }
}
$currentDate = date("Y-m-d");
$changeCoupanStatus = "UPDATE ".$wpdb->prefix ."coupon_generator SET status = '0'  WHERE redeemDate <=  '".$currentDate."' ";
$wpdb->query($changeCoupanStatus);
//Prepare Table of elements
$wp_list_table = new Coupon_Generator_List_Table();
$wp_list_table->prepare_items();
?>
<style type="text/css">
    .col_deactivate{color:#BF0000 !important;}
    .col_active{color:#70AE39 !important; }
    
</style>

<div class="wrap" ><h2>Coupon Generator Manager <a href="<?php echo WP_SITEURL.'/wp-admin/admin.php?page=callAddCoupon';?>" class="add-new-h2">Add Code</a></h2> 
   <?php if(!empty($msg)){ ?>
    <div id="mssage" class="<?php echo $msg['class']; ?>"><?php echo $msg['text']; ?></div>
    <?php }?>
    
    <form method="get" id="couponSearch" name="couponSearch">
    <input type="hidden" name="page" value="callcouponGenerator" />
    <?php if(!empty($orderby)){ ?>
    <input type="hidden" name="orderby" value="<?php echo $orderby ;?>" />
    <?php } if(!empty($order)) {?>
    <input type="hidden" name="order" value="<?php echo $order; ?>" />
    <?php } if(!empty($paged)){ ?>    
    <input type="hidden" name="paged" value="<?php echo $paged; ?>" />
    <?php }?>

       
    <?php $wp_list_table->search_box('search', 'search_id'); ?>
</form>
                <?php    $wp_list_table->display();     ?>
     </div>

<script type="text/javascript">

function callActionCoupon(action,codeId)
{

   var addaction = '<div><input type="hidden" name="action" value="'+action+'" /><input type="hidden" name="codeId" value="'+codeId+'" /></div>';

    jQuery('#couponSearch').append(addaction);
    
    jQuery("#couponSearch").submit();
}
</script>
            
