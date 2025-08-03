<?php
/**
 * LILAC Custom LearnDash Course Listing - Clean Template
 * Simple template that uses the CSS file for styling
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get the course ID
$course_id = get_the_ID();
$course = get_post($course_id);

if (!$course) {
    return;
}

// Add inline CSS for basic structure
add_action('wp_head', function() {
    ?>
    <style>
    /* Basic LearnDash Course Structure */
    .learndash-wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        direction: rtl;
        text-align: right;
    }
    
    .course-header {
        background: #2C3391;
        color: white;
        padding: 30px;
        margin-bottom: 20px;
        border-radius: 4px;
        text-align: right;
        direction: rtl;
    }
    
    .course-header h1 {
        color: white;
        font-size: 2rem;
        margin: 0;
        font-weight: 700;
    }
    </style>
    <?php
});

get_header();
?>

<div class="learndash-wrapper">
    <div class="course-header">
        <h1><?php echo esc_html(get_the_title()); ?></h1>
    </div>
    
    <?php
    // Display course content using LearnDash functions
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            the_content();
        }
    }
    ?>
</div>

<?php
get_footer();
?>
