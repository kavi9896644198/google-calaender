<?php
/**
 * This file is responsible for displaying question page
 * This file can be overridden by creating a anspress directory in active theme folder.
 *
 * @package    AnsPress
 * @subpackage Templates
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
 * @author     Rahul Aryan <support@anspress.io>
 *
 * @since      0.0.1
 * @since      4.1.0 Renamed file from question.php.
 * @since      4.1.2 Removed @see ap_recent_post_activity().
 * @since      4.1.5 Fixed date grammar when post is not published.
 */

?>
<div id="ap-single" class="ap-q clearfix " itemscope itemtype="https://schema.org/QAPage">
	<?php $post_id= get_the_ID();
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

	<div class="ap-question-lr row" itemprop="mainEntity" itemtype="https://schema.org/Question" itemscope="">
		
		<div class="ap-q-left col-12 <?php echo ( is_active_sidebar( 'ap-qsidebar' ) ) ? 'col-xl-9' : 'col-xl-12'; ?>">
			<?php do_action( 'ap_before_question_meta' ); ?>
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
			<?php do_action( 'ap_after_question_meta' ); ?>
			<div ap="question" apid="<?php the_ID(); ?>">
				<div id="question" role="main" class="ap-content">
					<div class="scisco-sq">
						<div class="scisco-sq-avatar">
							<?php do_action( 'ap_before_question_title' ); ?>
							<?php $scisco_page_slug = get_theme_mod('scisco_ap_about_slug', 'about'); ?>
							<a href="<?php echo ap_user_link(); ?>">
								<?php ap_author_avatar( ap_opt( 'avatar_size_qquestion' ) ); ?>
							</a>
						</div>
						<div class="scisco-sq-content-wrapper">
							
							<div class="scisco-sq-metas">
								<div class="scisco-sq-metas-inner">
									<div class="scisco-sq-metas-inner-left">
										<div class="mobile-avatar" style="display:none">
											<a href="<?php echo esc_url(ap_get_profile_link() . $scisco_page_slug); ?>/"><?php ap_author_avatar( ap_opt( 'avatar_size_qquestion' ) ); ?>
											</a>
										</div>
									</div>
									<div class="scisco-sq-metas-inner-right">
										
									
								<div class="scisco-sq-metas-left">
									<span class="ap-author" itemprop="author" itemscope itemtype="http://schema.org/Person"><a href="<?php echo ap_user_link(); ?>" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
                            <span itemprop="name"><?php echo $firstname." ".$lastname;?> 
                            
                        </span>
                    </a>
										<a href="<?php echo ap_user_link()."reputations"; ?>" class="ap-user-reputation" title="Reputation"><?php ap_get_user_reputation_meta($author_id); ?></a>
									</span>
									<a href="<?php the_permalink(); ?>" class="ap-posted">
										<span class="ap-display-meta-item ap-post-history">
                     				 <?php echo $human_time; ?> ago
               						 </span>
									</a>
									<div class="scisco-sq-metas-comment-count">
										<?php $comment_count = get_comments_number(); ?>
										<?php printf( _n( '%s Comment', '%s Comments', $comment_count, 'scisco' ), '<i class="fas fa-comment"></i><span itemprop="commentCount">' . (int) $comment_count . '</span>' ); ?>
									</div>
									
								</div>
								<div class="scisco-sq-desc">
									<?php if($user_info): ?>
            <span class="ap-display-meta-item states">
                <span class="question-states">
                    <?php echo $user_info; ?>     
                    
                </span>
            </span>
        <?php endif; ?>
								</div>

								<div class="scisco-sq-metas-right">
									
									<?php ap_post_actions_buttons(); ?>
									<?php do_action( 'ap_post_footer' ); ?>
								</div>
							</div></div>
							</div>
							<div class="scisco-sq-content" itemprop="text">
								<?php do_action( 'ap_before_question_content' ); ?>
								<h1 class="d-none" itemprop="name"><?php the_title(); ?></h1>
								<?php the_content(); ?>
								<?php do_action( 'ap_after_question_content' ); ?>
							</div>
							<div class="scisco-sq-comments">
								<?php ap_post_comments(); ?>
							</div>
						</div>
						<div class="scisco-sq-vote"><?php ap_vote_btn(); ?></div>
					</div>
				</div>
			</div>
			<?php
				/**
				 * Action triggered before answers.
				 *
				 * @since   4.1.8
				 */
				do_action( 'ap_before_answers' );
			?>

			<?php
				// Get answers.
				ap_answers();

				// Get answer form.
				ap_get_template_part( 'answer-form' );
			?>
		</div>
		<?php if ( is_active_sidebar( 'ap-qsidebar' ) ) { ?>
		<aside class="col-12 col-xl-3 mt-5 mt-xl-0">
			<div class="ap-question-info">
				<?php dynamic_sidebar( 'ap-qsidebar' ); ?>
			</div>
		</aside>
		<?php } ?>

	</div>
</div>
