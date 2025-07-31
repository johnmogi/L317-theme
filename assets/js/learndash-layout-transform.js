/**
 * LearnDash Layout Transformation Script
 * Forces the nested accordion structure into a modern card-based layout
 */

(function($) {
    'use strict';
    
    function transformLearnDashLayout() {
        console.log('Starting LearnDash layout transformation...');
        
        // Wait for DOM to be ready
        $(document).ready(function() {
            // Add a small delay to ensure LearnDash has finished loading
            setTimeout(function() {
                
                // Find the lessons container
                const lessonsContainer = $('.ld-accordion__items--lessons');
                
                if (lessonsContainer.length > 0) {
                    console.log('Found lessons container, applying transformation...');
                    
                    // Force grid layout with inline styles (highest specificity)
                    lessonsContainer.attr('style', `
                        display: grid !important;
                        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)) !important;
                        gap: 2rem !important;
                        padding: 1rem !important;
                        background: #f8fafc !important;
                        border-radius: 12px !important;
                        list-style: none !important;
                    `);
                    
                    // Transform each lesson item
                    $('.ld-accordion__item--lesson').each(function() {
                        const $lesson = $(this);
                        
                        // Style the lesson card
                        $lesson.attr('style', `
                            background: white !important;
                            border: 1px solid #e2e8f0 !important;
                            border-radius: 12px !important;
                            padding: 0 !important;
                            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05) !important;
                            transition: all 0.3s ease !important;
                            overflow: hidden !important;
                            display: flex !important;
                            flex-direction: column !important;
                        `);
                        
                        // Style the lesson header
                        $lesson.find('.ld-accordion__item-header--lesson').attr('style', `
                            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
                            color: white !important;
                            padding: 1.5rem !important;
                            margin: 0 !important;
                            border: none !important;
                        `);
                        
                        // Style lesson title
                        $lesson.find('.ld-accordion__item-title--lesson').attr('style', `
                            color: white !important;
                            font-size: 1.2rem !important;
                            font-weight: 600 !important;
                            text-decoration: none !important;
                            display: block !important;
                        `);
                        
                        // Style lesson attributes
                        $lesson.find('.ld-accordion__item-attributes--lesson').attr('style', `
                            margin-top: 0.5rem !important;
                            display: flex !important;
                            gap: 1rem !important;
                            flex-wrap: wrap !important;
                        `);
                        
                        // Style individual attributes
                        $lesson.find('.ld-accordion__item-attribute').attr('style', `
                            background: rgba(255, 255, 255, 0.2) !important;
                            padding: 0.25rem 0.75rem !important;
                            border-radius: 20px !important;
                            font-size: 0.8rem !important;
                            display: flex !important;
                            align-items: center !important;
                            gap: 0.5rem !important;
                        `);
                        
                        // Hide expand/collapse button
                        $lesson.find('.ld-accordion__expand-button--lesson').attr('style', 'display: none !important;');
                        
                        // Always show lesson content
                        $lesson.find('.ld-accordion__item-steps').attr('style', `
                            display: block !important;
                            padding: 1.5rem !important;
                            background: white !important;
                        `);
                        
                        // Style topics container
                        $lesson.find('.ld-accordion__items--topics').attr('style', `
                            display: flex !important;
                            flex-direction: column !important;
                            gap: 0.75rem !important;
                            padding: 0 !important;
                        `);
                        
                        // Style individual topics
                        $lesson.find('.ld-accordion__item--topic').each(function() {
                            $(this).attr('style', `
                                background: #f9fafb !important;
                                border: 1px solid #e5e7eb !important;
                                border-radius: 8px !important;
                                padding: 1rem !important;
                                transition: all 0.2s ease !important;
                                display: flex !important;
                                align-items: flex-start !important;
                                gap: 1rem !important;
                            `);
                        });
                        
                        // Style topic titles
                        $lesson.find('.ld-accordion__item-title--topic').attr('style', `
                            font-size: 1rem !important;
                            font-weight: 500 !important;
                            color: #374151 !important;
                            text-decoration: none !important;
                            flex: 1 !important;
                        `);
                        
                        // Style topic quizzes
                        $lesson.find('.ld-accordion__item--topic-quiz').attr('style', `
                            background: #eff6ff !important;
                            border: 1px solid #dbeafe !important;
                            border-radius: 6px !important;
                            padding: 0.75rem !important;
                            display: flex !important;
                            align-items: center !important;
                            gap: 0.75rem !important;
                        `);
                        
                        // Style topic quiz titles
                        $lesson.find('.ld-accordion__item-title--topic-quiz').attr('style', `
                            font-size: 0.9rem !important;
                            font-weight: 500 !important;
                            color: #1e40af !important;
                            text-decoration: none !important;
                        `);
                    });
                    
                    // Add hover effects with JavaScript
                    $('.ld-accordion__item--lesson').hover(
                        function() {
                            $(this).css({
                                'box-shadow': '0 8px 32px rgba(0, 0, 0, 0.12) !important',
                                'transform': 'translateY(-2px) !important',
                                'border-color': '#4f46e5 !important'
                            });
                        },
                        function() {
                            $(this).css({
                                'box-shadow': '0 2px 12px rgba(0, 0, 0, 0.05) !important',
                                'transform': 'translateY(0) !important',
                                'border-color': '#e2e8f0 !important'
                            });
                        }
                    );
                    
                    // Style the main course header
                    $('.ld-layout__header').attr('style', `
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                        color: white !important;
                        padding: 2rem !important;
                        border-radius: 12px !important;
                        margin-bottom: 2rem !important;
                    `);
                    
                    // Style progress elements
                    $('.ld-progress-percentage').attr('style', `
                        color: white !important;
                        font-weight: 600 !important;
                    `);
                    
                    $('.ld-progress-steps').attr('style', `
                        color: rgba(255, 255, 255, 0.9) !important;
                    `);
                    
                    $('.ld-progress-bar').attr('style', `
                        background: rgba(255, 255, 255, 0.2) !important;
                        border-radius: 25px !important;
                        height: 8px !important;
                    `);
                    
                    $('.ld-progress-bar-percentage').attr('style', `
                        background: #4ade80 !important;
                        border-radius: 25px !important;
                    `);
                    
                    // Style the main accordion header
                    $('.ld-accordion__header').attr('style', `
                        background: #f8fafc !important;
                        padding: 1.5rem !important;
                        border-radius: 12px !important;
                        margin-bottom: 1rem !important;
                        border: 1px solid #e2e8f0 !important;
                    `);
                    
                    $('.ld-accordion__heading').attr('style', `
                        font-size: 1.5rem !important;
                        font-weight: 700 !important;
                        color: #1f2937 !important;
                        margin: 0 !important;
                    `);
                    
                    $('.ld-accordion__expand-button--all').attr('style', `
                        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
                        color: white !important;
                        border: none !important;
                        border-radius: 8px !important;
                        padding: 0.75rem 1.5rem !important;
                        font-weight: 600 !important;
                        cursor: pointer !important;
                        transition: all 0.3s ease !important;
                    `);
                    
                    console.log('LearnDash layout transformation completed!');
                    
                    // Add a class to indicate transformation is complete
                    $('body').addClass('learndash-transformed');
                    
                } else {
                    console.log('Lessons container not found, retrying in 1 second...');
                    setTimeout(transformLearnDashLayout, 1000);
                }
                
            }, 500);
        });
    }
    
    // Initialize the transformation
    transformLearnDashLayout();
    
    // Also run on window load as a fallback
    $(window).on('load', function() {
        setTimeout(transformLearnDashLayout, 1000);
    });
    
})(jQuery);
