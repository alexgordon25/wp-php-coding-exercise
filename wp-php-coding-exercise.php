<?php
/**
 * WP PHP Coding Exercise Plugin
 *
 * @link              https://danielgordon.dev
 * @since             1.0.0
 * @package           Wp_Php_Coding_Exercise
 *
 * @wordpress-plugin
 * Plugin Name:       WP PHP Coding Exercise
 * Plugin URI:        https://https://github.com/alexgordon25/wp-php-coding-exercise/
 * Description:       A very simple WP Plugin Coding Exercise.
 * Version:           1.0.0
 * Author:            Daniel Gordon
 * Author URI:        https://danielgordon.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' )) {
    exit;
}

/**
 * Plugin version.
 */
define( 'WP_PHP_CODING_EXERCISE_VERSION', '1.0.0' );

/**
 * Plugin activation.
 */
function activate_wp_php_coding_exercise() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-php-coding-exercise-activator.php';
	Wp_Php_Coding_Exercise_Activator::activate();
}

/**
 * Plugin deactivation.
 */
function deactivate_wp_php_coding_exercise() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-php-coding-exercise-deactivator.php';
	Wp_Php_Coding_Exercise_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_php_coding_exercise' );
register_deactivation_hook( __FILE__, 'deactivate_wp_php_coding_exercise' );

// WP PHP Coding Exercise Plugin Class
class WP_PHP_Coding_Exercise {

    // Constructor
    public function __construct() {

		// Register example_cpt post type
        add_action( 'init', array( $this, 'register_example_cpt_post_type' ) );

		// Register the example meta field
        add_action( 'init', array( $this, 'register_example_meta' ) );

		// Add a meta box for managing the example meta field
        add_action( 'add_meta_boxes', array( $this, 'add_example_meta_box' ) );

		// Save the example meta field value when the post is saved or updated
        add_action( 'save_post', array( $this, 'save_example_meta' ) );

		// Display the meta field and its values in the REST API response for the example_cpt CPT
        add_filter( 'rest_prepare_example_cpt', array( $this, 'add_example_meta_to_rest_response' ), 10, 2 );
    }

    // Register Example CPT post type
    public function register_example_cpt_post_type() {
        $labels = array(
			'name'                  => _x( 'Example CPTs', 'Post Type General Name', 'wp_php_coding_exercise' ),
			'singular_name'         => _x( 'Example CPT', 'Post Type Singular Name', 'wp_php_coding_exercise' ),
			'menu_name'             => __( 'Example CPT', 'wp_php_coding_exercise' ),
			'name_admin_bar'        => __( 'Example CPT', 'wp_php_coding_exercise' ),
			'archives'              => __( 'Item Archives', 'wp_php_coding_exercise' ),
			'attributes'            => __( 'Item Attributes', 'wp_php_coding_exercise' ),
			'parent_item_colon'     => __( 'Parent Item:', 'wp_php_coding_exercise' ),
			'all_items'             => __( 'All Items', 'wp_php_coding_exercise' ),
			'add_new_item'          => __( 'Add New Item', 'wp_php_coding_exercise' ),
			'add_new'               => __( 'Add New', 'wp_php_coding_exercise' ),
			'new_item'              => __( 'New Item', 'wp_php_coding_exercise' ),
			'edit_item'             => __( 'Edit Item', 'wp_php_coding_exercise' ),
			'update_item'           => __( 'Update Item', 'wp_php_coding_exercise' ),
			'view_item'             => __( 'View Item', 'wp_php_coding_exercise' ),
			'view_items'            => __( 'View Items', 'wp_php_coding_exercise' ),
			'search_items'          => __( 'Search Item', 'wp_php_coding_exercise' ),
			'not_found'             => __( 'Not found', 'wp_php_coding_exercise' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wp_php_coding_exercise' ),
			'featured_image'        => __( 'Featured Image', 'wp_php_coding_exercise' ),
			'set_featured_image'    => __( 'Set featured image', 'wp_php_coding_exercise' ),
			'remove_featured_image' => __( 'Remove featured image', 'wp_php_coding_exercise' ),
			'use_featured_image'    => __( 'Use as featured image', 'wp_php_coding_exercise' ),
			'insert_into_item'      => __( 'Insert into item', 'wp_php_coding_exercise' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'wp_php_coding_exercise' ),
			'items_list'            => __( 'Items list', 'wp_php_coding_exercise' ),
			'items_list_navigation' => __( 'Items list navigation', 'wp_php_coding_exercise' ),
			'filter_items_list'     => __( 'Filter items list', 'wp_php_coding_exercise' ),
		);
		$rewrite = array(
			'slug'                  => 'example-cpt',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true
		);
		$args = array(
			'label'                 => __( 'Example CPT', 'wp_php_coding_exercise' ),
			'description'           => __( 'Example CPT posts', 'wp_php_coding_exercise' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
		);
		register_post_type( 'example_cpt', $args );
    }

	// Register a custom "Example Meta" meta field for the example_cpt post type
    public function register_example_meta() {
        register_meta(
            'example_cpt',
            'example_meta',
            array(
                'show_in_rest' => true,
                'single'       => true,
                'type'         => 'string',
            )
        );
    }

	// Add a text box for managing the custom meta field
    public function add_example_meta_box() {
        add_meta_box(
            'example_meta_box',
            'Example Meta Field',
            array( $this, 'render_example_meta_box' ),
            'example_cpt',
            'normal',
            'high'
        );
    }

	// Render the meta box content
    public function render_example_meta_box( $post ) {
        $value = get_post_meta( $post->ID, 'example_meta', true );
		?>
			<label for="example_meta_field">Example Meta Field:</label>
			<input type="text" id="example_meta_field" name="example_meta" value="<?php echo esc_attr( $value ); ?>" style="width: 100%;">
		<?php
    }

	// Save the custom meta field value when the post is saved or updated
    public function save_example_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        if ( isset( $_POST['example_meta'] ) ) {
            $meta_value = sanitize_text_field( $_POST['example_meta'] );
            update_post_meta( $post_id, 'example_meta', $meta_value );
        }
    }

    // Display the meta field and its values in the REST API response for the example_cpt CPT
    public function add_example_meta_to_rest_response( $response, $post ) {
        $meta_value = get_post_meta( $post->ID, 'example_meta', true );
        $response->data['example_meta'] = $meta_value;
        return $response;
    }
}

// Instantiate the plugin class
if (class_exists('WP_PHP_Coding_Exercise')) {
    $wp_php_coding_exercise = new WP_PHP_Coding_Exercise();
}