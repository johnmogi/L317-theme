<?php
/**
 * Single Course Template - Two Column Layout with Video Support
 * Template for displaying single LearnDash courses
 * Includes proper video content rendering
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<div class="learndash-wrapper">
    <?php while ( have_posts() ) : the_post(); ?>
        
        <!-- Course Header -->
        <header class="ld-course-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            
            <?php if ( has_excerpt() ) : ?>
                <div class="course-description">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Display course progress if user is enrolled
            if ( function_exists( 'learndash_course_progress' ) ) {
                $progress = learndash_course_progress( null, get_current_user_id(), 'percentage' );
                if ( $progress > 0 ) {
                    echo '<div class="ld-progress">';
                    echo '<div class="ld-progress-bar" style="width: ' . $progress . '%"></div>';
                    echo '</div>';
                    echo '<div class="ld-progress-stats">';
                    echo '<span>התקדמות: ' . $progress . '%</span>';
                    echo '</div>';
                }
            }
            ?>
        </header>

        <!-- Main Course Content -->
        <div class="ld-course-content">
            
            <!-- Course Content including Videos -->
            <div class="course-content">
                <div class="learndash_content">
                    <?php 
                    // This will display the course content including embedded videos
                    the_content(); 
                    ?>
                </div>
            </div>

            <?php
            // Display course curriculum
            if ( function_exists( 'learndash_get_course_steps' ) ) {
                $course_id = get_the_ID();
                $lessons = learndash_get_course_steps( $course_id, array( 'sfwd-lessons' ) );
                
                if ( ! empty( $lessons ) ) {
                    echo '<div class="ld-item-list">';
                    echo '<h3 class="course-curriculum-title">תוכן הקורס</h3>';
                    
                    // Group lessons by sections if available
                    $current_section = '';
                    $section_count = 0;
                    
                    foreach ( $lessons as $lesson_id ) {
                        $lesson = get_post( $lesson_id );
                        $lesson_progress = learndash_lesson_progress( $lesson_id, get_current_user_id() );
                        $is_completed = $lesson_progress['completed'];
                        
                        // Start new section every 5 lessons (mock sections)
                        if ( $section_count % 5 === 0 ) {
                            if ( $section_count > 0 ) {
                                echo '</div></div>'; // Close previous section
                            }
                            $section_num = floor( $section_count / 5 ) + 1;
                            echo '<div class="ld-item-list-section">';
                            echo '<div class="ld-item-list-section-heading">';
                            echo '<h3 class="ld-section-heading">יחידה ' . $section_num . '</h3>';
                            echo '</div>';
                            echo '<div class="ld-item-list-items">';
                        }
                        
                        echo '<div class="ld-item-list-item">';
                        echo '<a href="' . get_permalink( $lesson_id ) . '" class="ld-item-name">';
                        echo esc_html( $lesson->post_title );
                        echo '</a>';
                        echo '<span class="ld-status ' . ( $is_completed ? 'ld-status-complete' : 'ld-status-incomplete' ) . '">';
                        echo $is_completed ? 'הושלם' : 'לא הושלם';
                        echo '</span>';
                        echo '</div>';
                        
                        $section_count++;
                    }
                    
                    if ( $section_count > 0 ) {
                        echo '</div></div>'; // Close last section
                    }
                    
                    echo '</div>'; // Close ld-item-list
                }
            }
            ?>

            <?php
            // Course navigation buttons
            if ( function_exists( 'learndash_get_course_steps' ) ) {
                $course_steps = learndash_get_course_steps( get_the_ID() );
                if ( ! empty( $course_steps ) ) {
                    $first_step = reset( $course_steps );
                    echo '<div class="ld-course-navigation">';
                    echo '<a href="' . get_permalink( $first_step ) . '" class="ld-button ld-button-primary">התחל קורס</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>

        <!-- Sidebar content will be added via CSS ::after pseudo-element -->

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
