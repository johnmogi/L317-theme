<?php
/**
 * Single Lesson Template - Two Column Layout with Video Support
 * Template for displaying single LearnDash lessons
 * Includes proper video content rendering
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<div class="learndash-wrapper">
    <?php while ( have_posts() ) : the_post(); ?>
        
        <!-- Lesson Header with Breadcrumb -->
        <header class="ld-lesson-header">
            <?php
            // Display breadcrumb navigation
            if ( function_exists( 'learndash_get_course_id' ) ) {
                $course_id = learndash_get_course_id( get_the_ID() );
                if ( $course_id ) {
                    $course_title = get_the_title( $course_id );
                    $course_url = get_permalink( $course_id );
                    echo '<nav class="ld-breadcrumbs">';
                    echo '<a href="' . esc_url( $course_url ) . '">' . esc_html( $course_title ) . '</a>';
                    echo ' > <span class="current-lesson">' . get_the_title() . '</span>';
                    echo '</nav>';
                }
            }
            ?>
            
            <h1 class="entry-title"><?php the_title(); ?></h1>
            
            <?php
            // Display lesson progress if available
            if ( function_exists( 'learndash_lesson_progress' ) ) {
                $lesson_progress = learndash_lesson_progress( get_the_ID(), get_current_user_id() );
                if ( ! empty( $lesson_progress ) ) {
                    $progress_percentage = isset( $lesson_progress['percentage'] ) ? $lesson_progress['percentage'] : 0;
                    echo '<div class="ld-progress">';
                    echo '<div class="ld-progress-bar" style="width: ' . $progress_percentage . '%"></div>';
                    echo '</div>';
                    echo '<div class="ld-progress-stats">';
                    echo '<span>התקדמות: ' . $progress_percentage . '%</span>';
                    echo '</div>';
                }
            }
            ?>
        </header>

        <!-- Main Lesson Content -->
        <div class="ld-lesson-content">
            
            <!-- LearnDash Content with Video Support -->
            <div class="learndash_content">
                <?php
                // Use the processed $content variable like the legacy theme does
                // This $content variable is already processed by LearnDash's video system
                echo $content;
                ?>
            </div>

            <?php
            // Display lesson topics if available
            if ( function_exists( 'learndash_get_topic_list' ) ) {
                $course_id = learndash_get_course_id( get_the_ID() );
                $topics = learndash_get_topic_list( get_the_ID(), $course_id );
                
                if ( ! empty( $topics ) ) {
                    echo '<div class="ld-item-list">';
                    echo '<h3 class="lesson-topics-title">נושאים בשיעור</h3>';
                    
                    foreach ( $topics as $topic ) {
                        $topic_progress = learndash_topic_progress( $topic->ID, get_current_user_id() );
                        $is_completed = ! empty( $topic_progress ) && $topic_progress['completed'];
                        
                        echo '<div class="ld-item-list-item">';
                        echo '<a href="' . get_permalink( $topic->ID ) . '" class="ld-item-name">';
                        echo esc_html( $topic->post_title );
                        echo '</a>';
                        echo '<span class="ld-status ' . ( $is_completed ? 'ld-status-complete' : 'ld-status-incomplete' ) . '">';
                        echo $is_completed ? 'הושלם' : 'לא הושלם';
                        echo '</span>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
            }
            ?>

            <?php
            // Lesson navigation buttons
            if ( function_exists( 'learndash_get_course_id' ) ) {
                $course_id = learndash_get_course_id( get_the_ID() );
                if ( $course_id ) {
                    echo '<div class="ld-lesson-navigation">';
                    
                    // Previous lesson link
                    $prev_lesson = learndash_previous_post_link( get_the_ID(), true, '', 'sfwd-lessons' );
                    if ( ! empty( $prev_lesson ) ) {
                        echo '<a href="' . get_permalink( $prev_lesson ) . '" class="ld-button ld-button-secondary">שיעור קודם</a>';
                    }
                    
                    // Back to course link
                    echo '<a href="' . get_permalink( $course_id ) . '" class="ld-button ld-button-outline">חזרה לקורס</a>';
                    
                    // Next lesson link
                    $next_lesson = learndash_next_post_link( get_the_ID(), true, '', 'sfwd-lessons' );
                    if ( ! empty( $next_lesson ) ) {
                        echo '<a href="' . get_permalink( $next_lesson ) . '" class="ld-button ld-button-primary">שיעור הבא</a>';
                    }
                    
                    echo '</div>';
                }
            }
            ?>
        </div>

        <!-- Sidebar content will be added via CSS ::after pseudo-element -->

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
