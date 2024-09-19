
<?php get_header(); ?>
<?php $scisco_header_img = get_theme_mod('scisco_subheader_cover_img', ''); ?>
<header id="scisco-header" data-img="<?php echo esc_url($scisco_header_img); ?>">
  <?php get_template_part( 'templates/header/' . 'topnav', 'template'); ?>
  <div class="container-fluid hide-edit">
    <div id="scisco-page-title">
      <h1>
        <?php
        $scisco_search_query = get_search_query(); 
        if ( have_posts() ) {
          global $wp_query;
          $scisco_post_count = $wp_query->found_posts;
          echo esc_html($scisco_post_count) . ' ';
          if ($scisco_post_count > 1) {
            echo esc_html__( 'Results Found', 'scisco' );
          }
          else {
            echo esc_html__( 'Result Found', 'scisco' );
          }
        }
        else {
          echo esc_html__( 'No Results Found for the keyword!', 'scisco' );
        }
        ?>
      </h1>    
      <?php if (!empty($scisco_search_query)) { ?>
        <p class="scisco-description">
          <?php echo esc_html__( 'Search Results for:', 'scisco' ); ?> <?php echo esc_html($scisco_search_query); ?>
        </p>
      <?php } ?>
    </div>
  </div>
  <?php if (!empty($scisco_header_img)) { ?>
    <div id="scisco-header-overlay"></div>
  <?php } ?>
  <div class="scisco-header-breadcrumb">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="scisco-post-breadcrumb col-12 col-md-auto">
          <nav id="scisco-breadcrumb-menu" aria-label="breadcrumb">
            <?php scisco_anspress_breadcrumbs(); ?>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <main id="scisco-main-wrapper">
    <div class="container-fluid">

      <!-- Code for Search form here -->
      <div id="anspress" class="anspress">
        <div class="row">
          <div id="ap-lists" class="col-12 col-xl-9">
            <div class="ap-list-head">
              <div class="row">
                <div class="col-6 col-md-9">
                  <form id="ap-search-form" class="ap-search-form" action="http://cannaconnects.io/">
                    <div class="input-group">
                      <input type="text" class="form-control autocomplete-questions ui-autocomplete-input" placeholder="Search questions..." name="s" value="" autocomplete="off">
                      <input type="hidden" name="post_type" value="question">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-6 col-md-3">
                  <a class="btn btn-success btn-block" href="http://cannaconnects.io/questions/ask/">Ask question</a>
                </div>
              </div>
            </div>
            <!-- Code for Search form end here -->
            <?php if ( have_posts() ) { ?>  
              <div class="ap-questions">
                <?php while(have_posts()) : the_post(); ?>
                  <?php get_template_part( 'templates/' . 'search-post-template', 'template'); ?>
                <?php endwhile; ?>
              </div>
              <?php if ( (get_next_posts_link()) || (get_previous_posts_link())) : ?>
              <div class="scisco-pager">
                <?php scisco_pagination(); ?>
              </div> 
              <div class="clearfix"></div>    
            <?php endif; ?> 
          <?php } else { ?>
            <p class="ap-no-questions">
            There are no questions matching your query or you do not have permission to read them.  </p> 
          <?php } ?>


        </div>

        <aside class="col-12 col-xl-3 mt-5 mt-xl-0">
          <?php dynamic_sidebar( 'ap-sidebar' ); ?>
        </aside>



      </main>

      <?php if (is_active_sidebar( 'scisco_before_footer' )) { ?>
        <div class="container-fluid">
          <div class="scisco-footer-ads scisco-ads-wrapper">
            <?php dynamic_sidebar( 'scisco_before_footer' ); ?>
          </div>
        </div>
      <?php } ?>
      <?php get_footer(); ?>