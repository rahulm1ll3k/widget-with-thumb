<?php
/**
 * Plugin Name: Xpert Thumbnail Recent Post
 * Plugin URI: https://metabox.io
 * Description: A simple plugin for custom recent post widget with thumbnail and category features.
 * Version: 1.0
 * Author: Meta Box
 * Author URI: https://metabox.io
 * License: GPL2
 */

/*
Things we need to do in next:

1. Create a meta box
2. Add custom fields to that meta box
3. Save the values of custom fields
4. Display the value of each custom field in the front end
*/



add_action( 'after_setup_theme', 'dope_reg_widget' );
function dope_reg_widget(){

    register_widget('theme_recent_category_widget');

}



// custom functions for widgets section
class theme_recent_category_widget extends WP_Widget {
    public function __construct(){
        parent::__construct('theme_category', 'Recent Post With Thumbnail', array(
            'description' => 'Recent Posts with custom query and thumbnail options.', 
        ));
    }
    public function widget($one, $two){ 
        echo $one['before_widget'];
            echo $one['before_title'];
                echo __($two['title']);
            echo $one['after_title'];
            //recent-post-widget
        ?>
            <ul>
                <?php 
                $args = array('post_type' => 'post');
                $args['category__in'] = $two['category'];
                $args['posts_per_page'] = $two['count'];
                $args['offset'] = $two['offset'];
                $query = new WP_Query( $args );
                while($query->have_posts()): $query->the_post();  ?>

                    <?php if($two['layout'] == '1'){ ?>
                        <li class="single-item with-thumbnail">
                            <div class="thumbs">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('full', array('class'=>'img-fluid')); ?>
                                </a>
                            </div>
                            <div class="details">
                                <h5><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h5>
                            </div>
							<p class="meta">
								<span class="date"><?php the_date('M d, Y') ?></span>
								<span class="name"><?php echo get_the_author(); ?></span>
							</p>
                        </li>
                    <?php }else if($two['layout'] == '3'){ ?>
                        <li class="single-item large-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('full', array('class'=>'img-fluid')); ?>
                            </a>
                            <div class="content">
                                <h5><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h5>
                                <p class="meta">
									<span class="date"><?php the_date('M d, Y') ?></span>
                                    <span class="name"><?php echo get_the_author(); ?></span>
                                </p>
                            </div>
                        </li>
                    <?php }else{ ?>
                    <?php } ?>

                <?php endwhile; ?>
            </ul>
        <?php
        echo $one['after_widget'];
    }
    public function form($one){ 
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" class="widefat" value="<?php echo $one['title'] ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Number of Post to show: </label>
            <input type="number" name="<?php echo $this->get_field_name('count'); ?>" id="<?php echo $this->get_field_id('count'); ?>" class="widefat" value="<?php echo $one['count'] ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('offset'); ?>">Offset Post: </label>
            <input type="number" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" class="widefat" value="<?php echo $one['offset'] ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>">Select Post Category: </label>
            <select class="widefat" name="<?php echo $this->get_field_name('category'); ?>" value="<?php echo $one['category'] ?>">
            	<option value="">All</option>
                <?php 
                foreach(get_categories() as $item){
                    if ($one['category'] == $item->term_id) {
                        echo '<option value="'.$item->term_id.'" selected>'.$item->name.'</option>';
                    }else{
                        echo '<option value="'.$item->term_id.'">'.$item->name.'</option>';
                    }
                } 
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('layout'); ?>">Post Layout Type: </label>
            <select class="widefat" name="<?php echo $this->get_field_name('layout'); ?>" value="<?php echo $one['layout'] ?>">
                <option value="1" <?php echo ($one['layout'] == '1')?'selected':''; ?>>List With Thumbnail</option>
                <option value="3" <?php echo ($one['layout'] == '3')?'selected':''; ?>>Large Thumbnail</option>
            </select>
        </p>
        <?php
    }
}

function xpert_widget_with_thumb_function() {   
    wp_enqueue_style( 'widget-with-thumb_style', plugin_dir_url( __FILE__ ) . 'widget-with-thumb.css' );
}
add_action('wp_enqueue_scripts', 'xpert_widget_with_thumb_function');

