<?php
/*
Plugin Name: PoppityExity
Plugin URI: https://github.com/alexchuc/PoppityExity
Description: A WordPress plugin that displays a popup modal when users attempt to leave the page.
Version: 1.0.0
Author: <a href="https://github.com/AC-DAC" target="_blank">Alex Chuc</a>
Requires at least: 5.6
Requires PHP: 7.4
License: GPL 2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin class
class PoppityExity {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('wp_footer', array($this, 'render_modal'));
    }

    public function add_admin_menu() {
        add_options_page(
            __('PoppityExity Settings', 'poppity-exity'),
            __('PoppityExity', 'poppity-exity'),
            'manage_options',
            'poppity-exity',
            array($this, 'render_settings_page')
        );
    }

    public function register_settings() {
        register_setting('poppity_exity_options', 'poppity_exity_content');
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('poppity_exity_options');
                do_settings_sections('poppity-exity');
                ?>
                <div class="poppity-exity-editor">
                    <?php
                    $content = get_option('poppity_exity_content', '');
                    wp_editor($content, 'poppity_exity_content', array(
                        'media_buttons' => true,
                        'textarea_name' => 'poppity_exity_content',
                        'editor_height' => 300,
                        'teeny' => false
                    ));
                    ?>
                </div>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'poppity-exity-style',
            plugins_url('css/poppity-exity.css', __FILE__),
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'poppity-exity-script',
            plugins_url('js/poppity-exity.js', __FILE__),
            array('jquery'),
            '1.0.0',
            true
        );
    }

    public function render_modal() {
        $content = get_option('poppity_exity_content', '');
        if (empty($content)) {
            return;
        }
        ?>
        <div id="poppity-exity-modal" class="poppity-exity-modal">
            <div class="poppity-exity-modal-content">
                <span class="poppity-exity-close">&times;</span>
                <div class="poppity-exity-content">
                    <?php echo do_shortcode(wp_kses_post($content)); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

// Initialize the plugin
PoppityExity::get_instance();