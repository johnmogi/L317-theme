<?php
/**
 * LearnDash LD30 Lesson Template Override
 * Modern Two-Column Layout with Clean Design
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $post, $course_id;
$lesson_id = $post->ID;
$course_id = learndash_get_course_id( $lesson_id );
$lesson_progress = learndash_lesson_progress( $lesson_id );
$course_progress = learndash_course_progress( $course_id );

// Get lesson navigation
$prev_lesson = learndash_previous_post_link();
$next_lesson = learndash_next_post_link();

// Get topics for this lesson
$topics = learndash_get_topic_list( $lesson_id, $course_id );
?>

<div class="ld-lesson-wrapper ld-lesson-modern">
    
    <!-- Breadcrumb Navigation -->
    <div class="ld-lesson-breadcrumb">
        <div class="ld-breadcrumb-container">
            <a href="<?php echo get_permalink( $course_id ); ?>" class="ld-breadcrumb-link">
                <span class="ld-icon ld-icon-arrow-right"></span>
                <?php echo get_the_title( $course_id ); ?>
            </a>
            <span class="ld-breadcrumb-separator">/</span>
            <span class="ld-breadcrumb-current"><?php echo get_the_title(); ?></span>
        </div>
    </div>

    <!-- Lesson Header -->
    <div class="ld-lesson-header">
        <div class="ld-lesson-header-content">
            <div class="ld-lesson-title-section">
                <h1 class="ld-lesson-title"><?php echo get_the_title(); ?></h1>
                <?php if ( ! empty( $lesson_progress['percentage'] ) ) : ?>
                    <div class="ld-lesson-progress-wrapper">
                        <div class="ld-lesson-progress-bar">
                            <div class="ld-lesson-progress-fill" style="width: <?php echo $lesson_progress['percentage']; ?>%"></div>
                        </div>
                        <span class="ld-lesson-progress-text"><?php echo $lesson_progress['percentage']; ?>% הושלם</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Lesson Navigation -->
            <div class="ld-lesson-nav">
                <?php if ( $prev_lesson ) : ?>
                    <a href="<?php echo $prev_lesson['permalink']; ?>" class="ld-nav-button ld-nav-prev">
                        <span class="ld-icon ld-icon-arrow-left"></span>
                        <span class="ld-nav-text">שיעור קודם</span>
                    </a>
                <?php endif; ?>
                
                <?php if ( $next_lesson ) : ?>
                    <a href="<?php echo $next_lesson['permalink']; ?>" class="ld-nav-button ld-nav-next">
                        <span class="ld-nav-text">שיעור הבא</span>
                        <span class="ld-icon ld-icon-arrow-left"></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Two-column content area -->
    <div class="ld-lesson-content-wrapper">
        
        <!-- Main content area (left column) -->
        <div class="ld-lesson-main-content">
            
            <!-- Lesson content -->
            <div class="ld-lesson-content">
                <?php the_content(); ?>
            </div>

            <!-- Topics list if available -->
            <?php if ( ! empty( $topics ) ) : ?>
                <div class="ld-lesson-topics-wrapper">
                    <h3 class="ld-lesson-topics-title">נושאי השיעור</h3>
                    
                    <div class="ld-lesson-topics-list">
                        <?php foreach ( $topics as $topic ) : 
                            $topic_id = $topic['post']->ID;
                            $topic_status = learndash_lesson_status( $topic_id, get_current_user_id() );
                            $topic_progress = learndash_lesson_progress( $topic_id );
                        ?>
                            <div class="ld-topic-card <?php echo $topic_status; ?>">
                                <div class="ld-topic-card-content">
                                    <div class="ld-topic-status-icon">
                                        <?php if ( $topic_status === 'completed' ) : ?>
                                            <span class="ld-icon ld-icon-checkmark"></span>
                                        <?php else : ?>
                                            <span class="ld-icon ld-icon-content"></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ld-topic-info">
                                        <h4 class="ld-topic-title">
                                            <a href="<?php echo get_permalink( $topic_id ); ?>"><?php echo $topic['post']->post_title; ?></a>
                                        </h4>
                                        <?php if ( ! empty( $topic['post']->post_excerpt ) ) : ?>
                                            <p class="ld-topic-excerpt"><?php echo $topic['post']->post_excerpt; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ( ! empty( $topic_progress['percentage'] ) ) : ?>
                                        <div class="ld-topic-progress">
                                            <span class="ld-topic-progress-text"><?php echo $topic_progress['percentage']; ?>%</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Lesson completion button -->
            <div class="ld-lesson-completion">
                <?php echo learndash_mark_complete( $post ); ?>
            </div>
        </div>

        <!-- Sidebar (right column) -->
        <div class="ld-lesson-sidebar">
            
            <!-- Course progress widget -->
            <div class="ld-lesson-widget ld-course-progress-widget">
                <h4 class="ld-widget-title">התקדמות בקורס</h4>
                <div class="ld-widget-content">
                    <?php if ( ! empty( $course_progress ) ) : ?>
                        <div class="ld-progress-stats">
                            <div class="ld-progress-stat">
                                <span class="ld-stat-number"><?php echo $course_progress['completed']; ?></span>
                                <span class="ld-stat-label">הושלמו</span>
                            </div>
                            <div class="ld-progress-stat">
                                <span class="ld-stat-number"><?php echo $course_progress['total']; ?></span>
                                <span class="ld-stat-label">סה"כ</span>
                            </div>
                            <div class="ld-progress-stat">
                                <span class="ld-stat-number"><?php echo $course_progress['percentage']; ?>%</span>
                                <span class="ld-stat-label">הושלם</span>
                            </div>
                        </div>
                        
                        <div class="ld-course-progress-bar-widget">
                            <div class="ld-course-progress-bar">
                                <div class="ld-course-progress-fill" style="width: <?php echo $course_progress['percentage']; ?>%"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lesson navigation widget -->
            <div class="ld-lesson-widget ld-lesson-navigation-widget">
                <h4 class="ld-widget-title">ניווט בשיעור</h4>
                <div class="ld-widget-content">
                    <div class="ld-lesson-nav-links">
                        <a href="<?php echo get_permalink( $course_id ); ?>" class="ld-nav-link ld-nav-course">
                            <span class="ld-icon ld-icon-course"></span>
                            <span class="ld-nav-text">חזרה לקורס</span>
                        </a>
                        
                        <?php if ( $prev_lesson ) : ?>
                            <a href="<?php echo $prev_lesson['permalink']; ?>" class="ld-nav-link ld-nav-prev">
                                <span class="ld-icon ld-icon-arrow-left"></span>
                                <span class="ld-nav-text">שיעור קודם</span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( $next_lesson ) : ?>
                            <a href="<?php echo $next_lesson['permalink']; ?>" class="ld-nav-link ld-nav-next">
                                <span class="ld-nav-text">שיעור הבא</span>
                                <span class="ld-icon ld-icon-arrow-left"></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- User profile widget -->
            <div class="ld-lesson-widget ld-user-profile-widget">
                <h4 class="ld-widget-title">פרופיל משתמש</h4>
                <div class="ld-widget-content">
                    <?php if ( is_user_logged_in() ) : 
                        $current_user = wp_get_current_user();
                    ?>
                        <div class="ld-user-info">
                            <div class="ld-user-avatar">
                                <?php echo get_avatar( $current_user->ID, 50 ); ?>
                            </div>
                            <div class="ld-user-details">
                                <div class="ld-user-name"><?php echo $current_user->display_name; ?></div>
                                <div class="ld-user-email"><?php echo $current_user->user_email; ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
