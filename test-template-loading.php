<?php
/**
 * Test script to debug LearnDash template loading
 */

define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');

// Check if LearnDash is active
if (!defined('LEARNDASH_VERSION')) {
    die('LearnDash is not active');
}

// Get the course ID (you may need to change this)
$course_id = get_option('learndash_first_course_id', 0);
if (empty($course_id)) {
    $course = get_posts([
        'post_type' => 'sfwd-courses',
        'numberposts' => 1,
        'fields' => 'ids'
    ]);
    $course_id = !empty($course) ? $course[0] : 0;
}

if (empty($course_id)) {
    die('No courses found');
}

// Get the template paths
$template_paths = [];
if (function_exists('SFWD_LMS::get_template_paths')) {
    $template_paths = SFWD_LMS::get_template_paths('course/listing.php');
}

// Get the template using LearnDash function
$template = '';
if (function_exists('SFWD_LMS::get_template')) {
    $template = SFWD_LMS::get_template('course/listing.php', null, null, true);
}

// Output debug information
header('Content-Type: text/plain');
echo "=== LearnDash Template Debug ===\n\n";
echo "Course ID: $course_id\n";

echo "\n=== Template Paths ===\n";
if (!empty($template_paths)) {
    foreach ($template_paths as $path) {
        echo "- " . (file_exists($path) ? '✓ ' : '✗ ') . $path . "\n";
    }
} else {
    echo "No template paths found\n";
}

echo "\n=== Selected Template ===\n";
echo $template ?: 'No template found';

echo "\n\n=== Theme Directory ===\n";
echo get_stylesheet_directory() . "\n";

echo "\n=== Custom Template Path ===\n";
$custom_template = get_stylesheet_directory() . '/learndash/ld30/templates/course/listing.php';
echo (file_exists($custom_template) ? '✓ ' : '✗ ') . $custom_template . "\n";

// Try to load the template directly
echo "\n=== Direct Template Output ===\n";
if (file_exists($custom_template)) {
    ob_start();
    include($custom_template);
    $output = ob_get_clean();
    echo "Template loaded successfully (first 500 chars):\n";
    echo substr($output, 0, 500) . "...\n";
} else {
    echo "Custom template not found\n";
}
