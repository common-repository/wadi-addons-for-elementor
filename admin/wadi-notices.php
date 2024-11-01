<?php
/**
 * Class Wadi Admin Helpers
 */

namespace WadiAddons\Admin;

use WadiAddons\Admin\Admin_Helper;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Class Wadi_Notices
 */
class Wadi_Notices
{

    /**
	 * Class object
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Elementor slug
	 *
	 * @var elementor
	 */
	private static $elementor = 'elementor';
/**
	 * Notices Keys
	 *
	 * @var notices
	 */
	private static $notices = null;

	/**
	 * Constructor for the class
	 */
	public function __construct() {


		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

	}


    /**
	 * Required plugin check
	 *
	 * Shows an admin notice when Elementor is missing.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function required_plugins_check() {

		$elementor_path = sprintf( '%1$s/%1$s.php', self::$elementor );

		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {

			if ( ! Admin_Helper::is_plugin_installed( $elementor_path ) ) {

				if ( Admin_Helper::check_user_can( 'install_plugins' ) ) {

					$install_url = wp_nonce_url( self_admin_url( sprintf( 'update.php?action=install-plugin&plugin=%s', self::$elementor ) ), 'install-plugin_elementor' );

					$message = sprintf( '<p>%s</p>', __( 'Wadi Addons for Elementor is not working because you need to Install Elementor plugin.', 'wadi-addons' ) );

					$message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, __( 'Install Now', 'wadi-addons' ) );

				}
			} else {

				if ( Admin_Helper::check_user_can( 'activate_plugins' ) ) {

					$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor_path . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor_path );

					$message = '<p>' . __( 'Wadi Addons for Elementor is not working because you need to activate Elementor plugin.', 'wadi-addons' ) . '</p>';

					$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Now', 'wadi-addons' ) ) . '</p>';

				}
			}
			$this->render_admin_notices( $message );
		}
	}

    /**
	 * Renders an admin notice error message
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $message notice text.
	 * @param string $class notice class.
	 * @param string $handle notice handle.
	 *
	 * @return void
	 */
	private function render_admin_notices( $message, $class = '', $handle = '' ) {
		?>
			<div class="error wadi_admin_warning_notice <?php echo esc_attr( $class ); ?>" data-notice="<?php echo esc_attr( $handle ); ?>">
				<?php echo wp_kses_post( $message ); ?>
			</div>
		<?php
	}


	/**
	 * init notices check functions
	 */
	public function admin_notices() {

		$this->required_plugins_check();

	}

    /**
     * Creates and returns an instance of the class
     *
     * @since 1.0.0
     * @access public
     *
     * @return object
     */
    public static function get_instance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

