<?php

/*
Plugin Name: A Practical WordPress Plugin
Plugin URI: http://github.com/ianferrell/test-project
Description: This plugin does a multitude of things in order to get your site off the ground and running properly.
Version: 1.1
Author: Ian Ferrell
Author URI: http://ianferrell.com/
*/

//// CONTACT FORM SHORTCODE
	function form_func( $atts ){

		ob_start(); // using ob_start() to convert to a string. Helpful for shortcodes that produce a lot of output ?>
		<form action="#contactform" method="post" id="contactform">
			<input type="text" name="contactName" placeholder="Name" value="">
			<input type="text" name="contactEmail" placeholder="Email" value="">
			<input type="submit" name="submit" value="Submit"/>
		</form>
		<?php $html = ob_get_clean();

        if ( isset( $_POST['submit'] ) && $_POST['contactName'] != '' && $_POST["contactEmail"] != '' ) {
            global $wpdb;
            $table = $wpdb->prefix . 'form_entries';
            $name = strip_tags($_POST['contactName'], "");
            $email = strip_tags($_POST['contactEmail'], "");
            $wpdb->insert(
                $table,
                array(
                    'name' => $name,
					'email' => $email,
                )
            );
            $html = "<p>Thanks <strong>$name</strong>, we'll be in touch soon.</p>";
        }

        if ( isset( $_POST["submit"] ) && $_POST["contactName"] == "" || isset( $_POST["submit"] ) && $_POST["contactEmail"] == "" ) {
            $html .= "<p>You need to fill all the fields.</p>";
        }
        return $html;

	}

add_shortcode( 'form', 'form_func' ); // activate the [form] shortcode


//// FORM DATABASE TABLE SETUP

	global $contact_form_db_version;
	$contact_form_db_version = '1.1';

	function form_install() {
		global $wpdb;
		global $contact_form_db_version;

		$table_name = $wpdb->prefix . 'form_entries';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			email text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql ); // Using dbDelta from the upgrade file to check database between versions

		add_option( 'contact_form_db_version', $contact_form_db_version );

	}

register_activation_hook( __FILE__, 'form_install' );

//// BOOKS CUSTOM POST TYPE
	function books_cpt() {

		$labels = array(
			'name'                  => 'BOOKS',
			'singular_name'         => 'BOOK',
			'menu_name'             => 'Books',
			'name_admin_bar'        => 'Books',
		);
		$capabilities = array(
			'edit_post'             => 'update_core',
			'read_post'             => 'update_core',
			'delete_post'           => 'update_core',
			'edit_posts'            => 'update_core',
			'edit_others_posts'     => 'update_core',
			'publish_posts'         => 'update_core',
			'read_private_posts'    => 'update_core',
		);
		$args = array(
			'label'                 => 'BOOK',
			'description'           => 'Books',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', ),
			'taxonomies'            => array( 'book_category' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-book-alt',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capabilities'          => $capabilities,
		);
		register_post_type( 'books', $args );

	}
add_action( 'init', 'books_cpt', 0 );

//// BOOK CATEGORY CUSTOM TAXONOMY
	function book_category_taxo() {

		$labels = array(
			'name'                       => 'Book Categories',
			'singular_name'              => 'Book Category',
			'menu_name'                  => 'Book Categories',
		);
		$capabilities = array(
			'manage_terms'               => 'update_core',
			'edit_terms'                 => 'update_core',
			'delete_terms'               => 'update_core',
			'assign_terms'               => 'update_core',
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'capabilities'               => $capabilities,
		);
		register_taxonomy( 'book_category', array( 'books' ), $args );

//// DEFAULT TAXONOMY
		/// In a real environment it would probably be better to tie this to plugin activation
		// rather to run on each page load.

        if( !term_exists( 'NON FICTION', 'book_category' ) ) {
            wp_insert_term(
                'NON FICTION',
                'book_category',
                array(
                    'description' => 'Non-Fiction Books',
                    'slug'        => 'nonfiction'
                )
            );
        }

	}
add_action( 'init', 'book_category_taxo', 0 );
