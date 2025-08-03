<?php
/**
 * LILAC Custom LearnDash Course Listing - FLAT LIST STRUCTURE
 * Outputs a simple, flat list without any nesting or accordion behavior
 */

if (!defined('ABSPATH')) {
    exit;
}

// Debug marker
echo '<!-- LILAC FLAT LIST TEMPLATE LOADED -->';

// Get course data
global $course_pager_results, $post;
$course_id = $post->ID;
$user_id = get_current_user_id();

// Get all lessons for this course
$lessons = learndash_get_course_lessons_list($course_id, $user_id);
$quizzes = learndash_get_course_quiz_list($course_id, $user_id);

/**
 * Available Variables:
 * $course_id                  : (int) ID of the course
 * $course                     : (object) Post object of the course
 * $course_settings            : (array) Settings specific to current course
 * $user_id                    : Current User ID
 * $logged_in                  : User is logged in
 * $current_user               : (object) Currently logged in user object
 * $course_status              : Course Status
 * $has_access                 : User has access to course or is enrolled.
 * $lessons                    : Lessons Array
 * $quizzes                    : Quizzes Array
 * $lesson_progression_enabled : (true/false)
 * $has_topics                 : (true/false)
 * $lesson_topics              : (array) lessons topics
 *
 * @since 3.0.0
 * @package LearnDash\Templates\LD30
 * @customized LILAC - Modern Card Layout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add modern card layout styles
add_action('wp_head', function() {
    ?>
    <style>
    /* Modern Card Layout for LearnDash Course */
    .lilac-course-container {
        font-family: 'Assistant', sans-serif;
        direction: rtl;
        text-align: right;
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .lilac-lessons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .lilac-lesson-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .lilac-lesson-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        border-color: #4f46e5;
    }
    
    .lilac-lesson-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 2rem;
        position: relative;
    }
    
    .lilac-lesson-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        line-height: 1.3;
    }
    
    .lilac-lesson-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .lilac-lesson-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
    }
    
    .lilac-lesson-content {
        padding: 2rem;
    }
    
    .lilac-topics-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .lilac-topic-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .lilac-topic-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    .lilac-topic-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        flex-shrink: 0;
    }
    
    .lilac-topic-icon.completed {
        background: #10b981;
        color: white;
    }
    
    .lilac-topic-icon.in-progress {
        background: #f59e0b;
        color: white;
    }
    
    .lilac-topic-icon.not-started {
        background: #6b7280;
        color: white;
    }
    
    .lilac-topic-title {
        flex: 1;
        font-weight: 500;
        color: #374151;
    }
    
    .lilac-section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin: 2rem 0 1rem 0;
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .lilac-lessons-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .lilac-course-container {
            padding: 1rem;
        }
        
        .lilac-lesson-header,
        .lilac-lesson-content {
            padding: 1.5rem;
        }
    }
    </style>
    <?php
});
        font-weight: 700;
        color: #2f2f92;
        margin-bottom: 24px;
        text-align: right;
        direction: rtl;
    }

    /* ✅ כפתור Expand All */
    .ld-expand-button, .expand-button {
        background-color: #2f2f92;
        color: white;
        padding: 8px 20px;
        border-radius: 999px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        direction: rtl;
    }

    /* ✅ קופסה של כל פרק */
    .ld-item-list-section, .ld-lesson-item {
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 16px;
        background-color: #fff;
        direction: rtl;
        text-align: right;
    }

    /* ✅ מספר נושאים */
    .topic-count, .ld-lesson-topic-list .ld-table-list-header {
        font-size: 14px;
        color: #6b7c93;
        margin-top: 8px;
        direction: rtl;
        text-align: right;
    }

    /* ✅ אייקון וי של מצב סיום */
    .status-icon, .ld-status-complete {
        color: #00b383;
        font-size: 16px;
        margin-left: 6px;
        vertical-align: middle;
    }

    /* ✅ חץ לפתיחה/סגירה */
    .toggle-icon, .ld-lesson-item-expand {
        font-size: 12px;
        color: #2f2f92;
        margin-top: 12px;
        display: inline-block;
        transform: rotate(0deg);
        transition: transform 0.3s ease;
    }
    .toggle-icon.open, .ld-lesson-item-expand.ld-expanded {
        transform: rotate(180deg);
    }

    /* RTL specific adjustments */
    .ld-item-list-items {
        direction: rtl;
    }
    
    .ld-lesson-item-preview {
        text-align: right;
        direction: rtl;
    }
    
    .ld-lesson-title {
        direction: rtl;
        text-align: right;
        font-family: 'Assistant', sans-serif;
    }
    </style>
    <?php
});

