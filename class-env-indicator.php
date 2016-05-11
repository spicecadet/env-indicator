<?php

namespace wpengine;

class EnvIndicator
{
    private static $instance = null;
    private $tier = null;
    private $version = '0.1';

    static function get_instance()
    {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
  
    private function set_tier( $tier )
    {
        $this->tier = $tier;
    }

    public function current_tier()
    {
        if (function_exists( is_wpe ) && is_wpe() ){
            $tier = 'production';
        } elseif ( function_exists( is_wpe_snapshot ) && is_wpe_snapshot() ){
            $tier = 'staging';
        }  else {
            $tier = 'development';
        }
        $this->set_tier( $tier );

        //$this->set_tier('staging'); //override tier for testing
        //$this->set_tier('production'); //override tier for testing
    }

    function add_stylesheet()
    {
        wp_enqueue_style( 'env-indicator', plugins_url('css/env-indicator.css', __FILE__), false, $this->version );
    }

    function add_admin_body_class( $classes ) 
    {
        // Add class with tier name to dashboard body element
        $classes .= ' wpe-ei-' . $this->tier . ' ';
        return $classes ;
    } 

    function add_body_class( $classes ) 
    {
        // Add class with tier name to body element for all other pages
        $classes[] = ' wpe-ei-' . $this->tier . ' ';
        return $classes ;
    } 

    function update_site_name( $wp_admin_bar )
    {
        if ( ! $wp_admin_bar->get_node( 'site-name' ) ) {
            return;
        }

        $tier = strtoupper($this->tier);
        $site_name_node = $wp_admin_bar->get_node( 'site-name' );
        $modified_site_name = $tier . ' | ' . $site_name_node->title;
        $modified_site_name_node = array( 'id' => 'site-name', 'title' => $modified_site_name );
        $wp_admin_bar->add_menu( $modified_site_name_node );
    
    }

    private function __construct()
    {
        // Set the current tier
        $this->current_tier();
    }
}