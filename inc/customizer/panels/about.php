<?php
/**
 * About Section Customizer Options
 *
 * @since 1.0.0
 */
 
 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
/**
 * Variable
 */
$panel  = 'fargo_panel_about';
$prefix = 'fargo';

/**
 * Panel
 */
$wp_customize->add_section( $panel, array(
	'priority'              => fargo_get_section_position($panel),
	'capability'            => 'edit_theme_options',
	'theme_supports'        => '',
	'title'                 => esc_html__( 'About', 'fargo' ),
	'panel'			        => 'fargo_frontpage_panel'
) );

/**
 * Tabs
 */
$wp_customize->add_setting( $prefix . '_about_tab', array(
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_kses_post',
    )
);

$wp_customize->add_control( new Fargo_Customizer_Tab_Control( $wp_customize, $prefix . '_about_tab', array(
	'type'                  => 'epsilon-tab',
	'section'               => $panel,
	'buttons'               => array(
		array(
			'name'          => esc_html__( 'General', 'fargo' ),
			'fields'        => array(
				$prefix . '_about_general_enable',
				$prefix . '_about_general_title',
				$prefix . '_about_general_subtitle',
				$prefix . '_about_general_entry',
				$prefix . '_about_general_content',
				),
			'active'        => true
		),
		array(
			'name'          => esc_html__( 'Settings', 'fargo' ),
			'fields'        => array(
				$prefix . '_about_settings_width',
				$prefix . '_about_settings_position',
			),
		),
		array(
			'name'          => esc_html__( 'Colors', 'fargo' ),
			'fields'        => array(
				$prefix . '_about_colors_background',
				$prefix . '_about_colors_title',
				$prefix . '_about_colors_subtitle',
			),
		),
		array(
			'name'          => esc_html__( 'Backgrounds', 'fargo' ),
			'fields'        => array(
				$prefix . '_about_backgrounds_image',
				$prefix . '_about_backgrounds_position',
				$prefix . '_about_backgrounds_size',
				$prefix . '_about_backgrounds_repeat',
				$prefix . '_about_backgrounds_attachment',
				$prefix . '_about_backgrounds_overlay',
				$prefix . '_about_backgrounds_overlay_color',  
			),
		),
	),
) ) );

/**
 * GENERAL
 */

/**
 * Section Enable
 */
$wp_customize->add_setting( $prefix . '_about_general_enable', array(
	'sanitize_callback'     => 'fargo_sanitize_checkbox',
	'default'               => 1,
) );
	
$wp_customize->add_control( new Fargo_Customizer_Toggle_Control( $wp_customize, $prefix . '_about_general_enable', array(
	'type'                  => 'mte-toggle',
	'label'                 => esc_html__( 'Section Enable', 'fargo' ),
	'section'               => $panel,
	'settings'              => $prefix . '_about_general_enable',
) ) );

/**
 * Title
 */
$wp_customize->add_setting( $prefix . '_about_general_title', array(
	'transport'             => 'postMessage',
	'sanitize_callback'	    => 'sanitize_text_field',
	'default'               => esc_html__( 'ABOUT', 'fargo' ),
) );
	
$wp_customize->add_control( $prefix . '_about_general_title', array(
	'label'                 => esc_html__( 'Title', 'fargo' ),
	'section'               => $panel,
	'settings'              => $prefix . '_about_general_title',
) );
	
$wp_customize->selective_refresh->add_partial( $prefix .'_about_general_title', array(
    'selector' => '#about .section-header h2',
) );

/**
 * Sub Title
 */
$wp_customize->add_setting( $prefix . '_about_general_subtitle', array(
	'transport'             => 'postMessage',
	'sanitize_callback'     => 'wp_kses_post',
	'default'               => esc_html__( 'This is a description for the About section. You can set it up in the Customizer > Front Page Sections > About.', 'fargo' ),
) );
		
$wp_customize->add_control( $prefix . '_about_general_subtitle', array(
	'label'                 => esc_html__( 'Subtitle', 'fargo' ),
	'section'               => $panel,
	'settings'              => $prefix . '_about_general_subtitle'
) );

