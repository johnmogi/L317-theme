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
global $post;
$course_id = $post->ID;
$user_id = get_current_user_id();

// Get all lessons for this course
$lessons = learndash_get_course_lessons_list($course_id, $user_id);
$quizzes = learndash_get_course_quiz_list($course_id, $user_id);

?>

<div class="ld-item-list-flat">
    <?php if (!empty($lessons)): ?>
        <div class="ld-section-header">
            <h3>שיעורים</h3>
        </div>
        
        <ul class="ld-flat-list">
            <?php foreach ($lessons as $lesson): ?>
                <?php
                $lesson_id = $lesson['post']->ID;
                $lesson_title = $lesson['post']->post_title;
                $lesson_url = get_permalink($lesson_id);
                $lesson_status = learndash_course_status($lesson_id, $user_id, $course_id);
                $is_completed = $lesson_status === 'completed';
                ?>
                
                <li class="ld-flat-item <?php echo $is_completed ? 'completed' : 'incomplete'; ?>">
                    <a href="<?php echo esc_url($lesson_url); ?>" class="ld-flat-link">
                        <span class="ld-flat-title"><?php echo esc_html($lesson_title); ?></span>
                        <span class="ld-flat-status">
                            <?php if ($is_completed): ?>
                                ✓ הושלם
                            <?php else: ?>
                                ○ לא הושלם
                            <?php endif; ?>
                        </span>
                    </a>
                    
                    <?php
                    // Get topics for this lesson
                    $topics = learndash_get_topic_list($lesson_id, $course_id);
                    if (!empty($topics)):
                    ?>
                        <ul class="ld-flat-topics">
                            <?php foreach ($topics as $topic): ?>
                                <?php
                                $topic_id = $topic->ID;
                                $topic_title = $topic->post_title;
                                $topic_url = get_permalink($topic_id);
                                $topic_status = learndash_course_status($topic_id, $user_id, $course_id);
                                $topic_completed = $topic_status === 'completed';
                                ?>
                                
                                <li class="ld-flat-topic <?php echo $topic_completed ? 'completed' : 'incomplete'; ?>">
                                    <a href="<?php echo esc_url($topic_url); ?>" class="ld-flat-topic-link">
                                        <span class="ld-flat-topic-title"><?php echo esc_html($topic_title); ?></span>
                                        <span class="ld-flat-topic-status">
                                            <?php if ($topic_completed): ?>
                                                ✓
                                            <?php else: ?>
                                                ○
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <?php if (!empty($quizzes)): ?>
        <div class="ld-section-header">
            <h3>מבחנים</h3>
        </div>
        
        <ul class="ld-flat-list">
            <?php foreach ($quizzes as $quiz): ?>
                <?php
                $quiz_id = $quiz['post']->ID;
                $quiz_title = $quiz['post']->post_title;
                $quiz_url = get_permalink($quiz_id);
                $quiz_status = learndash_course_status($quiz_id, $user_id, $course_id);
                $is_completed = $quiz_status === 'completed';
                ?>
                
                <li class="ld-flat-item <?php echo $is_completed ? 'completed' : 'incomplete'; ?>">
                    <a href="<?php echo esc_url($quiz_url); ?>" class="ld-flat-link">
                        <span class="ld-flat-title"><?php echo esc_html($quiz_title); ?></span>
                        <span class="ld-flat-status">
                            <?php if ($is_completed): ?>
                                ✓ הושלם
                            <?php else: ?>
                                ○ לא הושלם
                            <?php endif; ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<style>
/* Inline CSS for flat list structure */
.ld-item-list-flat {
    direction: rtl;
    text-align: right;
    font-family: 'Assistant', sans-serif;
}

.ld-section-header {
    background: #2C3391;
    color: white;
    padding: 15px 20px;
    margin: 20px 0 0 0;
    font-weight: 600;
}

.ld-section-header h3 {
    margin: 0;
    color: white;
    font-size: 1.2rem;
}

.ld-flat-list {
    list-style: none;
    margin: 0;
    padding: 0;
    background: white;
    border: 1px solid #ddd;
}

.ld-flat-item {
    border-bottom: 1px solid #eee;
    margin: 0;
    padding: 0;
}

.ld-flat-item:last-child {
    border-bottom: none;
}

.ld-flat-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    text-decoration: none;
    color: #333;
    background: white;
    transition: background 0.2s;
}

.ld-flat-link:hover {
    background: #f9f9f9;
    color: #2C3391;
}

.ld-flat-title {
    font-weight: 500;
    flex-grow: 1;
}

.ld-flat-status {
    font-size: 0.9rem;
    color: #666;
}

.ld-flat-item.completed .ld-flat-status {
    color: #22c55e;
}

.ld-flat-topics {
    list-style: none;
    margin: 0;
    padding: 0;
    background: #f8f9fa;
}

.ld-flat-topic {
    border-bottom: 1px solid #e9ecef;
    margin: 0;
    padding: 0;
}

.ld-flat-topic:last-child {
    border-bottom: none;
}

.ld-flat-topic-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 40px;
    text-decoration: none;
    color: #555;
    background: #f8f9fa;
    transition: background 0.2s;
}

.ld-flat-topic-link:hover {
    background: #e9ecef;
    color: #2C3391;
}

.ld-flat-topic-title {
    font-size: 0.95rem;
    flex-grow: 1;
}

.ld-flat-topic-status {
    font-size: 0.8rem;
    color: #666;
}

.ld-flat-topic.completed .ld-flat-topic-status {
    color: #22c55e;
}
</style>
