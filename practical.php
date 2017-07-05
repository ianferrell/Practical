<?php

/*
Plugin Name: A Practical WordPress Plugin
Plugin URI: http://github.com/ianferrell/test-project
Description: This plugin does a multitude of things in order to get your site off the ground and running properly.
Version: 1.0
Author: Ian Ferrell
Author URI: http://ianferrell.com/
*/

//// CONTACT FORM SHORTCODE
	function form_func( $atts ){

		ob_start(); // using ob_start() to convert to a string. Helpful for shortcodes that produce a lot of output ?>
		<form method="post">
			Name: <input type="text" name="name" value="<?php echo $name;?>">
			E-mail: <input type="text" name="email" value="<?php echo $email;?>">
			<button type="submit">Submit</button>
		</form>
		<?php return ob_get_clean();
	}

add_shortcode( 'form', 'form_func' ); // activate the [form] shortcode


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
