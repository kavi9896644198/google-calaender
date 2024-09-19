<?php
$scisco_query_var = get_query_var('qa_user');
$scisco_orderby = get_theme_mod('scisco_users_orderby', 'alphabetical_asc');

if (get_query_var('qa_orderby')) {
    $scisco_orderby = get_query_var('qa_orderby');
}
?>
<form role="search" method="get" id="scisco-user-search-form">
    <div class="form-row">
        <div class="col-9">
            <div class="input-group">
            <input type="text" class="form-control" minlength="3" placeholder="<?php esc_attr_e('Search attendees...', 'scisco'); ?>" name="qa_user" value="<?php if ($scisco_query_var) { echo esc_attr($scisco_query_var); } ?>" />
            <div class="input-group-append"> 
                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
            </div>
        </div>
        </div>
        <div class="col-3">
            <!-- <select name="qa_orderby" id="qa_orderby" class="custom-select w-100">
                <option value="alphabetical_asc" <?php if ($scisco_orderby == 'alphabetical_asc') { ?>selected<?php } ?>><?php esc_html_e( 'Sort by:', 'scisco' ); ?> <?php esc_html_e( 'Alphabetical ASC', 'scisco' ); ?></option>
                <option value="alphabetical_desc" <?php if ($scisco_orderby == 'alphabetical_desc') { ?>selected<?php } ?>><?php esc_html_e( 'Sort by:', 'scisco' ); ?> <?php esc_html_e( 'Alphabetical DESC', 'scisco' ); ?></option>
                <option value="registration_date_asc" <?php if ($scisco_orderby == 'registration_date_asc') { ?>selected<?php } ?>><?php esc_html_e( 'Sort by:', 'scisco' ); ?> <?php esc_html_e( 'Registration Date ASC', 'scisco' ); ?></option>
                <option value="registration_date_desc" <?php if ($scisco_orderby == 'registration_date_desc') { ?>selected<?php } ?>><?php esc_html_e( 'Sort by:', 'scisco' ); ?> <?php esc_html_e( 'Registration Date DESC', 'scisco' ); ?></option>
                <?php if ( ap_is_addon_active('reputation.php')) { ?>
                <option value="reputation_asc" <?php if ($scisco_orderby == 'reputation_asc') { ?>selected<?php } ?>><?php esc_html_e( 'Sort by:', 'scisco' ); ?> <?php esc_html_e( 'Reputation ASC', 'scisco' ); ?></option>
                <option value="reputation_desc" <?php if ($scisco_orderby == 'reputation_desc') { ?>selected<?php } ?>><?php esc_html_e( 'Sort by:', 'scisco' ); ?> <?php esc_html_e( 'Reputation DESC', 'scisco' ); ?></option>
                <?php } ?>
            </select> -->
            <div class="availability_custom_class">
                <a href="/working-schedule/" target="_blank"><button type="button">Set Your Availability</button></a>
            </div>
        </div>
    </div>
</form>

<div class="custom_filter_user container-fluid">
    <select id="company_type_dropdown">
      <option value="">Company Type</option>
      <?php 
      $args = array(
        'taxonomy' => 'question_category',
        'orderby' => 'name',
        'order'   => 'ASC',
        'hide_empty'         => 0
      );
      $cats = get_categories($args);
      $post_options = array();
      foreach($cats as $cat) { 
      ?>
      <option value="<?php echo $cat->name ?>" <?php if(isset($_GET['type']) && $_GET['type'] == $cat->name){echo 'selected';} ?>><?php echo $cat->name ?></option>
      <?php } ?>
      <!-- <option value="cultivation" <?php if(isset($_GET['type']) && $_GET['type'] == 'cultivation'){echo 'selected';} ?>>Cultivation</option>
      <option value="retail" <?php if(isset($_GET['type']) && $_GET['type'] == 'retail'){echo 'selected';} ?>>Retail</option>
      <option value="wholesale" <?php if(isset($_GET['type']) && $_GET['type'] == 'wholesale'){echo 'selected';} ?>>Wholesale</option>
      <option value="distributor" <?php if(isset($_GET['type']) && $_GET['type'] == 'distributor'){echo 'selected';} ?>>Distributor</option>
      <option value="investors" <?php if(isset($_GET['type']) && $_GET['type'] == 'investors'){echo 'selected';} ?>>Investors</option>
      <option value="dispensary Owner" <?php if(isset($_GET['type']) && $_GET['type'] == 'dispensary Owner'){echo 'selected';} ?>>Dispensary Owner</option> -->
    </select>
    <select id="looking_for_dropdown">
      <option value="">Find People Looking For...</option>
      <?php 
      $args = array(
        'taxonomy' => 'question_category',
        'orderby' => 'name',
        'order'   => 'ASC',
        'hide_empty'         => 0
      );
      $cats = get_categories($args);
      $post_options = array();
      foreach($cats as $cat) { 
      ?>
      <option value="<?php echo $cat->name ?>" <?php if(isset($_GET['looking']) && $_GET['looking'] == $cat->name){echo 'selected';} ?>><?php echo $cat->name ?></option>
      <?php } ?>
      <!-- <option value="cultivation" <?php if(isset($_GET['looking']) && $_GET['looking'] == 'cultivation'){echo 'selected';} ?>>Cultivation</option>
      <option value="retail" <?php if(isset($_GET['looking']) && $_GET['looking'] == 'retail'){echo 'selected';} ?>>Retail</option>
      <option value="wholesale" <?php if(isset($_GET['looking']) && $_GET['looking'] == 'wholesale'){echo 'selected';} ?>>Wholesale</option>
      <option value="distributor" <?php if(isset($_GET['looking']) && $_GET['looking'] == 'distributor'){echo 'selected';} ?>>Distributor</option>
      <option value="investors" <?php if(isset($_GET['looking']) && $_GET['looking'] == 'investors'){echo 'selected';} ?>>Investors</option>
      <option value="dispensary Owner" <?php if(isset($_GET['looking']) && $_GET['looking'] == 'dispensary Owner'){echo 'selected';} ?>>Dispensary Owner</option> -->
    </select>
    <?php 
    global $woocommerce;
    $countries_obj   = new WC_Countries();
    $countries   = $countries_obj->__get('countries');
    $default_country = $countries_obj->get_base_country('US');
    $default_county_states = $countries_obj->get_states( $default_country )
    ?>
    <select id="custom_state">
      <option value="">State</option>
      <?php 
      if(!empty($default_county_states)){
        foreach ($default_county_states as $key => $value) { ?>
          <option value="<?php echo $key; ?>" <?php if(isset($_GET['state']) && $key == $_GET['state']){echo 'selected';} ?>><?php echo $value; ?></option>
        <?php
        }
      }
      ?>
    </select>
    <button class="btn btn-primary availability_custom_search">Filter Search Results</button>
    <button onclick="removeURLParams()" class="btn btn-primary">Clear Filter</button>
