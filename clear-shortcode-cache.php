<?php
/**
 * Clear Shortcode Cache Script
 * 
 * This script clears all caches that might be causing shortcodes to load from the wrong theme
 */

require_once('../../../wp-config.php');

echo "<h2>Clearing Shortcode and Theme Caches</h2>\n";

// 1. Clear WordPress object cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "<p>✅ WordPress object cache cleared</p>\n";
}

// 2. Clear all transients
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");
echo "<p>✅ All transients cleared</p>\n";

// 3. Clear theme-related options
delete_option('theme_switched');
delete_option('stylesheet');
delete_option('template');
echo "<p>✅ Theme options cleared</p>\n";

// 4. Force theme reload
update_option('stylesheet', 'hello-theme-child-master');
update_option('template', 'hello-elementor');
echo "<p>✅ Theme options reset</p>\n";

// 5. Clear shortcode registry and force re-registration
global $shortcode_tags;
$original_shortcodes = $shortcode_tags;

// Remove problematic shortcodes
remove_shortcode('student_teacher_info');
remove_shortcode('my_teacher');
remove_shortcode('teacher_dashboard');

echo "<p>✅ Shortcodes removed from registry</p>\n";

// 6. Force re-include of the correct theme files
$active_theme_dir = get_stylesheet_directory();
echo "<p>Active theme directory: $active_theme_dir</p>\n";

// Re-register shortcodes from the correct theme
$student_teacher_file = $active_theme_dir . '/mu-plugins/student-teacher-display.php';
if (file_exists($student_teacher_file)) {
    // Force reload the file
    include_once $student_teacher_file;
    Student_Teacher_Display::init();
    echo "<p>✅ Student teacher shortcodes re-registered from active theme</p>\n";
} else {
    echo "<p>❌ Student teacher file not found: $student_teacher_file</p>\n";
}

// 7. Check current shortcode registrations
echo "<h3>Current Shortcode Registrations:</h3>\n";
echo "<ul>\n";
foreach ($shortcode_tags as $tag => $callback) {
    if (in_array($tag, ['student_teacher_info', 'my_teacher', 'teacher_dashboard'])) {
        echo "<li><strong>$tag</strong>: " . (is_array($callback) ? get_class($callback[0]) . '::' . $callback[1] : $callback) . "</li>\n";
    }
}
echo "</ul>\n";

// 8. Test shortcode execution
echo "<h3>Testing Shortcode Execution:</h3>\n";
$test_output = do_shortcode('[student_teacher_info]');
echo "<p>Test shortcode output length: " . strlen($test_output) . " characters</p>\n";

// 9. Clear any PHP opcode cache if available
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "<p>✅ PHP OPcache cleared</p>\n";
}

echo "<h3>Cache Clearing Complete!</h3>\n";
echo "<p>Please refresh your pages to see if shortcodes are now loading from the correct theme.</p>\n";
?>
