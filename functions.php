<?php

// Register nav menu
register_nav_menu('primary', 'Primary Menu');

// include jQuery
if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   // As of Nov. 2012, latest jQuery is 1.8.2
   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
}

// Add TinyMCE editor style
add_editor_style('css/editor-style.css');

// Add admin scripts
add_action('admin_head', 'abide_admin_css');
function abide_admin_css() {
    $template_url = get_bloginfo('template_url');
    echo '<link rel="stylesheet" href="'.$template_url.'/css/admin-style.css" />';
}

// Remove some stuff from head
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_generator');

// Remove a few admin pages
add_action( 'admin_menu', 'my_remove_menus', 999 );
function my_remove_menus() {
    remove_menu_page('link-manager.php');
    remove_menu_page('edit-comments.php');
    remove_menu_page('upload.php');
    remove_menu_page('profile.php');
    remove_menu_page('tools.php');
    remove_menu_page('edit.php?post_type=page');
    remove_menu_page('edit-tags.php');
}

// Dashboard widget for receiving emails regarding new ideas
function custom_dashboard_widget() {
    if (isset($_POST['idea_email_val'])) {
        $val = $_POST['idea_email_val'];
        if ($val == 'on') {
            update_option('idea_email', true);
        } elseif ($val == 'off') {
            update_option('idea_email', false);
        }
    }
    if (get_option('idea_email') == true) {
        echo '<p>You <strong>are</strong> currently receiving email notifications when a new idea is submitted.</p><p>Want to turn notifications off?</p>';
        echo '<form action="" method="POST">'.
                '<input type="hidden" name="idea_email_val" id="idea_email_val" value="off">'.
                '<input class="button" type="submit" name="idea_email_submit" id="idea_email_submit" value="Turn notifications off">'.
             '</form>';
    } else {
        echo '<p>You <strong>are not</strong> currently receiving email notifications when a new idea is submitted.</p><p>Want to turn notifications on?</p>';
        echo '<form action="" method="POST">'.
                '<input type="hidden" name="idea_email_val" id="idea_email_val" value="on">'.
                '<input class="button" type="submit" name="idea_email_submit" id="idea_email_submit" value="Turn notifications on">'.
             '</form>';
    }
}

function add_custom_dashboard_widget() {
    wp_add_dashboard_widget('custom_dashboard_widget', 'Idea Submission Email Notifications', 'custom_dashboard_widget');
}
add_action('wp_dashboard_setup', 'add_custom_dashboard_widget');

// Only show paragraph and h3 tags in tinyMCE
function only_stuff($in) {
    $in['theme_advanced_blockformats']='p,h3';
    return $in;
}
add_filter('tiny_mce_before_init', 'only_stuff' );
/** 
 * Add "Styles" drop-down 
 */    
function custom_mcekit_editor_buttons($buttons) {  
    array_unshift($buttons, 'styleselect');  
    return $buttons;  
}  
add_filter('mce_buttons_2', 'custom_mcekit_editor_buttons');  
/** 
 * Add "Styles" drop-down content or classes 
 */    
function custom_mcekit_editor_settings($settings) {  
    if (!empty($settings['theme_advanced_styles']))  
        $settings['theme_advanced_styles'] .= ';';      
    else  
        $settings['theme_advanced_styles'] = '';  
  
    /** 
     * Add styles in $classes array. 
     * The format for this setting is "Name to display=class-name;". 
     * More info: http://wiki.moxiecode.com/index.php/TinyMCE:Configuration/theme_advanced_styles 
     * 
     * To be allow translation of the class names, these can be set in a PHP array (to keep them 
     * readable) and then converted to TinyMCE's format. You will need to replace 'textdomain' with 
     * your theme's textdomain. 
     */  
    $classes = array(  
        __('Small','textdomain') => 'small',  
    );  
  
    $class_settings = '';  
    foreach ( $classes as $name => $value )  
        $class_settings .= "{$name}={$value};";  
  
    $settings['theme_advanced_styles'] .= trim($class_settings, '; ');  
    return $settings;  
}   
  
add_filter('tiny_mce_before_init', 'custom_mcekit_editor_settings'); 

// Admin footer
add_filter('admin_footer_text', 'left_admin_footer_text_output'); //left side
function left_admin_footer_text_output($text) {
    $text = 'Site developed by <a href="http://parsleyandsprouts.com" target="_blank">Parsley &amp Sprouts</a>.';
    return $text;
}
add_filter('update_footer', 'right_admin_footer_text_output', 11); //right side
function right_admin_footer_text_output($text) {
    $text = '&copy '.date('Y').'.';
    return $text;
}

?>