<?php
/**
 * Dynamic Course Redirect After Purchase
 * 
 * Redirects users to the actual purchased course after order completion
 * Falls back to course ID 898 if no specific course is found
 */

// Redirect to specific course after purchase
add_action('template_redirect', 'redirect_to_specific_course_after_purchase', 20);
function redirect_to_specific_course_after_purchase() {
    // Ensure WooCommerce is loaded
    if (!function_exists('is_wc_endpoint_url') || !function_exists('wc_get_order')) {
        return;
    }
    
    // Only on thank you page
    if (!is_wc_endpoint_url('order-received')) {
        return;
    }
    
    // Get the order ID from the URL
    $order_id = absint(get_query_var('order-received'));
    if (!$order_id) {
        return;
    }
    
    // Get the order object
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }
    
    // Only redirect if order is paid
    if (!$order->is_paid()) {
        return;
    }
    
    // Find the purchased course from order items
    $purchased_course_id = null;
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        
        // Check if this product is associated with a LearnDash course
        $associated_courses = get_post_meta($product_id, '_related_course', true);
        if (!empty($associated_courses)) {
            if (is_array($associated_courses)) {
                $purchased_course_id = $associated_courses[0]; // Take first course
            } else {
                $purchased_course_id = $associated_courses;
            }
            break;
        }
        
        // Alternative: Check if product itself is a course
        if (get_post_type($product_id) === 'sfwd-courses') {
            $purchased_course_id = $product_id;
            break;
        }
    }
    
    // Fallback to course 898 if no specific course found
    if (!$purchased_course_id) {
        $purchased_course_id = 898;
    }
    
    // Get the URL for the purchased course
    $course_url = get_permalink($purchased_course_id);
    
    // Add a small delay to ensure everything is processed
    add_action('wp_footer', function() use ($course_url) {
        ?>
        <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '<?php echo esc_js($course_url); ?>';
        }, 1000); // 1 second delay
        </script>
        <?php
    });
    
    // Show a loading message
    add_filter('the_content', function($content) {
        if (is_wc_endpoint_url('order-received')) {
            return '<div style="text-align: center; padding: 40px 20px; font-family: Arial, sans-serif;">
                <h2>תודה על רכישתך!</h2>
                <p>מעביר אותך לקורס שלך...</p>
                <div style="margin: 20px 0;">
                    <div style="width: 50px; height: 50px; margin: 0 auto; border: 5px solid #f3f3f3; border-top: 5px solid #4f46e5; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                </div>
                <p>אם לא הועברת אוטומטית, <a href="' . esc_url($course_url) . '">לחץ כאן</a> כדי לגשת לקורס שלך.</p>
                <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                </style>
            </div>';
        }
        return $content;
    });
}