$wp_customize->selective_refresh->add_partial( $prefix .'_about_general_subtitle', array(
	'selector'              => '#about .section-header h5',
) );

/**
 * Entry
 */
$wp_customize->add_setting( $prefix .'_about_general_entry', array(
	'transport'         => 'postMessage',
	'sanitize_callback' => 'wp_kses_post',
	'default'           => '',
) );

$wp_customize->add_control( new Epsilon_Editor_Custom_Control( $wp_customize, $prefix .'_about_general_entry', array(
	'label'                 => esc_html__( 'Entry', 'fargo' ),
	'section'               => $panel,
	'settings'              => $prefix . '_about_general_entry'
) ) );

$wp_customize->selective_refresh->add_partial( $prefix .'_about_general_entry', array(
    'selector'              => '#about',
) );

/**
 * General Content
 */	
$wp_customize->add_setting( $prefix . '_about_general_content', array(
	'transport'             => $selective_refresh ? 'postMessage' : 'refresh',
) );

$hestia_about_content_control = $wp_customize->get_setting( $prefix .'_about_general_content' );
if ( ! empty( $hestia_about_content_control ) ) {
$hestia_about_content_control->default = json_encode( array(
				array(
				'icon_value' => 'fa-wechat',
				'title'      => esc_html__( 'Responsive', 'fargo' ),
				'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'fargo' ),
				'link'       => '#',
				'id'         => 'customizer_repeater_56d7ea7f40b56',
				'color'      => '#e91e63',
				),
				array(
				'icon_value' => 'fa-check',
				'title'      => esc_html__( 'Quality', 'fargo' ),
				'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'fargo' ),
				'link'       => '#',
				'id'         => 'customizer_repeater_56d7ea7f40b66',
				'color'      => '#00bcd4',
				),
				array(
				'icon_value' => 'fa-support',
				'title'      => esc_html__( 'Support', 'fargo' ),
				'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'fargo' ),
				'link'       => '#',
				'id'         => 'customizer_repeater_56d7ea7f40b86',
				'color'      => '#4caf50',
				),
				array(
				'icon_value' => 'fa-support',
				'title'      => esc_html__( 'Support', 'fargo' ),
				'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'fargo' ),
				'link'       => '#',
				'id'         => 'customizer_repeater_56d7ea7f40b86',
				'color'      => '#4caf50',
				),
				) );
    }
			
$wp_customize->add_control( new Fargo_Repeater( $wp_customize, $prefix . '_about_general_content', array(
				'label'                             => esc_html__( 'Skills Content', 'fargo' ),
				'section'                           => $panel,
				'add_field_label'                   => esc_html__( 'Add Skills', 'fargo' ),
				'item_name'                         => esc_html__( 'Skill', 'fargo' ),
				'customizer_repeater_icon_control'  => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_color_control' => true,
			
			) ) );

function about_general_content_callback() {
	$about_general_content = get_theme_mod( $prefix . '_about_general_content' );
	about_general_content( $about_general_content, true );
}

/**
 * SETTINGS
 */

/**
 * Full Width
 */
$wp_customize->add_setting( $prefix . '_about_settings_width', array(
    'transport'             => 'postMessage',
	'sanitize_callback'     => 'fargo_sanitize_checkbox',
	'default'               => 0,
) );
	
$wp_customize->add_control( new Fargo_Customizer_Toggle_Control( $wp_customize, $prefix . '_about_settings_width', array(
	'type'                  => 'mte-toggle',
	'label'                 => esc_html__( 'Full Width', 'fargo' ),
	'section'               => $panel,
	'settings'              => $prefix . '_about_settings_width',
) ) );




/**
 * Background Size
 */
$wp_customize->add_setting( $prefix . '_about_settings_position', array(
    'default'               => 'auto',
    'sanitize_callback'     => 'sanitize_text_field',
    'transport'             => 'postMessage',
) );

