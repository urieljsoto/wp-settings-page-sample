<?php
if (!defined('ABSPATH')) {
    exit;
}

class Uriel_Settings_Page
{
    private $tabs = [];

    /**
     * Class constructor.
     *
     * Initializes the settings page object with the provided tabs.
     *
     * @param array $tabs An array of tabs to be displayed on the settings page.
     */
    public function __construct($tabs)
    {
        $this->tabs = $tabs;
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_init', [$this, 'handle_form_submit']);
    }

    /**
     * Registers the settings for the plugin.
     *
     * This method is responsible for registering the settings sections and fields
     * for the plugin's settings page. It loops through the tabs, sections, and fields
     * defined in the $tabs property and adds them using the WordPress settings API.
     *
     * @return void
     */
    public function register_settings()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        foreach ($this->tabs as $tab_key => $tab) {
            foreach ($tab['sections'] as $section) {
                add_settings_section(
                    $section['id'],
                    isset($section['title']) ? $section['title'] : '',
                    isset($section['callback']) ? $section['callback'] : '__return_null',
                    $tab_key
                );

                foreach ($section['fields'] as $field) {
                    add_settings_field(
                        $field['id'],
                        $field['title'],
                        $field['callback'],
                        $tab_key,
                        $section['id']
                    );
                }
            }
        }
    }

    /**
     * Handles the form submission and updates the plugin settings.
     *
     * This method is responsible for handling the form submission and updating the plugin settings
     * based on the submitted data. It checks if the request method is POST and if the submitted
     * nonce is valid. It also checks if the current user has the capability to manage options.
     * If all conditions are met, it retrieves the submitted data from the form fields, sanitizes
     * the values, and updates the plugin settings using the `update_option` function.
     *
     * @return void
     */
    public function handle_form_submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uriel_form_nonce']) && wp_verify_nonce($_POST['uriel_form_nonce'], 'uriel_form_action')) {
            if (!current_user_can('manage_options')) {
                return;
            }

            $settings_data = [];
            foreach ($this->tabs as $tab_key => $tab) {
                foreach ($tab['sections'] as $section) {
                    foreach ($section['fields'] as $field) {
                        if (isset($_POST[$field['name']])) {
                            $settings_data[$field['name']] = sanitize_text_field($_POST[$field['name']]);
                        }
                    }
                }
            }

            update_option('uriel_settings', $settings_data);
        }
    }

    /**
     * Creates a text field HTML element.
     *
     * @param string $name The name attribute of the input field.
     * @param string $value The value attribute of the input field.
     * @param string $placeholder The placeholder attribute of the input field.
     * @return void
     */
    public static function create_text_field($name, $value = '', $placeholder = '')
    {
        echo '<input type="text" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($placeholder) . '">';
    }
}

/**
 *This file contains an array definition for the $tabs variable, which is used to define the settings tabs and fields for the plugin's settings page.
 */
$tabs = [
    'uriel_general_settings' => [
        'sections' => [
            [
                'id' => 'uriel_general_section',
                'title' => 'General Settings',
                'callback' => '__return_null',
                'fields' => [
                    [
                        'id' => 'uriel_text_field_1',
                        'title' => 'Text Field 1',
                        'callback' => function () {
                            Uriel_Settings_Page::create_text_field('uriel_text_field_1', get_option('uriel_settings')['uriel_text_field_1'] ?? '', 'Enter text here');
                        },
                        'name' => 'uriel_text_field_1',
                    ],
                    [
                        'id' => 'uriel_text_field_2',
                        'title' => 'Text Field 2',
                        'callback' => function () {
                            Uriel_Settings_Page::create_text_field('uriel_text_field_2', get_option('uriel_settings')['uriel_text_field_2'] ?? '', 'Enter text here');
                        },
                        'name' => 'uriel_text_field_2',
                    ],
                ],
            ],
        ],
    ],
];

new Uriel_Settings_Page($tabs);

/**
 * Renders the Uriel Settings page.
 *
 * This function is responsible for rendering the settings page for the Uriel plugin.
 * It displays a form with various settings fields and a submit button.
 *
 * @since 1.0.0
 */

function uriel_settings_page()
{
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
    ?>
    <div class="wrap">
        <h2>Uriel Settings</h2>
        <h2 class="nav-tab-wrapper">
            <a href="?page=uriel-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
        </h2>
        <form method="post" action="">
            <?php wp_nonce_field('uriel_form_action', 'uriel_form_nonce'); ?>
            <?php
            settings_fields('uriel_general_settings');
            do_settings_sections('uriel_general_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
