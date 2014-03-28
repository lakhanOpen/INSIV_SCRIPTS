<?php get_header('white'); $_SESSION['nowuserid'] ='';?>
<div class="planPage">
 <div class="container_12 greylight1">
   <div class="planLeftmenu">
   <div class="grid_3"> 
   <div class="sideBlogbox">
   
   <h3>Blog Categories <span class="blueBdr"></span> </h3>
   <?php
   $categories = get_terms('blog_categories', array(
 	'orderby'    => 'count',
 	'hide_empty' => 1));
	
    if(!empty($categories)){
    echo '<ul>';
	foreach($categories as $cat):
	echo '<li><a href="'.get_term_link( $cat, 'blog_categories' ).'">'.$cat->name.'</a></li>';
    endforeach;
    echo '</ul>';
	}
?>  
<?php wp_reset_query(); ?>
    </div>
    <div class="sideBlogbox">
    <h3>Recently Published<span class="blueBdr"></span> </h3>
    <?php 
	$args = array(
    'numberposts' => 5,
    'orderby' => 'post_date',
    'order' => 'DESC',
    'post_type' => 'blog',
    'post_status' => 'publish');

    $recent_posts = wp_get_recent_posts( $args, ARRAY_A );
	if(!empty($recent_posts)){
	echo '<ul>';
	foreach($recent_posts as $rpost){
	echo '<li><a href="'.get_permalink($rpost['ID']).'">'.$rpost['post_title'].'</a></li>';
	}
	echo '</ul>';
	}
	
	?>
<?php wp_reset_query(); ?>
    </div>
    </div>
   </div>
   
    <div class="planRightpost">
     <div class="grid_8">
     <?php  global $wp_query;
			$wp_query = new WP_Query("post_type=blog&post_status=publish&posts_per_page=10&orderby=title&order=ASC");
			while ($wp_query->have_posts()) : $wp_query->the_post(); 
			$cat=wp_get_post_terms(get_the_ID(),'blog_categories',array("fields"=>"names")); ?>
      <div class="planPost blogPostbox"><h4><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h4>
      <div class="blogMeta">Published on <span><?php the_time('d M, Y') ?></span>  in <?php echo @implode(',',$cat);  ?></div>
      <div class="blogPost"><?php the_excerpt(); ?></div> 
      </div>
      <?php endwhile; ?><?php wp_reset_query(); ?>
     </div>
    </div>
   
 </div>
 <div class="clear"></div>
   </div>
<?php get_footer(); ?>
