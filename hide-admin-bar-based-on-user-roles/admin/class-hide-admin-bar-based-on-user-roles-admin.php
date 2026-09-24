<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpankit.com/
 * @since      1.7.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/admin
 * @author     Ankit Panchal
 */
class hab_Hide_Admin_Bar_Based_On_User_Roles_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.7.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.7.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.7.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Add AJAX action for dismissing review banner
		add_action('wp_ajax_hab_dismiss_review_banner', array($this, 'hab_dismiss_review_banner'));

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.7.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in hab_Hide_Admin_Bar_Based_On_User_Roles_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The hab_Hide_Admin_Bar_Based_On_User_Roles_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'hide-admin-bar-settings' ) {
			wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/hide-admin-bar-based-on-user-roles-admin.css', array( 'dashicons' ), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.7.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in hab_Hide_Admin_Bar_Based_On_User_Roles_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The hab_Hide_Admin_Bar_Based_On_User_Roles_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'hide-admin-bar-settings' ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hide-admin-bar-based-on-user-roles-admin.js', array( 'jquery' ), $this->version, true );
			$args = array(
				'url'          => admin_url( 'admin-ajax.php' ),
				'hba_nonce'    => wp_create_nonce( 'hba-nonce' ),
				'review_nonce' => wp_create_nonce( 'hab_dismiss_review_nonce' ),
				'i18n'         => array(
					'saving'           => __( 'Saving…', 'hide-admin-bar-based-on-user-roles' ),
					'saved'            => __( 'Settings saved.', 'hide-admin-bar-based-on-user-roles' ),
					'error'            => __( 'The settings couldn’t be saved. Reload the page and try again.', 'hide-admin-bar-based-on-user-roles' ),
					/* translators: %s: capability name. */
					'removeCapability' => __( 'Remove %s', 'hide-admin-bar-based-on-user-roles' ),
					'confirmReset'     => __( 'Reset all Hide Admin Bar settings? The admin bar will show again for everyone.', 'hide-admin-bar-based-on-user-roles' ),
				),
			);
			wp_localize_script( $this->plugin_name, 'ajaxVar', $args );
		}


	}


	public function generate_admin_menu_page() {

		add_options_page( __( 'Hide Admin Bar Settings', 'hide-admin-bar-based-on-user-roles' ), __( 'Hide Admin Bar Settings', 'hide-admin-bar-based-on-user-roles' ), 'manage_options', 'hide-admin-bar-settings', array(
			$this,
			'hide_admin_bar_settings'
		) );

	}

	public function hide_admin_bar_settings() {

		$settings      = get_option( "hab_settings" );
		$hab_reset_key = get_option( "hab_reset_key" );

		if ( ! empty( $hab_reset_key ) && isset( $_GET["reset_plugin"] ) && $_GET["reset_plugin"] == $hab_reset_key ) {
			update_option( "hab_settings", "" );
			update_option( "hab_reset_key", rand( 0, 999999999 ) );
			echo '<script>window.location.reload();</script>';
		}

		// hab_settings is an empty string until the first save and after a reset.
		$settings = is_array( $settings ) ? $settings : array();

		$hide_for_all    = isset( $settings['hab_disableforall'] ) && 'yes' === $settings['hab_disableforall'];
		$hide_for_guests = isset( $settings['hab_disableforallGuests'] ) && 'yes' === $settings['hab_disableforallGuests'];
		$selected_roles  = ( isset( $settings['hab_userRoles'] ) && is_array( $settings['hab_userRoles'] ) ) ? $settings['hab_userRoles'] : array();
		$capabilities    = array();
		if ( isset( $settings['hab_capabilities'] ) && is_string( $settings['hab_capabilities'] ) ) {
			$capabilities = array_values( array_unique( array_filter( array_map( 'trim', explode( ',', $settings['hab_capabilities'] ) ), 'strlen' ) ) );
		}
		$roles     = wp_roles()->get_names();
		$reset_url = empty( $hab_reset_key ) ? '' : add_query_arg(
			array(
				'page'         => 'hide-admin-bar-settings',
				'reset_plugin' => $hab_reset_key,
			),
			admin_url( 'options-general.php' )
		);

		include plugin_dir_path( __FILE__ ) . 'partials/hide-admin-bar-based-on-user-roles-admin-display.php';

	}

	public function save_user_roles() {
		global $wpdb;

		if ( current_user_can( 'manage_options' ) && wp_verify_nonce( $_POST['hbaNonce'], 'hba-nonce' ) ) {

			// Sanitize and validate all inputs
			$UserRoles      = isset($_REQUEST['UserRoles']) ? array_map('sanitize_text_field', (array)$_REQUEST['UserRoles']) : array();
			$caps           = isset($_REQUEST['caps']) ? sanitize_text_field(str_replace("&nbsp;", "", $_REQUEST['caps'])) : '';
			$disableForAll  = isset($_REQUEST['disableForAll']) ? sanitize_text_field($_REQUEST['disableForAll']) : 'no';
			$forGuests      = isset($_REQUEST['forGuests']) ? sanitize_text_field($_REQUEST['forGuests']) : 'no';

			// Validate disableForAll is either 'yes' or 'no'
			$disableForAll = in_array($disableForAll, array('yes', 'no')) ? $disableForAll : 'no';
			
			// Validate forGuests is either 'yes' or 'no'
			$forGuests = in_array($forGuests, array('yes', 'no')) ? $forGuests : 'no';

			$settings                      = array();
			$settings['hab_disableforall'] = $disableForAll;

			if ( $disableForAll == 'no' ) {
				$settings['hab_userRoles']           = $UserRoles;
				$settings['hab_capabilities']        = $caps;
				$settings['hab_disableforallGuests'] = $forGuests;
			}
			update_option( "hab_settings", $settings );
			echo "Success";
		} else {
			echo "Failed";
		}
		wp_die();
	}

	public function upgrader_process_complete() {
	}

	public function enqueue_silent_installer() {
    }

    public function check_plugin_status() {
        // Check nonce
        if (!check_ajax_referer('silent_installer', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Invalid security token.'));
        }

        // Check user capabilities
        if (!current_user_can('install_plugins')) {
            wp_send_json_error(array('message' => 'You do not have permission to install plugins.'));
        }

        $plugin_slug = sanitize_text_field($_POST['plugin_slug']);
        
        if (empty($plugin_slug)) {
            wp_send_json_error(array('message' => 'Plugin slug is required.'));
        }

        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        
        $all_plugins = get_plugins();
        $plugin_base_file = false;
        
        foreach ($all_plugins as $file => $plugin) {
            if (strpos($file, $plugin_slug . '/') === 0) {
                $plugin_base_file = $file;
                break;
            }
        }

        wp_send_json_success(array(
            'installed' => !empty($plugin_base_file),
            'active' => !empty($plugin_base_file) && is_plugin_active($plugin_base_file)
        ));
    }

    public function handle_silent_install_plugin() {
        // Check nonce
        if (!check_ajax_referer('silent_installer', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Invalid security token.'));
        }

        // Check user capabilities
        if (!current_user_can('install_plugins')) {
            wp_send_json_error(array('message' => 'You do not have permission to install plugins.'));
        }

        $plugin_slug = sanitize_text_field($_POST['plugin_slug']);
        
        if (empty($plugin_slug)) {
            wp_send_json_error(array('message' => 'Plugin slug is required.'));
        }

        // Include required files
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

        // Check if plugin is already installed
        $installed_plugins = get_plugins();
        $plugin_base_file = false;

        foreach ($installed_plugins as $file => $plugin) {
            if (strpos($file, $plugin_slug . '/') === 0) {
                $plugin_base_file = $file;
                break;
            }
        }

        // If plugin is not installed, install it
        if (!$plugin_base_file) {
            try {
                // Get plugin info
                $api = plugins_api('plugin_information', array(
                    'slug' => $plugin_slug,
                    'fields' => array(
                        'short_description' => false,
                        'sections' => false,
                        'requires' => false,
                        'rating' => false,
                        'ratings' => false,
                        'downloaded' => false,
                        'last_updated' => false,
                        'added' => false,
                        'tags' => false,
                        'compatibility' => false,
                        'homepage' => false,
                        'donate_link' => false,
                    ),
                ));

                if (is_wp_error($api)) {
                    wp_send_json_error(array('message' => $api->get_error_message()));
                }

                $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
                $install_result = $upgrader->install($api->download_link);

                if (is_wp_error($install_result)) {
                    wp_send_json_error(array('message' => $install_result->get_error_message()));
                }

                $plugin_base_file = $upgrader->plugin_info();

            } catch (Exception $e) {
                wp_send_json_error(array('message' => $e->getMessage()));
            }
        }

        // Activate the plugin
        if ($plugin_base_file) {
            try {
                $activation_result = activate_plugin($plugin_base_file);
                
                if (is_wp_error($activation_result)) {
                    wp_send_json_error(array('message' => $activation_result->get_error_message()));
                }

                wp_send_json_success(array(
                    'message' => 'Plugin installed and activated successfully',
                    'plugin_file' => $plugin_base_file
                ));

            } catch (Exception $e) {
                wp_send_json_error(array('message' => $e->getMessage()));
            }
        } else {
            wp_send_json_error(array('message' => 'Plugin installation failed.'));
        }
    }

    public function hab_dismiss_review_banner() {
        if (!check_ajax_referer('hab_dismiss_review_nonce', 'nonce', false)) {
            wp_send_json_error('Invalid nonce');
        }

        $user_id = get_current_user_id();
        $dismiss_type = sanitize_text_field($_POST['dismiss_type']);

        if ($dismiss_type === 'permanent') {
            update_user_meta($user_id, 'hab_hide_review_banner', 'permanent');
        } else if ($dismiss_type === '30days') {
            update_user_meta($user_id, 'hab_hide_review_until', time() + (30 * DAY_IN_SECONDS));
        } else if ($dismiss_type === 'now') {
            // Set to show again after 15 days
            update_user_meta($user_id, 'hab_hide_review_until', time() + (15 * DAY_IN_SECONDS));
        }

        wp_send_json_success();
    }
}
