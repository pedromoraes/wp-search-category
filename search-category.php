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

function searchcategory_shortcode( $attributes ) {
    ob_start();
    include( SCPLUGIN_BASE_PATH . "/template.php" );
    return ob_get_clean();
}
add_shortcode( 'search-category', 'searchcategory_shortcode' );

function searchcategory_posts_where( $where ) {
  global $wpdb; $posts = $wpdb->posts;

  if (isset($_GET[SCPLUGIN_QUERY_ARG]) && $_GET[SCPLUGIN_QUERY_ARG] != "") {
    $word = $wpdb->escape($word);
    $words = preg_split('/\s+/', $_GET[SCPLUGIN_QUERY_ARG]);
    $search = "";
    foreach ($words as $word) {
      if ($search != "") $search .= " OR ";
      $search .= "${posts}.post_content like '%${word}%'";
    }
    $where .= " AND ($search)";
  }
	return $where;
}
add_filter( 'posts_where' , 'searchcategory_posts_where' );
