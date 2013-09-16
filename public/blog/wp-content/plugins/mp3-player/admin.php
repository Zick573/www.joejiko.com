<?php
    /**
    * Administration functions for loading and displaying the settings page and saving settings 
    * are handled in this file.
    *
    * @package mp3-player
    */

    /* Initialize the theme admin functionality. */
    add_action( 'init', 'mp3_player_init' );
    define('mp3_player_DIR',dirname(__FILE__).'/');

    function mp3_player_init() {
        add_action( 'admin_menu', 'mp3_player_settings_page_init' );

        add_action( 'mp3_player_update_settings_page', 'mp3_player_save_settings' );
    }

    /**
    * Sets up the mp3-player settings page and loads the appropriate functions when needed.
    *
    * @since 0.8
    */
    function mp3_player_settings_page_init() {
        $mp3_player=new stdClass();
        global $mp3_player;

        /* Create the theme settings page. */
        $mp3_player->settings_page = add_options_page( __( 'Mp3 Player', 'mp3-player' ), __( 'Mp3 Player', 'mp3-player' ), 'edit_theme_options', 'mp3-player', 'mp3_player_settings_page' );



        /* Register the default theme settings meta boxes. */
        add_action( "load-{$mp3_player->settings_page}", 'mp3_player_create_settings_meta_boxes' );

        /* Make sure the settings are saved. */
        add_action( "load-{$mp3_player->settings_page}", 'mp3_player_load_settings_page' );

        /* Load the JavaScript and stylehsheets needed for the theme settings. */
        add_action( "load-{$mp3_player->settings_page}", 'mp3_player_settings_page_enqueue_script' );
        add_action( "load-{$mp3_player->settings_page}", 'mp3_player_settings_page_enqueue_style' );
        add_action( "admin_head-{$mp3_player->settings_page}", 'mp3_player_settings_page_load_scripts' );
    }

    /**
    * Returns an array with the default plugin settings.
    *
    * @since 0.8
    */
    function mp3_player_settings() {
        $settings = array(
        'mp3_player_width'=>false,
        );
        return apply_filters( 'mp3_player_settings', $settings );
    }

    /**
    * Function run at load time of the settings page, which is useful for hooking save functions into.
    *
    * @since 0.8
    */
    function mp3_player_load_settings_page() {

        /* Get theme settings from the database. */
        $settings = get_option( 'mp3_player_settings' );

        /* If no settings are available, add the default settings to the database. */
        if ( empty( $settings ) ) {
            add_option( 'mp3_player_settings', mp3_player_settings(), '', 'yes' );

            /* Redirect the page so that the settings are reflected on the settings page. */
            wp_redirect( admin_url( 'options-general.php?page=mp3-player' ) );
            exit;
        }

        /* If the form has been submitted, check the referer and execute available actions. */
        elseif ( isset( $_POST['mp3-player-settings-submit'] ) ) {

            /* Make sure the form is valid. */
            check_admin_referer( 'mp3-player-settings-page' );

            /* Available hook for saving settings. */
            do_action( 'mp3_player_update_settings_page' );

            /* Redirect the page so that the new settings are reflected on the settings page. */
            wp_redirect( admin_url( 'options-general.php?page=mp3-player&updated=true' ) );
            exit;
        }
    }




    

    /**
    * Validates the plugin settings.
    *
    * @since 0.8
    */
    function mp3_player_save_settings() {

        /* Get the current theme settings. */
        $settings = get_option( 'mp3_player_settings' );

        $settings['mp3_player_soundcloud_CLIENT_ID'] = esc_html( $_POST['mp3_player_soundcloud_CLIENT_ID'] ); 




        /* Update the theme settings. */
        $updated = update_option( 'mp3_player_settings', $settings );



    }

    /**
    * Registers the plugin meta boxes for use on the settings page.
    *
    * @since 0.8
    */
    function mp3_player_create_settings_meta_boxes() {
        global $mp3_player;

        add_meta_box( 'mp3-player-about-meta-box', __( 'About Mp3 player', 'mp3-player' ), 'mp3_player_about_meta_box', $mp3_player->settings_page, 'normal', 'high' );

        add_meta_box( 'mp3-player-general-meta-box2', __( 'Mp3 player Setttings', 'mp3-player' ), 'mp3_player_general_meta_box', $mp3_player->settings_page, 'normal', 'high' );

    }

    /**
    * Displays the about meta box.
    *
    * @since 0.8
    */
    function mp3_player_about_meta_box() {
        $plugin_data = get_plugin_data( mp3_player_DIR . 'mp3_player.php' ); 
        echo 'Made by Simon Hansen. ';

 }

    /**
    * Displays the gallery settings meta box.
    *
    * @since 0.8
    */
    function mp3_player_general_meta_box() {

        echo 'Go <a href="http://developers.soundcloud.com" target="_blank" >here</a> to register a CLIENT_ID for soundcloud';
   
        $settings=get_option( 'mp3_player_settings' );


    ?>

    <table class="form-table">

        <tr>
            <th><?php _e( 'Soundcloud CLIENT_ID:', 'mp3-player' ); ?></th>
            <td>
                <input style="width:260px" id="mp3-player_soundcloud_CLIENT_ID" name="mp3_player_soundcloud_CLIENT_ID" type="input"  value="<?php echo $settings['mp3_player_soundcloud_CLIENT_ID' ]; ?>" /> 
                <label for="mp3_player_soundcloud_CLIENT_ID"><?php _e( 'Paste your CLIENT_ID from soundcloud', 'mp3-player' ); ?></label>
            </td>
        </tr>


    </table><!-- .form-table --><?php
    }




    /**
    * Displays a settings saved message.
    *
    * @since 0.8
    */
    function mp3_player_settings_update_message() { ?>
    <p class="updated fade below-h2" style="padding: 5px 10px;">
        <strong><?php _e( 'Settings saved.', 'mp3-player' ); ?></strong>
    </p><?php
    }

    /**
    * Outputs the HTML and calls the meta boxes for the settings page.
    *
    * @since 0.8
    */
    function mp3_player_settings_page() {
        global $mp3_player;

        $plugin_data = get_plugin_data( mp3_player_DIR . 'mp3_player.php' ); ?>

    <div class="wrap">

        <h2><?php _e( 'Mp3 Player Settings', 'mp3-player' ); ?></h2>

        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) mp3_player_settings_update_message(); ?>

        <div id="poststuff">

            <form method="post" action="<?php admin_url( 'options-general.php?page=mp3-player' ); ?>">

                <?php wp_nonce_field( 'mp3-player-settings-page' ); ?>
                <?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
                <?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

                <div class="metabox-holder">
                    <div class="post-box-container column-1 normal"><?php do_meta_boxes( $mp3_player->settings_page, 'normal', $plugin_data ); ?></div>
                    <div class="post-box-container column-2 advanced"><?php do_meta_boxes( $mp3_player->settings_page, 'advanced', $plugin_data ); ?></div>
                    <div class="post-box-container column-3 side"><?php do_meta_boxes( $mp3_player->settings_page, 'side', $plugin_data ); ?></div>
                </div>

                <p class="submit" style="clear: both;">
                    <input type="submit" name="Submit"  class="button-primary" value="<?php _e( 'Update Settings', 'mp3-player' ); ?>" />
                    <input type="hidden" name="mp3-player-settings-submit" value="true" />
                </p><!-- .submit -->

            </form>

        </div><!-- #poststuff -->

    </div><!-- .wrap --><?php
    }

    /**
    * Loads the scripts needed for the settings page.
    *
    * @since 0.8
    */
    function mp3_player_settings_page_enqueue_script() {
        wp_enqueue_script( 'common' );
        wp_enqueue_script( 'wp-lists' );
        wp_enqueue_script( 'postbox' );
    }

    /**
    * Loads the stylesheets needed for the settings page.
    *
    * @since 0.8
    */
    function mp3_player_settings_page_enqueue_style() {
        wp_enqueue_style( 'mp3-player-admin', mp3_player_URL . 'admin.css', false, 0.7, 'screen' );
    }

    /**
    * Loads the metabox toggle JavaScript in the settings page head.
    *
    * @since 0.8
    */
    function mp3_player_settings_page_load_scripts() {
        global $mp3_player; ?>
    <script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready( function($) {
            $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
            postboxes.add_postbox_toggles( '<?php echo $mp3_player->settings_page; ?>' );
        });
        //]]>
    </script><?php
    }

?>