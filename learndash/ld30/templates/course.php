<?php
/**
 * LearnDash LD30 Course Template Override
 * Two-Column Layout with Sidebar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $course_id, $post;
$course_id = $post->ID;

$course_price = learndash_get_course_price( $course_id );
$has_access = sfwd_lms_has_access( $course_id, get_current_user_id() );
$course_status = learndash_course_status( $course_id, null );
$course_progress = learndash_course_progress( $course_id );

// Get course lessons
$lessons = learndash_get_course_lessons_list( $course_id );
?>

<div class="ld-course-wrapper ld-course-two-column">
    
    <!-- Full-width course header -->
    <div class="ld-course-header">
        <div class="ld-course-header-content">
            <h1 class="ld-course-title"><?php echo get_the_title(); ?></h1>
            <?php if ( ! empty( $course_progress['percentage'] ) ) : ?>
                <div class="ld-course-progress-wrapper">
                    <div class="ld-course-progress-bar">
                        <div class="ld-course-progress-fill" style="width: <?php echo $course_progress['percentage']; ?>%"></div>
                    </div>
                    <span class="ld-course-progress-text"><?php echo $course_progress['percentage']; ?>% הושלם</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Two-column content area -->
    <div class="ld-course-content-wrapper">
        
        <!-- Main content area (left column) -->
        <div class="ld-course-main-content">
            
            <!-- Course description -->
            <?php if ( ! empty( get_the_content() ) ) : ?>
                <div class="ld-course-description">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>

            <!-- Course lessons list -->
            <div class="ld-course-lessons-wrapper">
                <h3 class="ld-course-lessons-title">תוכן הקורס</h3>
                
                <?php if ( ! empty( $lessons ) ) : ?>
                    <div class="ld-course-lessons-list">
                        <?php foreach ( $lessons as $lesson ) : 
                            $lesson_id = $lesson['post']->ID;
                            $lesson_status = learndash_lesson_status( $lesson_id, get_current_user_id() );
                            $lesson_progress = learndash_lesson_progress( $lesson_id );
                            $topics = learndash_get_topic_list( $lesson_id, $course_id );
                            $quizzes = learndash_get_lesson_quiz_list( $lesson_id, get_current_user_id(), $course_id );
                        ?>
                            <div class="ld-lesson-card <?php echo $lesson_status; ?>">
                                <div class="ld-lesson-card-header">
                                    <div class="ld-lesson-status-icon">
                                        <?php if ( $lesson_status === 'completed' ) : ?>
                                            <span class="ld-icon ld-icon-checkmark"></span>
                                        <?php else : ?>
                                            <span class="ld-icon ld-icon-content"></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ld-lesson-info">
                                        <h4 class="ld-lesson-title">
                                            <a href="<?php echo get_permalink( $lesson_id ); ?>"><?php echo $lesson['post']->post_title; ?></a>
                                        </h4>
                                        <div class="ld-lesson-meta">
                                            <?php if ( ! empty( $topics ) ) : ?>
                                                <span class="ld-lesson-topics"><?php echo count( $topics ); ?> נושאים</span>
                                            <?php endif; ?>
                                            <?php if ( ! empty( $quizzes ) ) : ?>
                                                <span class="ld-lesson-quizzes"><?php echo count( $quizzes ); ?> מבחנים</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ( ! empty( $lesson_progress['percentage'] ) ) : ?>
                                        <div class="ld-lesson-progress">
                                            <span class="ld-lesson-progress-text"><?php echo $lesson_progress['percentage']; ?>%</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ( ! empty( $lesson['post']->post_excerpt ) ) : ?>
                                    <div class="ld-lesson-excerpt">
                                        <?php echo $lesson['post']->post_excerpt; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar (right column) -->
        <div class="ld-course-sidebar">
            
            <!-- Course progress widget -->
            <div class="ld-course-widget ld-course-progress-widget">
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
                    <?php endif; ?>
                </div>
            </div>

            <!-- User profile widget -->
            <div class="ld-course-widget ld-user-profile-widget">
                <h4 class="ld-widget-title">פרופיל משתמש</h4>
                <div class="ld-widget-content">
                    <?php if ( is_user_logged_in() ) : 
                        $current_user = wp_get_current_user();
                    ?>
                        <div class="ld-user-info">
                            <div class="ld-user-avatar">
                                <?php echo get_avatar( $current_user->ID, 60 ); ?>
                            </div>
                            <div class="ld-user-details">
                                <div class="ld-user-name"><?php echo $current_user->display_name; ?></div>
                                <div class="ld-user-email"><?php echo $current_user->user_email; ?></div>
                            </div>
                        </div>
                        
                        <!-- User course stats -->
                        <div class="ld-user-stats">
                            <?php
                            $user_courses = learndash_user_get_enrolled_courses( $current_user->ID );
                            $completed_courses = 0;
                            foreach ( $user_courses as $user_course_id ) {
                                if ( learndash_course_completed( $current_user->ID, $user_course_id ) ) {
                                    $completed_courses++;
                                }
                            }
                            ?>
                            <div class="ld-user-stat">
                                <span class="ld-stat-number"><?php echo count( $user_courses ); ?></span>
                                <span class="ld-stat-label">קורסים רשומים</span>
                            </div>
                            <div class="ld-user-stat">
                                <span class="ld-stat-number"><?php echo $completed_courses; ?></span>
                                <span class="ld-stat-label">קורסים שהושלמו</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Course navigation widget -->
            <div class="ld-course-widget ld-course-navigation-widget">
                <h4 class="ld-widget-title">ניווט מהיר</h4>
                <div class="ld-widget-content">
                    <div class="ld-quick-nav">
                        <?php if ( $has_access ) : ?>
                            <a href="<?php echo learndash_get_step_permalink( learndash_course_get_first_lesson( $course_id ), $course_id ); ?>" class="ld-nav-button ld-nav-start">
                                <span class="ld-icon ld-icon-play"></span>
                                התחל קורס
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $course_progress ) && $course_progress['percentage'] > 0 ) : ?>
                            <a href="#" class="ld-nav-button ld-nav-continue">
                                <span class="ld-icon ld-icon-arrow-left"></span>
                                המשך מהמקום שעצרת
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
