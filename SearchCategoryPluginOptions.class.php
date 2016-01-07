<?php

define('SC_OPTION_GROUP', 'searchcategory-optiongroup');
define('SC_OPTION', 'searchcategory');
define('SC_TRANSLATE_SECTION', 'searchcategory-translatesection');
define('SC_GENERAL_SECTION', 'searchcategory-generalsection');
define('SC_PAGE', 'searchcategory-adminpage');

class SearchCategoryPluginOptions
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Search Category',
            'manage_options',
            SC_PAGE,
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'searchcategory_settings' );
        ?>
        <div class="wrap">
            <h1>Search Category Plugin Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( SC_OPTION_GROUP );
                do_settings_sections( SC_PAGE );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            SC_OPTION_GROUP, // Option group
            'searchcategory_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        //GENERAL
        add_settings_section(
            SC_GENERAL_SECTION, // ID
            'General', // Title
            array( $this, 'print_section_info' ), // Callback
            SC_PAGE // Page
        );

        /*add_settings_field(
            'add-to-category-page', // ID
            'Add widget to category page', // Title
            array( $this, 'addtocategorypage_callback' ), // Callback
            SC_PAGE, // Page
            SC_GENERAL_SECTION // Section
        );*/

        add_settings_field(
            'all-words', // ID
            'All-words search', // Title
            array( $this, 'allwords_callback' ), // Callback
            SC_PAGE, // Page
            SC_GENERAL_SECTION // Section
        );

        //TEXT
        add_settings_section(
            SC_TRANSLATE_SECTION, // ID
            'Text Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            SC_PAGE // Page
        );

        add_settings_field(
            'placeholder-text', // ID
            'Field Placeholder Text', // Title
            array( $this, 'placeholdertext_callback' ), // Callback
            SC_PAGE, // Page
            SC_TRANSLATE_SECTION // Section
        );

        add_settings_field(
            'submit-text',
            'Submit Button Text',
            array( $this, 'submittext_callback' ),
            SC_PAGE,
            SC_TRANSLATE_SECTION
        );

        add_settings_field(
            'clear-text',
            'Clear Button Text',
            array( $this, 'cleartext_callback' ),
            SC_PAGE,
            SC_TRANSLATE_SECTION
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
      $new_input = array();
      if( isset( $input['add-to-category-page'] ) )
        $new_input['add-to-category-page'] = $input['add-to-category-page'] ? true : false;

      if( isset( $input['all-words'] ) )
        $new_input['all-words'] = $input['all-words'] ? true : false;

      $textfields = array("clear-text", "submit-text", "placeholder-text");
      foreach ($textfields as $field) {
        if( isset( $input[$field] ) )
          $new_input[$field] = sanitize_text_field( $input[$field] );
      }
      return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        //print 'Enter your settings below:';
    }

    public function placeholdertext_callback()
    {
        printf(
            '<input type="text" id="placeholder-text" name="searchcategory_settings[placeholder-text]" value="%s" />',
            isset( $this->options['placeholder-text'] ) ? esc_attr( $this->options['placeholder-text']) : ''
        );
    }

    public function cleartext_callback()
    {
        printf(
            '<input type="text" id="clear-text" name="searchcategory_settings[clear-text]" value="%s" />',
            isset( $this->options['clear-text'] ) ? esc_attr( $this->options['clear-text']) : ''
        );
    }

    public function submittext_callback()
    {
        printf(
            '<input type="text" id="submit-text" name="searchcategory_settings[submit-text]" value="%s" />',
            isset( $this->options['submit-text'] ) ? esc_attr( $this->options['submit-text']) : ''
        );
    }

    /*
    public function addtocategorypage_callback()
    {
        echo "<div>Widget will be inserted to the top of category pages. Alternatively, you can use the <em>&lt;search-category /&gt;</em> shortcode.</div><br />";
        printf(
            '<input type="checkbox" id="add-to-category-page" name="searchcategory_settings[add-to-category-page]" value="1" %s />',
            isset( $this->options['add-to-category-page'] ) ? 'checked' : ''
        );
        echo "Enabled";
    }*/

    public function allwords_callback()
    {
        echo "<div>Matches all query words (AND operator - default is OR)</div><br />";
        printf(
            '<input type="checkbox" id="all-words" name="searchcategory_settings[all-words]" value="1" %s />',
            isset( $this->options['all-words'] ) ? 'checked' : ''
        );
        echo "Enabled";
    }

}
