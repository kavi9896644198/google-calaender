<?php


$verified_filter = array();
    $verified_filter = array(
        'meta_query' => array(
            array(
                'key' => 'scisco_verified_user',
                'value' => 'yes'
            )
        )
    );
    if (get_query_var('qa_orderby')) {
    $scisco_orderby = get_query_var('qa_orderby');

switch ($scisco_orderby) {
    case 'registration_date_asc':
        $user_query = array(
            'orderby'    => 'user_registered',
            'order'      => 'ASC',
            'exclude' => $exclude,
            'number'     => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'registration_date_desc':
        $user_query = array(
            'orderby'    => 'user_registered',
            'order'      => 'DESC',
            'exclude' => $exclude,
            'number'     => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'alphabetical_asc':
        $user_query = array(
            'orderby'    => 'title',
            'order'      => 'ASC',
            'exclude' => $exclude,
            'number'     => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'alphabetical_desc':
        $user_query = array(
            'orderby'    => 'title',
            'order'      => 'DESC',
            'exclude' => $exclude,
            'number'     => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'reputation_asc':
        $user_query = array(
            'orderby'    => 'meta_value_num',
            'meta_key'   => 'ap_reputations',
            'order'      => 'ASC',
            'exclude' => $exclude,
            'number'     => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'reputation_desc':
        $user_query = array(
            'orderby'    => 'meta_value_num',
            'meta_key'   => 'ap_reputations',
            'order'      => 'ASC',
            'exclude' => $exclude,
            'number'     => $scisco_limit,
            'offset'     => $offset
        );
    break;
}
}else{
    $user_query = array();
    
}

$final_query = new WP_User_Query( $verified_filter + $user_query);
$total_query = count($final_query->get_results());
?>
<?php if(!(isset($_GET['qa_user'])) || $_GET['qa_user'] == ""){
 if ($final_query->get_results()) { 
   ?>
<div class="scisco-users-wrapper verified-user">
<h3 class="users-title">Featured Users</h3>
<?php 
foreach ($final_query->get_results() as $user) {

?>
    <div class="scisco-question-wrapper">
		<div class="scisco-question-list">
            <div class="scisco-question-avatar <?php echo $user->ID ?>">
                <?php $scisco_page_slug = get_theme_mod('scisco_ap_about_slug', 'about'); ?>
                <?php if ( $da_avatar = get_the_author_meta( 'scisco_cmb2_user_avatar', $user->ID ) ) : ?>
                   <a href="<?php echo esc_url(ap_user_link($user->ID) . $scisco_page_slug); ?>/">
                        <?php echo get_avatar( $user->ID, ap_opt( 'avatar_size_list' ) ); ?>
                    </a>
                <?php else : 
                        $uploads = wp_upload_dir();
                        $upload_path = $uploads['baseurl']; 
                        if($user->scisco_cmb2_gender =='male'){
                            $profileImage = $upload_path."/2020/12/logo3.png" ;
                        }else {
                             $profileImage = $upload_path."/2020/12/logo6.png";
                        }
                    ?>
                   <a href="<?php echo esc_url(ap_user_link($user->ID) . $scisco_page_slug); ?>/">                       
                        <img class="avatar lazyloaded" data-src="<?php echo $profileImage ?>" src="<?php echo $profileImage ?>" width="45" height="45" alt="">
                    </a>
                <?php endif; ?>
               
            </div>

            <div class="scisco-question-title">
                <h6>
                    <a href="<?php echo esc_url(ap_user_link($user->ID) . $scisco_page_slug); ?>/">
                        <?php echo esc_html($user->user_nicename); ?>
                    </a>
                    <?php $reputation_count = ap_get_user_reputation_meta( $user->ID, true ); ?>
                    <span class="ap-user-reputation"><?php echo esc_html($reputation_count); ?></span>
                </h6>
                <span><?php echo esc_html($user->description); ?></span>
                <!-- <p>
                    <?php if(!empty($user->scisco_cmb2_user_type)){ ?>
                        <?=$user->scisco_cmb2_user_type?>, <?=$user->scisco_cmb2_user_stage?>
                   <?php } ?>
                   </p> -->
                    <?php $scisco_cmb2_user_skills = $user->scisco_cmb2_user_skills;                   
                    if(!empty($scisco_cmb2_user_skills)){ ?>
                <span class="scisco-cat-list">                          
                    <?php    
                    $scisco_cmb2_user_skills_arr = explode(",",$scisco_cmb2_user_skills);     
                    foreach($scisco_cmb2_user_skills_arr as $user_skills){
                                echo '<a href="">'.$user_skills.'</a> ';
                            }
                     ?>
                </span>
            <?php } ?>
                
                    <?php if($user->scisco_cmb2_user_city): ?>
                        <p class="scisco-location">
                        <?=$user->scisco_cmb2_user_city?>, <?=$user->scisco_cmb2_user_state?>
                        </p>
                    <?php endif; ?>
                
                <!-- <div class="scisco-question-meta">
                    <?php esc_html_e( 'Member since:', 'scisco'); ?> <?php echo esc_html(date( get_option('date_format'), strtotime( $user->user_registered ))); ?>
                </div> -->
            </div>
            <?php $scisco_cmb2_user_question_category = $user->scisco_cmb2_user_question_category;
                        if(!empty($scisco_cmb2_user_question_category)){ ?>
            <p class="looking-for"><i class="fa fa-users"></i> Looking for: 
              <?php
                            $scisco_cmb2_user_question_category_arr = explode(",",$scisco_cmb2_user_question_category);
                            foreach($scisco_cmb2_user_question_category_arr as $question_category){
                                echo '<a href="">'.$question_category.'</a> ';
                            }
                     ?>
            </p>
        <?php } ?>
        </div>
        <div class="scisco-question-counts">
        <?php
            $user_answer = ap_total_posts_count( 'answer', false, $user->ID);
            $user_answer_count = $user_answer->publish;
            $user_question = ap_total_posts_count( 'question', false, $user->ID);
            $user_question_count = $user_question->publish;
            ?>
            <span class="ap-questions-count">
                <span><?php echo esc_html($user_question_count); ?></span>
                <?php esc_html_e( 'Que', 'scisco' ); ?>
            </span>
            <span class="ap-questions-count ap-questions-vcount">
                <span><?php echo esc_html($user_answer_count); ?></span>
                <?php esc_html_e( 'Ans', 'scisco' ); ?>
            </span>
		</div>
    </div>
    <?php } ?>
</div>
<?php
} else { ?>
<div class="alert alert-danger"><?php esc_html_e( 'No Featured users found!', 'scisco' ); ?></div>      
<?php } 
} ?>