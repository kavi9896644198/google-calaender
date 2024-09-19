<?php get_header(); ?>
<?php
if (get_query_var( 'ap_page' ) != '' && get_query_var( 'ap_page' ) == 'user'){
    $scisco_user_id = ap_current_user_id();
    $scisco_header_img = get_user_meta( $scisco_user_id, 'scisco_cmb2_user_cover_image', true );
    if (empty($scisco_header_img)) {
        $scisco_header_img = get_theme_mod('scisco_ap_user_default_img', '');
    }
    $scisco_user_desc = get_user_meta( $scisco_user_id, 'description',true);
    $scisco_user_location = get_user_meta( $scisco_user_id,'scisco_cmb2_location',true);
    $scisco_cmb2_user_skills = get_user_meta($scisco_user_id,'scisco_cmb2_user_skills',true);  
    $scisco_user_reputation = ap_get_user_reputation_meta( $scisco_user_id );
    $user_id = get_current_user_id();
?>

<header id="scisco-header" data-img="<?php echo esc_url($scisco_header_img); ?>">
<?php get_template_part( 'templates/header/' . 'topnav', 'template'); ?>
    <div class="container-fluid">
        <div id="scisco-page-title" class="scisco-profile-header">
            <div class="scisco-profile-thumbnail">
            <?php echo get_avatar( $scisco_user_id, 150 ); ?>
            </div>
            <div class="scisco-profile-info">
                <h1 class="scisco-ap-title">
                <?php
                echo ap_user_display_name(
                    [
                        'user_id' => $scisco_user_id,
                        'html'    => false,
                    ]
                );
                ?><span class="scisco-title-count scisco-title-rep"><?php echo esc_html($scisco_user_reputation); ?></span>
                </h1>
                <?php if ($scisco_user_desc) { ?>
                <div class="scisco-description">
                <p><?php echo wp_kses_post($scisco_user_desc); ?></p>
                </div>
                <?php } ?>
                <?php if($scisco_user_location): ?>
                <div class="scisco-location">
                <p><?php echo wp_kses_post($scisco_user_location); ?></p>
                </div>
            <?php endif; ?>            
                <div class="scisco-skills">
                    <p class="skill-title">Skills and Specialties</p>
                    <?php                 
                    if($scisco_cmb2_user_skills): ?>
                <span class="scisco-cat-list">                          
                    <?php    
                    $scisco_cmb2_user_skills_arr = explode(",",$scisco_cmb2_user_skills);     
                    foreach($scisco_cmb2_user_skills_arr as $user_skills){
                                echo '<a href="">'.$user_skills.'</a> ';
                            }
                     ?>
                </span>
            <?php endif; ?>
                        
                    </span>
            </div>
        </div>
        <?php if($scisco_user_id == $user_id) { ?>
         <div class="user_Image_upload" style="color: white;">
            <form id="profile_image_form" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <label for="user_profile_image">Upload Image:</label>
                <input type="file" name="user_profile_image" id="user_profile_image">
                <input type="hidden" name="scisco_user_id" value="<?php echo esc_attr( $scisco_user_id ); ?>">
                <input type="submit" name="submit_user_profile_image" value="Upload">
            </form>
            <script>
                function validateForm() {
                    var imageInput = document.getElementById("user_profile_image");
                    if (imageInput.files.length === 0) {
                        alert("Please select an image.");
                        return false;
                    }
                    return true;
                }
            </script>
        </div>
        <?php } ?>
    </div>
</div>
    <?php 
    $scisco_breadcrumb = get_theme_mod('scisco_disable_breadcrumb'); 
    if (empty($scisco_breadcrumb)) {
         $displayUsername = ap_user_display_name(['user_id' => $scisco_user_id,'html'    => false,]); 
    ?>
    <div class="scisco-header-breadcrumb">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="scisco-post-breadcrumb col-12 col-md-auto">
                    <nav id="scisco-breadcrumb-menu" aria-label="breadcrumb">
                        <?php // scisco_anspress_breadcrumbs(); ?>
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="/questions/">Questions</a></li>
                            <li class="breadcrumb-item"><a href="/questions/profile/<?php  echo $displayUsername; ?>/about/">User Directory</a></li>
                            <li class="breadcrumb-item active">
                                <?php  echo $displayUsername; ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="scisco-post-date col-12 col-md-auto mt-2 mt-md-0 ml-auto"><?php do_action('scisco_user_buttons'); ?></div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if (!empty($scisco_header_img)) { ?>
    <div id="scisco-header-overlay"></div>
    <?php } ?>
</header>    

<?php } else { ?>    
<?php
$scisco_subtitle = get_post_meta( get_queried_object_id(), 'scisco_cmb2_subtitle', true ); 
if (is_tax('question_category') || is_tax('question_tag')) { 
    $scisco_header_img = get_term_meta( get_queried_object_id(), 'scisco_cmb2_taxheader_image', true );
    if (empty($scisco_header_img)) {
        $scisco_header_img = get_theme_mod('scisco_subheader_cover_img', '');
    } 
} else {
    $scisco_header_img = get_theme_mod('scisco_subheader_cover_img', '');
}
?>
<header id="scisco-header" data-img="<?php echo esc_url($scisco_header_img); ?>">
<?php get_template_part( 'templates/header/' . 'topnav', 'template'); ?>
    <div class="container-fluid">
        <div id="scisco-page-title">
        <?php if (is_tax('question_category') || is_tax('question_tag')) { 
            $term = get_queried_object();
            $term_desc = $term->description;
            $term_count = $term->count;
        ?>
        <h1 class="scisco-ap-title"><?php single_term_title(); ?><span class="scisco-title-count"><?php echo esc_html($term_count); ?></span></h1>
        <?php
            if ($term_desc) {
                echo '<p class="scisco-description">' . esc_html($term_desc) . '</p>';
            }
        ?>
        <?php } else { ?>
        <?php the_title('<h1>','</h1>'); ?>
        <?php
        if (is_search()) { ?>
            <p class="scisco-description">
                <?php echo esc_html__( 'Search Results for:', 'scisco' ); ?> <?php echo esc_html(get_search_query()); ?>
            </p>
        <?php } elseif (!empty($scisco_subtitle)) {
            echo '<p class="scisco-description">' . esc_html($scisco_subtitle) . '</p>';
        } 
        ?> 
        <?php } ?>
        </div>
    </div>
    <?php 
    $scisco_breadcrumb = get_theme_mod('scisco_disable_breadcrumb'); 
    if (empty($scisco_breadcrumb)) {
    ?>
    <div class="scisco-header-breadcrumb">
        <div class="container-fluid">
            <nav id="scisco-breadcrumb-menu" aria-label="breadcrumb">
                <?php scisco_anspress_breadcrumbs(); ?>
            </nav>
        </div>
    </div>
    <?php } ?>
    <?php if (!empty($scisco_header_img)) { ?>
    <div id="scisco-header-overlay"></div>
    <?php } ?>
</header>

<?php } ?>

<main id="scisco-main-wrapper">
    <div class="container-fluid">
        <?php while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
            <div class="clearfix"></div>
        <?php endwhile; ?>
    </div>
</main>

<?php if (is_active_sidebar( 'scisco_before_footer' )) { ?>
<div class="container-fluid">
    <div class="scisco-footer-ads scisco-ads-wrapper">
        <?php dynamic_sidebar( 'scisco_before_footer' ); ?>
    </div>
</div>
<?php } ?>
<?php get_footer(); ?>