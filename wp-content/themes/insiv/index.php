<?php  /*  Template name: Home page  */?>
    <?php get_header();$_SESSION['nowuserid'] ='';?>
<div class="sliderBg">
    <div class="container_12">
        <div class="slideTxt">  
                    <?php 
        
$args = array(
	'posts_per_page'   => 1,
	'offset'           => 0,
	'category'         => '21',
	'orderby'          => 'post_date',
	'order'            => 'DESC',	
	'post_type'        => 'post',	
	'post_status'      => 'publish',
	'suppress_filters' => true ); 
$myposts = get_posts( $args );
        if(!empty($myposts)){
        ?>
        <div class="h2Txt"><?php echo $myposts['0']->post_title; ?></div>
        <div class="slidePtxt"><?php echo $myposts['0']->post_content; ?></div>
        <?php wp_reset_postdata(); } ?>
             <a href="<?php echo get_permalink( 24 ); ?>" class="grayBtn1">Get Started</a> 
        </div>                             		   
        <!--<div class="controls">
        <a href="#" class="left">&lt;</a>
        <a href="#" class="right">&gt;</a>
        </div>-->
        <div class="slide-nav"></div>
        <div class="clearfix"></div>
        
    </div>
    
</div>
<div class="aboutInsiv"> 
    <div class="container_12"> 
        <?php 
        
$args = array(
	'posts_per_page'   => 1,
	'offset'           => 0,
	'category'         => '9',
	'orderby'          => 'post_date',
	'order'            => 'DESC',	
	'post_type'        => 'post',	
	'post_status'      => 'publish',
	'suppress_filters' => true ); 
$myposts = get_posts( $args );
        if(!empty($myposts)){
        ?>
        <div class="grid_7 prefix_2"> 
            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/insivStamp.png"> 
            <h6><?php echo $myposts['0']->post_title; ?> </h6> 
            <p><?php echo $myposts['0']->post_content; ?></p>
        
           
        </div>    
        <?php wp_reset_postdata(); } ?>
    </div>  
</div>
<div class="howWork">  
    <div class="container_12">   
        <h2>How it works <span class="blueBdr"></span></h2> 
        
                <?php 
        
$args = array(
	'posts_per_page'   => 3,
	'offset'           => 0,
	'category'         => 10,
	'orderby'          => 'post_date',
	'order'            => 'ASC',	
	'post_type'        => 'post',	
	'post_status'      => 'publish',
	'suppress_filters' => true ); 
$myposts = get_posts( $args );

    if(!empty($myposts)){
        ?>
        <div class="itworkBlog">     
            <div class="grid_4 omega"> 
                <div class="workpic"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/chosePlane.png"></div> 
                <div class="blogBDR fRight"> 
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/images/chosePlaneBDR.png" class="HmobiBdrNone">
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/images/pick-strengthBDR.png" class="HmobiBdr"> 
                </div>   
                <div class="clear"></div>  
                <div class="worktext">
                    <div class="worksPost_title"><?php echo $myposts['0']->post_title; ?></div>
                    <div class="worksPost_content"><?php echo $myposts['0']->post_content; ?></div> 
                </div> 
            </div>  
        </div>    
        <div class="itworkBlog">  
            <div class="grid_4 alpha omega">  
                <div class="workpic"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/pick-strength.png"></div>
                <div class="blogBDR textCenter"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/pick-strengthBDR.png"></div>   
                <div class="clear"></div>   
                <div class="worktext">
                  <div class="worksPost_title"><?php echo $myposts['1']->post_title; ?></div> 
                    <div class="worksPost_content"><?php echo $myposts['1']->post_content; ?></div>
                </div>  
            </div>
        </div>   
        <div class="itworkBlog">  
            <div class="grid_4 alpha">   
                <div class="workpic"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/Open-Enjoy.png"></div>
                <div class="blogBDR fLeft">
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/images/Open-EnjoyBDR.png" class="HmobiBdrNone">
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/images/pick-strengthBDR.png" class="HmobiBdr">
                </div>
                <div class="clear"></div> 
                <div class="worktext">
                  <div class="worksPost_title"><?php echo $myposts['2']->post_title; ?></div>
                   <div class="worksPost_content"><?php echo $myposts['2']->post_content; ?></div>
                </div>
            </div> 
        </div>
        
    <?php wp_reset_postdata(); }?>
    </div>  
</div>
<div class="feturedHome">  
    <div class="container_12"> 
        <h2>Featured Brands<span class="whiteBdr"></span></h2>
        <ul>    
            <?php
            $loop = new WP_Query( array( 'post_type' => 'brand','orderby' => 'ID', 'order' => 'ASC') );	
            while ( $loop->have_posts() ) : $loop->the_post();	
            echo '<li>';
            the_post_thumbnail('thumbnail');
            ?><?php
            echo '</li>';
            endwhile;     
            ?>   
        </ul>  
    </div>  
</div>
<div class="startedBtnlink">  
    <div class="container_12">  
        <div class="startedBtn">
            <a class="blueBtnBig" href="<?php echo get_permalink( 24 ); ?>">Get Started<span class="whiteBdr"></span></a>
        </div> 
    </div> 
</div>
<?php get_footer(); ?>