$wp_customize->add_control( $prefix . '_about_settings_position', array(
    'label'                 => esc_html__( 'Position', 'fargo' ),
	'type'                  => 'select',
    'section'               => $panel,
	'settings'              => $prefix . '_about_settings_position',
    'choices'               => array(
        'auto'              => esc_html__( 'Left Text Right Skill', 'fargo' ),
        'sogo'              => esc_html__( 'Right Skill Left Text', 'fargo' ),
    ),
) );

/**
 * COLORS
 */

/**
 * Background Colors
 */
$wp_customize->add_setting( $prefix . '_about_colors_background', array(
    'transport'             => 'postMessage',
    'sanitize_callback'     => 'fargo_sanitize_color',
	'default'               => '#ffffff',

) );

$wp_customize->add_control( new Fargo_Customizer_Color_Control( $wp_customize, $prefix . '_about_colors_background', array(
    'label'                 => esc_html__( 'Background Color', 'fargo' ),
    'section'               => $panel,
    'settings'              => $prefix . '_about_colors_background',
) ) );

/**
 * Title Colors
 */
$wp_customize->add_setting( $prefix . '_about_colors_title', array(
    'transport'             => 'postMessage',
    'sanitize_callback'     => 'fargo_sanitize_color',
    'default'               => '#ffffff',
) );

$wp_customize->add_control( new Fargo_Customizer_Color_Control( $wp_customize, $prefix . '_about_colors_title', array(
    'label'                 => esc_html__( 'Title Color', 'fargo' ),
    'section'               => $panel,
    'settings'              => $prefix . '_about_colors_title',
) ) );

/**
 * Sub Title Colors
 */
$wp_customize->add_setting( $prefix . '_about_colors_subtitle', array(
    'transport'             => 'postMessage',
    'sanitize_callback'     => 'fargo_sanitize_color',
    'default'               => '#ffffff',
) );

$wp_customize->add_control( new Fargo_Customizer_Color_Control( $wp_customize, $prefix . '_about_colors_subtitle', array(
    'label'                 => esc_html__( 'Subtitle Color', 'fargo' ),
    'section'               => $panel,
    'settings'              => $prefix . '_about_colors_subtitle',
) ) );

	
/**
 * BACKGROUNDS
 */

/**
 * Background Image
 */
$wp_customize->add_setting( $prefix . '_about_backgrounds_image', array(
    'transport'             => 'postMessage',
    'sanitize_callback'     => 'esc_url',
	'default'               => '',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $prefix . '_about_backgrounds_image', array(
    'label'                 => esc_html__( 'Background Image', 'fargo' ),
    'section'               => $panel,
    'settings'              => $prefix . '_about_backgrounds_image',
) ) );

/**
 * Background Position
 */
$wp_customize->add_setting( $prefix . '_about_backgrounds_position', array(
    'transport'             => 'postMessage',
    'sanitize_callback'     => 'sanitize_text_field',
	'default'               => 'initial',
) );

$wp_customize->add_control( $prefix . '_about_backgrounds_position', array(
    'label'                 => esc_html__( 'Background Position', 'fargo' ),
	'type'                  => 'select',
    'section'               => $panel,
	'settings'              => $prefix . '_about_backgrounds_position',
    'choices'               => array(
		'initial' 			=> esc_html__( 'Default', 'fargo' ),
		'top left' 			=> esc_html__( 'Top Left', 'fargo' ),
		'top center' 		=> esc_html__( 'Top Center', 'fargo' ),
		'top right'  		=> esc_html__( 'Top Right', 'fargo' ),
		'center left' 		=> esc_html__( 'Center Left', 'fargo' ),
		'center center' 	=> esc_html__( 'Center Center', 'fargo' ),
		'center right' 		=> esc_html__( 'Center Right', 'fargo' ),
		'bottom left' 		=> esc_html__( 'Bottom Left', 'fargo' ),
		'bottom center' 	=> esc_html__( 'Bottom Center', 'fargo' ),
		'bottom right' 		=> esc_html__( 'Bottom Right', 'fargo' ),
    ),
) );

