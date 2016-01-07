<?php
/**
 * @package Search_Category
 * @version 0.1
 */
/*
Plugin Name: Search_Category
Plugin URI: http://wordpress.org/plugins/search-category/
Description: Full-text search restricted to current page query. Might be used on a category page, for example, for searching inside category.
Author: Pedro Moraes
Version: 0.1
Author URI: http://pedromoraes.net/
 */
define( 'SCPLUGIN_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SCPLUGIN_QUERY_ARG', 'scq' );

$options = get_option( 'searchcategory_settings' );
$SCPLUGIN_OPTIONS = array(
  'add-to-category-page' =>   $options['add-to-category-page'] ? true : false,
  'all-words' =>              $options['all-words'] ? true : false,
  'submit-text' =>            empty($options['submit-text']) ? "Search this category" : $options['submit-text'],
  'clear-text' =>             empty($options['clear-text']) ? "Clear" : $options['clear-text'],
  'placeholder-text' =>       empty($options['placeholder-text']) ? "" : $options['placeholder-text'],
);

//SHORTCODE
function searchcategory_shortcode( $attributes ) {
    global $SCPLUGIN_OPTIONS;
    ob_start();
    include( SCPLUGIN_BASE_PATH . "/template.php" );
    return ob_get_clean();
}
add_shortcode( 'search-category', 'searchcategory_shortcode' );

//FILTER
function searchcategory_posts_where( $where ) {
  global $SCPLUGIN_OPTIONS, $wpdb; $posts = $wpdb->posts;

  if (isset($_GET[SCPLUGIN_QUERY_ARG]) && $_GET[SCPLUGIN_QUERY_ARG] != "") {
    $search_connector = $SCPLUGIN_OPTIONS['all-words'] ? "AND" : "OR";
    $words = preg_split('/\s+/', $_GET[SCPLUGIN_QUERY_ARG]);
    $search = "";
    foreach ($words as $word) {
      if (empty($word) || !(strlen($word) > 2 || sizeof($words) == 1)) continue;
      $word = $wpdb->escape($word);
      if ($search != "") $search .= " $search_connector ";
      $search .= "${posts}.post_content like '%${word}%'";
    }
    $where .= " AND ($search)";
  }
	return $where;
}
add_filter( 'posts_where' , 'searchcategory_posts_where' );

if( is_admin() ) {
	include( SCPLUGIN_BASE_PATH . "/SearchCategoryPluginOptions.class.php" );
	$settings_page = new SearchCategoryPluginOptions();
}
