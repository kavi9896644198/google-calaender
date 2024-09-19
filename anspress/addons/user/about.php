<?php 
$user_data = get_userdata(ap_current_user_id());
$user_meta_data = get_usermeta(ap_current_user_id());
//print_r($user_meta_data);
$registered_on = $user_data->user_registered; 
$first_name = $user_data->first_name;
$last_name = $user_data->last_name; 
$user_website = str_replace(array( 'http://', 'https://' ), '', $user_data->user_url);
?>

<div class="scisco-user-boxes">
    <div class="row">
        <div class="col-12 col-md">
            <div class="scisco-user-box box-success">
                <div class="scisco-user-box-icon">
                    <i class="fas fa-comment"></i>
                </div>
                <div class="scisco-user-box-info">
                    <?php 
                    $user_answer = ap_total_posts_count( 'answer', false, ap_current_user_id()); 
                    $user_best_answer = ap_total_posts_count( 'answer', 'best_answer', ap_current_user_id()); 
                    $user_answer_count = $user_answer->publish;
                    $user_best_answer_count = $user_best_answer->publish;
                    ?>
                    <span><?php echo esc_html($user_answer_count); ?> <?php esc_html_e( 'Answers', 'scisco'); ?></span>
                    <span><?php echo esc_html($user_best_answer_count); ?> <?php esc_html_e( 'Best Answers', 'scisco'); ?></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md mt-3 mt-md-0">
            <div class="scisco-user-box box-danger">
                <div class="scisco-user-box-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="scisco-user-box-info">
                    <?php 
                    $user_question = ap_total_posts_count( 'question', false, ap_current_user_id()); 
                    $user_unanswered = ap_total_posts_count( 'question', 'unanswered', ap_current_user_id()); 
                    $user_question_count = $user_question->publish;
                    $user_unanswered_count = $user_unanswered->publish;
                    ?>
                    <span><?php echo esc_html($user_question_count); ?> <?php esc_html_e( 'Questions', 'scisco'); ?></span>
                    <span><?php echo esc_html($user_unanswered_count); ?> <?php esc_html_e( 'Unanswered', 'scisco'); ?></span>
                </div>
            </div>
        </div>
    <?php if ( ap_is_addon_active('reputation.php')) { ?>
        <div class="col-12 col-md mt-3 mt-md-0">
            <div class="scisco-user-box box-warning">
                <div class="scisco-user-box-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="scisco-user-box-info">
                    <?php $reputation_count = ap_get_user_reputation_meta( ap_current_user_id(), true ); ?>
                    <span><?php echo esc_html($reputation_count); ?> <?php esc_html_e( 'Reputation', 'scisco'); ?></span>
                    <span><?php esc_html_e( 'Member since:', 'scisco'); ?> <?php echo esc_html(date( "M Y", strtotime( $registered_on ))); ?></span>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
<div class="scisco-user-table-wrapper">
    <?php $company = get_user_meta( ap_current_user_id(), 'u_company_name', true );
        if($company):
            $company_name = $company;
        else:
            $company_name = "------";
        endif;
     ?>
    <div class="scisco-user-table user-name-bx">
        <div class="scisco-user-table-left"><?php esc_html_e( 'Company', 'scisco' ); ?></div>
        <div class="scisco-user-table-right"><?php echo esc_html($company_name); ?></div>
    </div>
    <?php $u_type = get_user_meta( ap_current_user_id(), 'u_type', true );
        if($u_type):
            $type = $u_type;
        else:
            $type = "------";
        endif;
     ?>
    <div class="scisco-user-table age-bx">
        <div class="scisco-user-table-left"><?php esc_html_e( 'Type', 'scisco' ); ?></div>
        <div class="scisco-user-table-right"><?php echo esc_html($type); ?></div>
    </div>
    <?php $u_stage = get_user_meta( ap_current_user_id(), 'u_stage', true );
        if($u_stage):
            $stage = $u_stage;
        else:
            $stage = "------";
        endif;
     ?>
    <div class="scisco-user-table gender-bx">
        <div class="scisco-user-table-left"><?php esc_html_e( 'Stage', 'scisco' ); ?></div>
        <div class="scisco-user-table-right"><?php echo esc_html($stage); ?></div>
    </div>

    
    <?php
    if ($user_website): 
        $website = $user_website;
    else: 
        $website = "------";
        endif;   
    ?>
    <div class="scisco-user-table website-bx">
        <div class="scisco-user-table-left"><?php esc_html_e( 'Website', 'scisco' ); ?></div>
        <div class="scisco-user-table-right"><?php echo esc_html($website); ?></div>
    </div>
    <?php 
    $user_location = get_user_meta( ap_current_user_id(), 'scisco_cmb2_user_phone', true ); 
    if ($user_location):
        $location = $user_location;
    else: 
        $location = "------";
        endif;   
    ?>
    <div class="scisco-user-table location-bx">
        <div class="scisco-user-table-left"><?php esc_html_e( 'Phone', 'scisco' ); ?></div>
        <div class="scisco-user-table-right"><?php echo esc_html($location); ?></div>
    </div>

</div>
<?php
$scisco_user_icons = get_user_meta( ap_current_user_id(), 'scisco_cmb2user_icons', true );
$scisco_enable_user_icons = get_theme_mod('scisco_ap_social_icons');
if ( $scisco_user_icons && $scisco_enable_user_icons ) {
?>
<div id="scisco-user-icons">
    <ul class="scisco-icons">
    <?php 
    foreach ( (array) $scisco_user_icons as $key => $entry ) { 
        $icon = $icon_link  = $tooltip_output  ='';
        if ( isset( $entry['scisco_cmb2iconimg'] ) ) {            
            $icon = $entry['scisco_cmb2iconimg'];
        }
        if ( isset( $entry['scisco_cmb2iconlink'] ) ) {            
            $icon_link = $entry['scisco_cmb2iconlink'];
        } 
        if (isset($entry['scisco_cmb2icontooltip'])) {
            $tooltip_output = $entry['scisco_cmb2icontooltip'];
        }
        if (!empty($tooltip_output)) {
        ?>
        <li data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr($entry['scisco_cmb2icontooltip']); ?>"><a class="scisco-social-btn btn-<?php echo esc_attr($icon); ?>" href="<?php echo esc_url($icon_link); ?>" target="_blank"><span aria-hidden="true" class="fab fa-<?php echo esc_attr($icon); ?>"></span></a></li>
        <?php } else { ?>
        <li><a class="scisco-social-btn btn-<?php echo esc_attr($icon); ?>" href="<?php echo esc_url($icon_link); ?>" target="_blank"><span aria-hidden="true" class="fab fa-<?php echo esc_attr($icon); ?>"></span></a></li>
        <?php } ?>
    <?php } ?>
    </ul>
    <div class="clearfix"></div>
</div>
<?php } ?>  