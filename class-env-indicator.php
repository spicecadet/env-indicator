<?php

namespace wpengine;

class EnvIndicator {
    private static $instance = null;
    private $tier = null;
    private $version = '0.2';

    private function __construct() {
        
        /**
         * Set the current tier
         */
        $this->current_tier();

        add_action( 'admin_body_class', array( $this, 'add_admin_body_class' ));
        add_action( 'body_class', array( $this, 'add_body_class' ));
        add_action( 'admin_enqueue_scripts',  array( $this, 'add_stylesheet' ));
        add_action( 'wp_enqueue_scripts', array( $this, 'add_stylesheet' ));
        add_action( 'admin_bar_menu', array( $this, 'update_site_name' ), 99 ); 
    }
    
    /**
     * Get an instance of the EnvIndicator object.
     *
     * This function creates a single instance of the EnvIndicator object.
     *
     * @return object EnvIndicator.
     */
    static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Set the current environment.
     *
     * This function sets a private variable used to determine the current tier.
     *
     * @param string $tier The current environment tier.
     */
    private function set_tier( $tier ) {
        $this->tier = $tier;
    }

    /**
     * Detrmine which environment is being used.
     *
     * Determine the environment based on the existance of WP Engine specific functions for production and staging.
     */
    public function current_tier(){
        if (function_exists( 'is_wpe' ) && is_wpe() ) {
            $tier = 'production';
        } elseif ( function_exists( 'is_wpe_snapshot' ) && is_wpe_snapshot() ) {
            $tier = 'staging';
        }  else {
            $tier = 'development';
        }
        $this->set_tier( $tier );

        //$this->set_tier('staging'); //override tier for testing
        //$this->set_tier('production'); //override tier for testing
    }
    
    /**
     * Add stylesheet for Environment Indicator.
     *
     * Adds a stylesheet to change the admin toolbar and sidebar colors.
     *
     */
    function add_stylesheet() {
        wp_enqueue_style( 'env-indicator', plugins_url('css/env-indicator.css', __FILE__), false, $this->version );
    }
    
    /**
     * Add class to the <body> tag.
     *
     * Adds a class that indicates the current environment to the body tag when the dashboard is active.
     *
     * @global string $classes The global $classes value.

     * @return string $classes to be added to the the body tag.
     */
    function add_admin_body_class( $classes ) {
        // Add class with tier name to dashboard body element
        $classes .= ' wpe-ei-' . $this->tier . ' ';
        return $classes ;
    } 

    /**
     * Add class to the <body> tag.
     *
     * Adds a class that indicates the current environment to the body tag when the dashboard is not active.
     *
     * @global array $classes The global $classes value.

     * @return array $classes to be added to the the body tag.
     */
    function add_body_class( $classes ) {
        $classes[] = ' wpe-ei-' . $this->tier . ' ';
        return $classes;
    } 

    /**
     * Add class to the <body> tag.
     *
     * Adds a class that indicates the current environment to the body tag when the dashboard is active.
     *
     * @global object $wp_admin_bar The global $wp_admin_bar.
     */
    function update_site_name( $wp_admin_bar ) {
        if ( ! $wp_admin_bar->get_node( 'site-name' ) ) {
            return;
        }

        $tier = strtoupper($this->tier);
        $site_name_node = $wp_admin_bar->get_node( 'site-name' );
        $modified_site_name = $tier . ' | ' . $site_name_node->title;
        $modified_site_name_node = array( 'id' => 'site-name', 'title' => $modified_site_name );
        $wp_admin_bar->add_menu( $modified_site_name_node );
    } 
}