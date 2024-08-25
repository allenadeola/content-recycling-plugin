<?php
/*
Plugin Name: Content Recycling Plugin
Plugin URI: https://yourwebsite.com/plugins/content-recycling
Description: A plugin to help bloggers and content creators recycle old content with update suggestions, spin-off ideas, and social media re-sharing.
Version: 1.0
Author: Allen Oluwatobi Adeola
Author URI: https://yourwebsite.com
License: GPL2
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Initialize the plugin
function crp_initialize_plugin() {
    // Initialization code will go here
}
add_action('init', 'crp_initialize_plugin');

// Create admin menu
function crp_add_admin_menu() {
    add_menu_page(
        'Content Recycling',        // Page title
        'Content Recycling',        // Menu title
        'manage_options',           // Capability required to access
        'content-recycling',        // Menu slug
        'crp_settings_page',        // Function to display the page
        'dashicons-recycle',        // Menu icon (dashicons-recycle is a recycle icon)
        20                          // Position in menu
    );
}
add_action('admin_menu', 'crp_add_admin_menu');

// Admin settings page
function crp_settings_page() {
    ?>
    <div class="wrap">
        <h1>Content Recycling Settings</h1>
        <form method="post" action="options.php">
            <?php
            // Output security fields for the registered setting "crp_settings_group"
            settings_fields('crp_settings_group');
            // Output setting sections and their fields
            do_settings_sections('content-recycling');
            // Output save settings button
            submit_button();
            ?>
        </form>
		<h2>Old Content</h2>
        <?php crp_detect_old_content(); ?> <!-- This line calls the function -->
    </div>
    <?php
}

// Register settings and fields
function crp_register_settings() {
    register_setting('crp_settings_group', 'crp_content_age');
    
    add_settings_section(
        'crp_main_section',                // Section ID
        'Main Settings',                   // Title
        'crp_main_section_callback',       // Callback function to display content
        'content-recycling'                // Page slug
    );
    
    add_settings_field(
        'crp_content_age',                 // Field ID
        'Content Age (days)',              // Field title
        'crp_content_age_callback',        // Callback function to display the field
        'content-recycling',               // Page slug
        'crp_main_section'                 // Section ID
    );
}
add_action('admin_init', 'crp_register_settings');

function crp_main_section_callback() {
    echo '<p>Configure the main settings for the Content Recycling Plugin.</p>';
}

function crp_content_age_callback() {
    $content_age = get_option('crp_content_age', 30); // Default value is 30 days
    echo "<input type='number' name='crp_content_age' value='" . esc_attr($content_age) . "' />";
}

function crp_detect_old_content() {
    $content_age = get_option('crp_content_age', 30); // Get the content age from settings
    
    $args = array(
        'date_query' => array(
            array(
                'column' => 'post_date',
                'before' => "$content_age days ago"
            )
        ),
        'posts_per_page' => -1
    );
    
    $old_posts = get_posts($args);
    
    // Process each post found
    foreach($old_posts as $post) {
        // Example: Output the post title (you'll replace this with actual functionality)
        echo '<p>' . $post->post_title . ' was published on ' . $post->post_date . '</p>';
    }
}