/**
 * Display lessons if they exist
 *
 * @var $lessons [array]
 * @since 3.0.0
 */

if ( ! empty( $lessons ) || ! empty( $quizzes ) ) :

	/**
	 * Filters LearnDash Course table CSS class.
	 *
	 * @since 3.0.0
	 *
	 * @param string $course_table_class CSS classes for course table.
	 */
	$table_class = apply_filters( 'learndash_course_table_class', 'ld-item-list-items ' . ( isset( $lesson_progression_enabled ) && $lesson_progression_enabled ? 'ld-lesson-progression' : '' ) );

	$table_class .= ' ld-item-list-' . absint( $course_id );

	/**
	 * Display the expand button if lesson has topics
	 *
	 * @var $lessons [array]
	 * @since 3.0.0
	 */
	?>

	<div class="<?php echo esc_attr( $table_class ); ?>" id="<?php echo esc_attr( 'ld-item-list-' . $course_id ); ?>" data-ld-expand-id="<?php echo esc_attr( 'ld-item-list-' . $course_id ); ?>" data-ld-expand-list="true" style="direction: rtl;">
		<?php
		/**
		 * Fires before the course listing.
		 *
		 * @since 3.0.0
		 *
		 * @param int $course_id Course ID.
		 * @param int $user_id   User ID.
		 */
		do_action( 'learndash-course-listing-before', $course_id, $user_id );

		if ( $lessons && ! empty( $lessons ) ) :

			/**
			 * Loop through each lesson and output a row
			 *
			 * @var $lessons [array]
			 * @since 3.0.0
			 */

			$sections = learndash_30_get_course_sections( $course_id );
			$i        = 0;

			foreach ( $lessons as $lesson ) :
				learndash_get_template_part(
					'lesson/partials/row.php',
					array(
						'count'                => $i,
						'sections'             => $sections,
						'lesson'               => $lesson,
						'course_id'            => $course_id,
						'user_id'              => $user_id,
						'lesson_topics'        => ! empty( $lesson_topics ) ? $lesson_topics : [],
						'has_access'           => $has_access,
						'course_pager_results' => $course_pager_results,
					),
					true
				);
				$i++;
			endforeach;

		endif;

		/**
		 * Display quizzes if they exist
		 *
		 * @var $quizzes [array]
		 * @since 3.0.0
		 */

		if ( $quizzes && ! empty( $quizzes ) ) :

			/**
			 * Loop through each quiz and output a row
			 *
			 * @var $quizzes [array]
			 * @since 3.0.0
			 */

			foreach ( $quizzes as $quiz ) :
				learndash_get_template_part(
					'quiz/partials/row.php',
					array(
						'quiz'       => $quiz,
						'course_id'  => $course_id,
						'user_id'    => $user_id,
						'has_access' => $has_access,
					),
					true
				);
			endforeach;

		endif;

		/**
		 * Fires after the course listing.
		 *
		 * @since 3.0.0
		 *
		 * @param int $course_id Course ID.
		 * @param int $user_id   User ID.
		 */
		do_action( 'learndash-course-listing-after', $course_id, $user_id );
		?>

	</div> <!--/.ld-item-list-items-->

	<?php

endif;
