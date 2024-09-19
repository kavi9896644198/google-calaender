<?php
/**
 * Template for question list item.
 *
 * @link    https://anspress.net
 * @since   0.1
 * @license GPL 2+
 * @package AnsPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! ap_user_can_view_post( get_the_ID() ) ) {
	return;
}

$clearfix_class = array( 'ap-questions-item clearfix' );

?>
<div id="question-<?php the_ID(); ?>" <?php post_class( $clearfix_class ); ?> itemtype="https://schema.org/Question" itemscope="">
	<?php 
            /*---------For votes-----------*/
            $post_id= get_the_ID();
            global $wpdb;
            $q1="SELECT * FROM pik_ap_qameta WHERE post_id = '$post_id'";
            $q2="SELECT * FROM pik_term_relationships WHERE object_id = '$post_id'";    
            $r1 = $wpdb->get_results($q1, ARRAY_A);
            foreach($r1 as $result){
                $comments = $result['comments'];
                $answers = $result['answers'];
                $votes_up = $result['votes_up'];
                $votes_down = $result['votes_down'];
                $views = $result['views'];
                $subscribers = $result['subscribers'];
                $closed = $result['closed'];
                $last_updated = $result['last_updated'];
                $flags = $result['flags'];
               }
             $publish_date = get_the_date( $d = 'U', $post = $post_id );
             $human_time = human_time_diff($publish_date, current_time ('timestamp'));
                $category = get_the_terms($post_id, 'question_category' );
               $states = get_the_terms($post_id, 'question_state' );
               $cat_id = $category[0]->term_id;
                $badge_color = get_field('category_badge_color','question_category_'.$cat_id);
                $q_cat_name = $category[0]->name;               
                $q_cat_description = $category[0]->description;
                $state_id = $states[0]->id;
                 $q_state_name = $states[0]->name;
                 $q_state_description = $states[0]->description;
                 $author_id= get_post_field( 'post_author', $post_id );
                 $firstname = get_the_author_meta( 'first_name' );
                 $lastname = get_the_author_meta( 'last_name' );
                 $user_info = get_usermeta( $author_id, $meta_key = 'description' );
                               
            ?>
            <!-- Mobile -->
    <div class="show-only-mobile">
        <div class="show-only-mobile-left">
            <h6 itemprop="name">
                <a class="ap-questions-hyperlink" itemprop="url" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
            </h6>
        </div>
        <div class="show-only-mobile-right">
         
            <div class="scisco-question-meta">
                <span class="ap-display-meta-item categories" style="background-color:<?php
                if($badge_color): echo $badge_color; else: echo"#0F1C2E"; endif;?>">
                    <i class="apicon-tag"></i>
                    <span class="question-categories">
                        <a data-catid="<?php echo $cat_id; ?>" href="" title="<?php echo $q_cat_description ?>"><?php echo $q_cat_name; ?></a>
                    </span>
                </span>
                <span class="ap-display-meta-item ap-post-history">
                     Posted <?php echo $human_time; ?> ago
                </span>
                |
                <span class="ap-display-meta-item views">
                    <i class="apicon-eye"></i>
                    <?php echo $views ;?> views
                </span>
                
        </div>
        <div class="scisco-question-counts">
                <span class="ap-questions-count ap-questions-vcount">
                    <span itemprop="upvoteCount"><?php echo $votes_up - $votes_down; ?></span>
                Votes               </span>
                <a class="ap-questions-count ap-questions-acount noanswer" href="<?php the_permalink(); ?>/#answers">
                    <span itemprop="answerCount"><?php echo $answers; ?></span>
                Ans
                </a>
            </div> 
        </div>


    </div>
     <!-- Mobile ends-->
    <div class="scisco-question-wrapper desktop-only">
        <div class="scisco-question-avatar">
            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?></a>


        </div>
        <div class="scisco-question-title">
            <h6 itemprop="name">
                <a class="ap-questions-hyperlink" itemprop="url" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
            </h6>
            <div class="scisco-question-meta">
                <span class="ap-display-meta-item categories" style="background-color:<?php
                if($badge_color): echo $badge_color; else: echo"#0F1C2E"; endif;?>">
                    <i class="apicon-tag"></i>
                    <span class="question-categories">
                        <a data-catid="<?php echo $cat_id; ?>" href="" title="<?php echo $q_cat_description ?>"><?php echo $q_cat_name; ?></a>
                    </span>
                </span>
                <span class="ap-display-meta-item ap-post-history">
                     Posted <?php echo $human_time; ?> ago
                </span>
                <span class="pipe">|</span>
                <span class="ap-display-meta-item views">
                    <i class="apicon-eye"></i>
                    <?php echo $views ;?> views
                </span>
                <br>
                <?php if($firstname): ?>
                <span class="ap-display-meta-item history">
                     <strong><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
                            <span itemprop="name"><?php echo $firstname." ".$lastname;if($q_state_name):echo " (".$q_state_name.") ";endif;?> 
                        </span>
                    </a></strong>
            </span>
        <?php endif; ?>
            <br>
           <?php if($user_info): ?>
            <span class="ap-display-meta-item states">
                <span class="question-states">
                    <?php echo $user_info; ?>     
                    
                </span>
            </span>
        <?php endif; ?>  
        </div>
            </div>
            <div class="scisco-question-counts">
                <span class="ap-questions-count ap-questions-vcount">
                    <span itemprop="upvoteCount"><?php echo $votes_up - $votes_down; ?></span>
                Votes               </span>
                <a class="ap-questions-count ap-questions-acount noanswer" href="<?php the_permalink(); ?>/#answers">
                    <span itemprop="answerCount"><?php echo $answers; ?></span>
                Ans
                </a>
            </div>

<!--         <div class="card-footer">
<div class="card-avatar">

</div>
<div class="card-meta">
<div class="card-meta-author"><a href=""><?php the_author(); ?></a></div>
<div class="card-meta-date"><a href="<?php esc_url(the_permalink()); ?>"><?php the_time(get_option('date_format')); ?></a></div>
</div>
<div class="card-comments">
<a href="<?php the_permalink(); ?>/#scisco-comments-wrapper"><i class="fas fa-comments"></i> <?php echo esc_html(get_comments_number()); ?></a>
</div>
</div> -->
</div> 
</div><!-- list item -->