/**
 * Background Size
 */
$wp_customize->add_setting( $prefix . '_about_backgrounds_size', array(
	'transport'             => 'postMessage',
    'sanitize_callback'     => 'sanitize_text_field',
	'default'               => 'auto',
) );

$wp_customize->add_control( $prefix . '_about_backgrounds_size', array(
    'label'      		    => esc_html__( 'Background Size', 'fargo' ),
	'type'                  => 'select',
    'section'               => $panel,
	'settings'              => $prefix . '_about_backgrounds_size',
    'choices'               => array(
        'auto'              => esc_html__( 'Default', 'fargo' ),
        'contain'           => esc_html__( 'Fit to Screen', 'fargo' ),
        'cover'             => esc_html__( 'Fill Screen', 'fargo' ),
    ),
) );

/**
 * Background Repeat
 */
$wp_customize->add_setting( $prefix . '_about_backgrounds_repeat', array(
    'transport'             => 'postMessage',
    'sanitize_callback'     => 'sanitize_text_field',
	'default'               => 'initial',
) );

$wp_customize->add_control( $prefix . '_about_backgrounds_repeat', array(
    'label'     		    => esc_html__( 'Background Repeat', 'fargo' ),
	'type'                  => 'select',
    'section'               => $panel,
	'settings'              => $prefix . '_about_backgrounds_repeat',
    'choices'               => array(
	    'initial'        	=> esc_html__( 'Default', 'fargo' ),
		'no-repeat'         => esc_html__( 'No-repeat', 'fargo' ),
		'repeat' 	        => esc_html__( 'Repeat', 'fargo' ),
		'repeat-x'      	=> esc_html__( 'Repeat-x', 'fargo' ),
		'repeat-y'      	=> esc_html__( 'Repeat-y', 'fargo' ),
    ),
) );

/**
 * Background Attachment
 */
$wp_customize->add_setting( $prefix . '_about_backgrounds_attachment', array(
    'transport'        		=> 'postMessage',
    'sanitize_callback' 	=> 'sanitize_text_field',
	'default'               => 'initial',
) );

$wp_customize->add_control( $prefix . '_about_backgrounds_attachment', array(
    'label'                 => esc_html__( 'Background Attachment', 'fargo' ),
	'type'                  => 'select',
    'section'               => $panel,
	'settings'              => $prefix . '_about_backgrounds_attachment',
    'choices'               => array(
		'initial'         	=> esc_html__( 'Default', 'fargo' ),
		'scroll' 	        => esc_html__( 'Scroll', 'fargo' ),
		'fixed' 	        => esc_html__( 'Fixed', 'fargo' )
    ),
) );

/**
 * Background Overlay Enable
 */
$wp_customize->add_setting( $prefix . '_about_backgrounds_overlay', array(
    'transport'             => 'postMessage',
	'sanitize_callback'     => 'fargo_sanitize_checkbox',
	'default'               => 0,
) );
	
$wp_customize->add_control( new Fargo_Customizer_Toggle_Control( $wp_customize, $prefix . '_about_backgrounds_overlay', array(
	'label'                 => esc_html__( 'Overlay Enable', 'fargo' ),
	'type'                  => 'mte-toggle',
	'section'               => $panel,
	'settings'              => $prefix . '_about_backgrounds_overlay',
) ) );

/**
 * Background Overlay Color
 */
$wp_customize->add_setting( $prefix . '_about_backgrounds_overlay_color', array(
    'transport'             => 'postMessage',
	'sanitize_callback'     => 'fargo_sanitize_color',
	'default'               => '#ffffff',		
) );

$wp_customize->add_control( new Fargo_Customizer_Color_Control( $wp_customize, $prefix . '_about_backgrounds_overlay_color', array(
    'label'                 => esc_html__( 'Overlay Color', 'fargo' ),
    'section'               => $panel,
    'settings'              => $prefix . '_about_backgrounds_overlay_color',
) ) );