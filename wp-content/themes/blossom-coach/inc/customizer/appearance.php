<?php
/**
 * Blossom Coach Appearance Settings
 *
 * @package Blossom_Coach
 */

function blossom_coach_customize_register_appearance( $wp_customize ) {
    
    /** Appearance Settings */
    $wp_customize->add_panel( 
        'appearance_settings',
         array(
            'priority'    => 50,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Appearance Settings', 'blossom-coach' ),
            'description' => __( 'Customize Typography, Background Image & Color.', 'blossom-coach' ),
        ) 
    );
    
    /** Typography */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __( 'Typography', 'blossom-coach' ),
            'priority' => 20,
            'panel'    => 'appearance_settings',
        )
    );
    
    /** Primary Font */
    $wp_customize->add_setting(
		'primary_font',
		array(
			'default'			=> 'Nunito Sans',
			'sanitize_callback' => 'blossom_coach_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'primary_font',
    		array(
                'label'	      => __( 'Primary Font', 'blossom-coach' ),
                'description' => __( 'Primary font of the site.', 'blossom-coach' ),
    			'section'     => 'typography_settings',
    			'choices'     => blossom_coach_get_all_fonts(),	
     		)
		)
	);
    
    /** Secondary Font */
    $wp_customize->add_setting(
		'secondary_font',
		array(
			'default'			=> 'Nunito',
			'sanitize_callback' => 'blossom_coach_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'secondary_font',
    		array(
                'label'	      => __( 'Secondary Font', 'blossom-coach' ),
                'description' => __( 'Secondary font of the site.', 'blossom-coach' ),
    			'section'     => 'typography_settings',
    			'choices'     => blossom_coach_get_all_fonts(),	
     		)
		)
	);

    $wp_customize->add_setting(
        'ed_localgoogle_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_coach_sanitize_checkbox',
            'priority'          => 20,
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Coach_Toggle_Control( 
            $wp_customize,
            'ed_localgoogle_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Load Google Fonts Locally', 'blossom-coach' ),
                'description'   => __( 'Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'blossom-coach' )
            )
        )
    );   

    $wp_customize->add_setting(
        'ed_preload_local_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_coach_sanitize_checkbox',
            'priority'          => 30,
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Coach_Toggle_Control( 
            $wp_customize,
            'ed_preload_local_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Preload Local Fonts', 'blossom-coach' ),
                'description'   => __( 'Preloading Google fonts will speed up your website speed.', 'blossom-coach' ),
                'active_callback' => 'blossom_coach_ed_localgoogle_fonts'
            )
        )
    );   

    ob_start(); ?>
        
        <span style="margin-bottom: 5px;display: block;"><?php esc_html_e( 'Click the button to reset the local fonts cache', 'blossom-coach' ); ?></span>
        
        <input type="button" class="button button-primary blossom-coach-flush-local-fonts-button" name="blossom-coach-flush-local-fonts-button" value="<?php esc_attr_e( 'Flush Local Font Files', 'blossom-coach' ); ?>" />
    <?php
    $blossom_coach_flush_button = ob_get_clean();

    $wp_customize->add_setting(
        'ed_flush_local_fonts',
        array(
            'sanitize_callback' => 'wp_kses_post',
            'priority'          => 40,
        )
    );
    
    $wp_customize->add_control(
        'ed_flush_local_fonts',
        array(
            'label'         => __( 'Flush Local Fonts Cache', 'blossom-coach' ),
            'section'       => 'typography_settings',
            'description'   => $blossom_coach_flush_button,
            'type'          => 'hidden',
            'active_callback' => 'blossom_coach_ed_localgoogle_fonts'
        )
    );
    
    /** Move Background Image section to appearance panel */
    $wp_customize->get_section( 'colors' )->panel              = 'appearance_settings';
    $wp_customize->get_section( 'colors' )->priority           = 10;
    $wp_customize->get_section( 'background_image' )->panel    = 'appearance_settings';
    $wp_customize->get_section( 'background_image' )->priority = 15;

    /** Color */

    /** Note */
    $wp_customize->add_setting(
        'color_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Coach_Note_Control( 
            $wp_customize,
            'color_text',
            array(
                'section'     => 'colors',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-coach' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-coach-pro/?utm_source=blossom_coach&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'color_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_coach_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Coach_Radio_Image_Control(
            $wp_customize,
            'color_settings',
            array(
                'section'     => 'colors',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/colors-settings.png',
                ),
            )
        )
    );

     /** Typography */

     /** Note */
     $wp_customize->add_setting(
        'typpography_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Coach_Note_Control( 
            $wp_customize,
            'typpography_text',
            array(
                'section'     => 'typography_settings',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-coach' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-coach-pro/?utm_source=blossom_coach&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'typpography_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_coach_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Coach_Radio_Image_Control(
            $wp_customize,
            'typpography_settings',
            array(
                'section'     => 'typography_settings',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/typography.png',
                ),
            )
        )
    );
    
}
add_action( 'customize_register', 'blossom_coach_customize_register_appearance' );