<?php

if ( ! class_exists( 'Loadmore' ) ) {
	/**
	 * Class Loadmore
	 *
	 * @package    StudentCTP
	 * @author     Martin Nestorov
	 */
	class Loadmore {

		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'dx_loadmore_scripts' ) );
			add_action( 'wp_ajax_loadmore', array( $this, 'dx_loadmore_ajax_handler' ) );
			add_action( 'wp_ajax_nopriv_loadmore', array( $this, 'dx_loadmore_ajax_handler' ) );
		}

		public function dx_loadmore_scripts() {
			global $wp_query;

			// Register script but don't enqueue it yet.
			wp_register_script( 'dx-loadmore', SCPT_URL_PATH . './includes/assets/js/loadmore.js', array( 'jquery' ), false, true );
			wp_enqueue_script( 'dx-loadmore' );

			wp_localize_script(
				'dx-loadmore',
				'loadmore_params',
				array(
					'ajaxurl'      => site_url() . '/wp-admin/admin-ajax.php', // WP AJAX.
					'posts'        => wp_json_encode( $wp_query->query_vars ),    // Loop is here.
					'current_page' => get_query_var( 'paged' ) ?? 1,
					'max_page'     => $wp_query->max_num_pages,
				)
			);

			wp_register_style( 'dx-main', SCPT_URL_PATH . './includes/assets/css/main.css', array(), false, 'all' );
			wp_enqueue_style( 'dx-main' );
		}

		public function dx_loadmore_ajax_handler() {

			$args                     = json_decode( stripslashes( ! isset( $_POST['query'] ) ?? '' ), true );
			$args['post_type']        = 'student';
			$args['posts_per_page']   = 3;
			$args['paged']            = $_POST['page'] + 1; // Loading next page.
			$args['post_status']      = 'publish';
			$args['suppress_filters'] = true;

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part( 'template-parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );
				}
			}

			die;
		}
	}
}