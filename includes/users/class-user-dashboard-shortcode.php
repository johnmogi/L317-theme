<?php
/**
 * User Dashboard Shortcode
 * 
 * @package Hello_Theme_Child
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class User_Dashboard_Shortcode {
    /**
     * Default shortcode attributes
     *
     * @var array
     */
    private $defaults = array(
        'vehicle_type' => 'default', // Options: default, private, truck, motorcycle
        'show_practice' => 'true',
        'show_real_test' => 'true',
        'show_teacher_quizzes' => 'true',
        'show_study_materials' => 'true',
        'show_topic_tests' => 'true',
        'show_stats' => 'true',
        'practice_url' => '#',
        'real_test_url' => '#',
        'study_materials_url' => '#',
        'topic_tests_url' => '#',
        'account_url' => '#',
        'stats_url' => '#',
        'welcome_text' => 'שלום, %s!', // %s will be replaced with user's name
        'track_name' => 'חינוך תעבורתי',
        'show_logout' => 'true',
        'teacher_quiz_limit' => '5'
    );

    /**
     * Vehicle type labels
     *
     * @var array
     */
    private $vehicle_types = array(
        'default' => 'שינוי נושא לימוד',
        'private' => 'רכב פרטי',
        'truck' => 'משאית',
        'motorcycle' => 'אפנוע או קורקינט'
    );

    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode('user_dashboard', array($this, 'render_dashboard'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        global $post;
        
        // Check if we're on a page with the shortcode or on a single course page
        $should_enqueue = false;
        
        if (is_singular() && $post) {
            $should_enqueue = has_shortcode($post->post_content, 'user_dashboard') || 
                            (function_exists('sfwd_lms_has_access') && 'sfwd-courses' === $post->post_type);
        }
        
        if ($should_enqueue) {
            wp_enqueue_style(
                'user-dashboard-style',
                get_stylesheet_directory_uri() . '/assets/css/user-dashboard.css',
                array(),
                filemtime(get_stylesheet_directory() . '/assets/css/user-dashboard.css')
            );
        }
    }

    /**
     * Get current user's full name
     */
    private function get_user_full_name() {
        $current_user = wp_get_current_user();
        $name = trim($current_user->first_name . ' ' . $current_user->last_name);
        return !empty($name) ? $name : $current_user->display_name;
    }

    /**
     * Get vehicle type text
     *
     * @param string $type Vehicle type key
     * @return string Vehicle type label
     */
    private function get_vehicle_type_text($type) {
        return isset($this->vehicle_types[$type]) ? $this->vehicle_types[$type] : $this->vehicle_types['default'];
    }

    /**
     * Get current date in format dd/mm/yyyy
     */
    private function get_current_date() {
        return date('d/m/Y');
    }

    /**
     * Get the teacher ID assigned to current student
     *
     * @return int|false Teacher ID or false if not found
     */
    private function get_student_teacher_id() {
        if (!is_user_logged_in()) {
            return false;
        }

        $current_user_id = get_current_user_id();
        global $wpdb;
        
        // Try to get teacher from school_teacher_students table
        $teacher_id = $wpdb->get_var($wpdb->prepare(
            "SELECT teacher_id 
             FROM {$wpdb->prefix}school_teacher_students 
             WHERE student_id = %d 
             LIMIT 1",
            $current_user_id
        ));

        // If no direct teacher-student relationship, try to get from class
        if (!$teacher_id) {
            $teacher_id = $wpdb->get_var($wpdb->prepare(
                "SELECT sc.teacher_id 
                 FROM {$wpdb->prefix}school_classes sc
                 JOIN {$wpdb->prefix}school_students ss ON sc.id = ss.class_id
                 WHERE ss.wp_user_id = %d
                 LIMIT 1",
                $current_user_id
            ));
        }

        return $teacher_id ? (int)$teacher_id : false;
    }

    /**
     * Get quizzes created by a specific teacher
     *
     * @param int $teacher_id Teacher user ID
     * @param int $limit Number of quizzes to retrieve
     * @return array Quiz information
     */
    private function get_teacher_quizzes($teacher_id, $limit = 5) {
        $args = array(
            'post_type'      => 'sfwd-quiz',
            'posts_per_page' => intval($limit),
            'author'         => $teacher_id,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        $quizzes = get_posts($args);
        
        // Add quiz metadata
        foreach ($quizzes as &$quiz) {
            $quiz->quiz_url = get_permalink($quiz->ID);
            $quiz->quiz_date = get_the_date('d/m/Y', $quiz->ID);
        }
        
        return $quizzes;
    }

    /**
     * Get teacher's name
     *
     * @param int $teacher_id Teacher user ID
     * @return string Teacher's display name
     */
    private function get_teacher_name($teacher_id) {
        $teacher = get_userdata($teacher_id);
        return $teacher ? $teacher->display_name : '';
    }

    /**
     * Render dashboard HTML
     */
    public function render_dashboard($atts) {
        // Only show to logged in users
        if (!is_user_logged_in()) {
            return '<div class="user-dashboard-login-notice">יש להתחבר למערכת כדי לצפות בלוח הבקרה.</div>';
        }

        // Parse attributes with defaults
        $atts = shortcode_atts($this->defaults, $atts, 'user_dashboard');
        
        // Get vehicle type text
        $vehicle_text = $this->get_vehicle_type_text($atts['vehicle_type']);
        
        // Prepare welcome text
        $welcome_text = sprintf($atts['welcome_text'], $this->get_user_full_name());

        ob_start();
        ?>
        <div class="user-dashboard-container">
            <div class="dashboard-content">
                <!-- Left Column - User Panel -->
                <div class="dashboard-column user-panel">
                    <div class="user-greeting">
                        <h2><?php echo esc_html($welcome_text); ?></h2>
                        <div class="user-meta">
                            <div class="meta-item date">
                                <span class="meta-icon">📅</span>
                                <span class="meta-text"><?php echo esc_html($this->get_current_date()); ?></span>
                            </div>
                            <div class="meta-item track">
                                <span class="meta-icon">🎯</span>
                                <span class="meta-text"><?php echo esc_html($atts['track_name']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="user-actions">
                        <a href="<?php echo esc_url($atts['account_url']); ?>" class="user-action-link edit-account">
                            <span class="link-icon">✏️</span>
                            <span class="link-text">ערוך חשבון (<?php echo esc_html($vehicle_text); ?>)</span>
                        </a>
                        <?php if ($atts['show_stats'] === 'true') : ?>
                            <?php 
                            // Check if current user is a teacher
                            $current_user = wp_get_current_user();
                            $is_teacher = false;
                            $teacher_roles = array('administrator', 'school_teacher', 'wdm_instructor', 'instructor', 'wdm_swd_instructor', 'swd_instructor');
                            
                            foreach ($teacher_roles as $role) {
                                if (in_array($role, $current_user->roles)) {
                                    $is_teacher = true;
                                    break;
                                }
                            }
                            
                            if ($is_teacher) {
                                // Teacher - redirect to teacher dashboard with their ID
                                $stats_url = 'https://test-li.ussl.co.il/teacher_dashboard/?teacher_id=' . $current_user->ID;
                            } else {
                                // Student - scroll to quiz progress details on same page
                                $stats_url = '#quiz_progress_details';
                            }
                            ?>
                        <a href="<?php echo esc_url($stats_url); ?>" class="user-action-link stats" <?php echo !$is_teacher ? 'onclick="document.getElementById(\"quiz_progress_details\").style.display=\"block\"; document.getElementById(\"quiz_progress_details\").scrollIntoView({behavior: \"smooth\"}); return false;"' : ''; ?>>
                            <span class="link-icon">📊</span>
                            <span class="link-text">סטטיסטיקות לימוד</span>
                        </a>
                        <?php endif; ?>
                        <?php if ($atts['show_logout'] === 'true') : ?>
                        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="user-action-link logout">
                            <span class="link-icon">🚪</span>
                            <span class="link-text">התנתק</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Middle Column - Practice Tests -->
                <?php if ($atts['show_practice'] === 'true' || $atts['show_real_test'] === 'true' || $atts['show_teacher_quizzes'] === 'true') : ?>
                <div class="dashboard-column test-column">
                    <div class="column-header">
                        <h3>מבחנים כדוגמת מבחן התיאוריה</h3>
                    </div>
                    <div class="button-group">
                        <?php if ($atts['show_practice'] === 'true') : ?>
                        <a href="<?php echo esc_url(home_url('quizzes/מבחן-תרגול-להמחשה/')); ?>" class="dashboard-button practice-button">
                            <span class="button-text">מבחני תרגול</span>
                            <span class="button-icon">📝</span>
                        </a>
                        <?php endif; ?>
                        <?php if ($atts['show_real_test'] === 'true') : ?>
                        <a href="<?php echo esc_url(home_url('/courses/פרטי/lessons/פרק-01-תורת-החינוך-התעברותי-פרק-מבוא/quizzes/מבחן-אמת-כמו-בתאוריה/')); ?>" class="dashboard-button real-test-button">
                            <span class="button-text">מבחני אמת – כמו בתיאוריה</span>
                            <span class="button-icon">📋</span>
                        </a>
                        <?php endif; ?>
                        <?php if ($atts['show_teacher_quizzes'] === 'true') : ?>
                            <?php 
                            $teacher_id = $this->get_student_teacher_id();
                            if ($teacher_id) {
                                $teacher_quizzes = $this->get_teacher_quizzes($teacher_id, 1); // Get only the latest quiz
                                if (!empty($teacher_quizzes)) {
                                    $latest_quiz = $teacher_quizzes[0];
                                    $quiz_url = $latest_quiz->quiz_url;
                                } else {
                                    // Default URL if no quizzes found - you can change this
                                    $quiz_url = home_url('/quizzes/');
                                }
                            } else {
                                // Default URL if no teacher assigned - you can change this
                                $quiz_url = home_url('/quizzes/');
                            }
                            ?>
                            <a href="<?php echo esc_url($quiz_url); ?>" class="dashboard-button teacher-quiz-button">
                                <span class="button-text">מבחן מורה</span>
                                <span class="button-icon">🎓</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Right Column - Questions by Topic -->
                <?php if ($atts['show_study_materials'] === 'true' || $atts['show_topic_tests'] === 'true') : ?>
                <div class="dashboard-column questions-column">
                    <div class="column-header">
                        <h3>שאלות מהמאגר לפי נושאים</h3>
                    </div>
                    <div class="button-group">
                        <?php if ($atts['show_study_materials'] === 'true') : ?>
                        <a href="<?php echo esc_url($atts['study_materials_url']); ?>" class="dashboard-button study-materials-button">
                            <span class="button-text">חומר לימוד לפי נושאים</span>
                            <span class="button-icon">📚</span>
                        </a>
                        <?php endif; ?>
                        <?php if ($atts['show_topic_tests'] === 'true') : ?>
                        <a href="<?php echo esc_url($atts['topic_tests_url']); ?>" class="dashboard-button topic-tests-button">
                            <span class="button-text">מבחנים לפי נושאים</span>
                            <span class="button-icon">📝</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <div class="dashboard-footer">
                <p>בהצלחה בלימוד ובתרגול!</p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the shortcode
new User_Dashboard_Shortcode();