</div>

<script>

    $(document).ready(function(){
      $('.availability_custom_search').on('click',function(){
         var selectedLocation = 'availability';
          var url = window.location.href;
          if(selectedLocation !== '') {
            url = updateUrlParameter(url, 'Availability', selectedLocation);
          } else {
            url = removeUrlParameter(url, 'Availability');
          }
          window.location.href = url;
      })
      $('#company_type_dropdown').on('change', function() {
        var selectedType = $(this).val();
        var url = window.location.href;
        if(selectedType !== '') {
          url = updateUrlParameter(url, 'type', selectedType);
        } else {
          url = removeUrlParameter(url, 'type');
        }
        window.location.href = url;
      });

      $('#looking_for_dropdown').on('change', function() {
        var selectedLocation = $(this).val();
        var url = window.location.href;
        if(selectedLocation !== '') {
          url = updateUrlParameter(url, 'looking', selectedLocation);
        } else {
          url = removeUrlParameter(url, 'looking');
        }
        window.location.href = url;
      });

      $('#custom_state').on('change', function() {
        var selectedLocation = $(this).val();
        var url = window.location.href;
        if(selectedLocation !== '') {
          url = updateUrlParameter(url, 'state', selectedLocation);
        } else {
          url = removeUrlParameter(url, 'state');
        }
        window.location.href = url;
      });

      // Function to update URL parameter
      function updateUrlParameter(url, param, value) {
        var urlParts = url.split('?');
        if (urlParts.length >= 2) {
          var prefix = encodeURIComponent(param) + '=';
          var parts = urlParts[1].split(/[&;]/g);
          // Iterate over the parts and replace the parameter if it exists
          for (var i = 0; i < parts.length; i++) {
            if (parts[i].lastIndexOf(prefix, 0) !== -1) {
              parts[i] = prefix + encodeURIComponent(value);
              return urlParts[0] + '?' + parts.join('&');
            }
          }
          // If parameter does not exist, append it to the URL
          return urlParts[0] + '?' + urlParts[1] + '&' + prefix + encodeURIComponent(value);
        } else {
          // If no parameters exist in the URL, add them directly
          return url + '?' + encodeURIComponent(param) + '=' + encodeURIComponent(value);
        }
      }
      // Function to remove URL parameter
      function removeUrlParameter(url, param) {
        var urlParts = url.split('?');
        if (urlParts.length >= 2) {
          var prefix = encodeURIComponent(param) + '=';
          var parts = urlParts[1].split(/[&;]/g);
          // Iterate over the parts and remove the parameter if it exists
          for (var i = parts.length; i-- > 0;) {
            if (parts[i].lastIndexOf(prefix, 0) !== -1) {
              parts.splice(i, 1);
            }
          }

          // Reconstruct the URL without the removed parameter
          if (parts.length > 0) {
            return urlParts[0] + '?' + parts.join('&');
          } else {
            return urlParts[0];
          }
        } else {
          // If no parameters exist in the URL, return the URL as is
          return url;
        }
      }
   });
    function removeURLParams() {
      var urlWithoutParams = window.location.origin + window.location.pathname;
      window.location.href = urlWithoutParams;
    }
   
</script>