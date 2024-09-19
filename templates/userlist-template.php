<?php
$scisco_query_var = get_query_var('qa_user');
$scisco_exclude = get_theme_mod('scisco_users_exclude');
$scisco_limit = get_theme_mod('scisco_users_limit', 10);
$scisco_orderby = get_theme_mod('scisco_users_orderby', 'alphabetical_asc');
$user_search_query = array();

$prefix = 'scisco_cmb2';
if (get_query_var('qa_orderby')) {
    $scisco_orderby = get_query_var('qa_orderby');
}

if ($scisco_exclude) {
    $exclude = explode( ',', $scisco_exclude );
} else {
    $exclude = array();
}

if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); } elseif ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); } else { $paged = 1; }
$offset = ($paged - 1) * $scisco_limit;


$users = get_users();

$total_users = count($users);
$total_pages = intval(ceil($total_users / $scisco_limit));
$exclude = array();
if($scisco_query_var):
 $exclude = array();
else:    
$ver_user = get_users(array(
    'meta_key' => 'scisco_verified_user',
    'meta_value' => 'yes'
));

foreach ($ver_user as $user) {
    $exclude[] = $user->ID;

}
endif;

//$exclude = array(1,2,5,7);

if ($scisco_query_var) {
    $user_search_query = array(
        'search'         => '*' . esc_attr( $scisco_query_var ) . '*',
        'search_columns' => array(
            'user_login',
            'user_nicename',
            'first_name',
            'last_name'
        )
    );  
}

switch ($scisco_orderby) {
    case 'registration_date_asc':
        $user_query_1 = array(
            'orderby'	 => 'user_registered',
            'order'		 => 'ASC',
            'exclude' => $exclude,
            'number'	 => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'registration_date_desc':
        $user_query_1 = array(
            'orderby'	 => 'user_registered',
            'order'		 => 'DESC',
            'exclude' => $exclude,
            'number'	 => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'alphabetical_asc':
        $user_query_1 = array(
            'orderby'	 => 'title',
            'order'		 => 'ASC',
            'exclude' => $exclude,
            'number'	 => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'alphabetical_desc':
        $user_query_1 = array(
            'orderby'	 => 'title',
            'order'		 => 'DESC',
            'exclude' => $exclude,
            'number'	 => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'reputation_asc':
        $user_query_1 = array(
            'orderby'	 => 'meta_value_num',
            'meta_key'	 => 'ap_reputations',
            'order'		 => 'ASC',
            'exclude' => $exclude,
            'number'	 => $scisco_limit,
            'offset'     => $offset
        );
    break;
    case 'reputation_desc':
        $user_query_1 = array(
            'orderby'	 => 'meta_value_num',
            'meta_key'	 => 'ap_reputations',
            'order'		 => 'ASC',
            'exclude' => $exclude,
            'number'	 => $scisco_limit,
            'offset'     => $offset
        );
    break;
}

$final_query = new WP_User_Query($user_search_query + $user_query_1);
$total_query = count($final_query->get_results());
?>
<?php if ($final_query->get_results()) { ?>
<div class="scisco-users-wrapper">

<?php $userId = 1;
$user_meta = get_user_meta($userId);
//echo "<pre>";print_r($user_meta);die;
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
if (empty($scisco_query_var)) {
    if ($total_users > $total_query) {
        $url_params_regex = '/\?.*?$/';
        preg_match($url_params_regex, get_pagenum_link(), $url_params);
        $base  = !empty($url_params[0]) ? preg_replace($url_params_regex, '', get_pagenum_link()) .'%_%/' : get_pagenum_link().'%_%';
        $format = 'page/%#%';
        $pages = paginate_links( [
            'base'      => $base,
            'format'    => $format,
            'current'      => max( 1, $paged ),
            'total'        => $total_pages,
            'type'         => 'array',
            'show_all'     => false,
            'end_size'     => 3,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => '<i class="fas fa-angle-left"></i>',
            'next_text'    => '<i class="fas fa-angle-right"></i>'
        ]
    );
    $pagination = '<div class="scisco-users-pager"><ul class="pagination pagination-lg flex-wrap justify-content-center">';
    foreach ($pages as $page) {
        $pagination .= '<li class="page-item' . (strpos($page, 'current') !== false ? ' active' : '') . '"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
    }
    $pagination .= '</ul></div>';
    echo wp_kses_post($pagination);  
    }
}
} else { ?>
<div class="alert alert-danger"><?php esc_html_e( 'No users found!', 'scisco' ); ?></div>      
<?php } ?>