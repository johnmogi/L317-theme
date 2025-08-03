/**
 * Force Flat List - JavaScript to replace LearnDash accordion with flat structure
 */

(function($) {
    'use strict';
    
    function createFlatList() {
        console.log('LILAC: Starting flat list transformation...');
        
        // Find the main course content container
        var $courseContent = $('.ld-course-content, .learndash-wrapper');
        
        if ($courseContent.length === 0) {
            console.log('LILAC: Course content container not found');
            return;
        }
        
        // Find all accordion items
        var $accordionItems = $('.ld-accordion__item, .ld-item-list-item');
        
        if ($accordionItems.length === 0) {
            console.log('LILAC: No accordion items found');
            return;
        }
        
        console.log('LILAC: Found ' + $accordionItems.length + ' accordion items');
        
        // Create flat list structure
        var flatListHtml = '<div class="ld-flat-list-container">';
        
        $accordionItems.each(function(index, item) {
            var $item = $(item);
            var $title = $item.find('.ld-accordion__item-title, .ld-item-name');
            var $link = $title.find('a').length ? $title.find('a') : $title.closest('a');
            
            if ($title.length > 0) {
                var titleText = $title.text().trim();
                var linkHref = $link.length ? $link.attr('href') : '#';
                
                // Determine completion status
                var isCompleted = $item.hasClass('ld-status-complete') || 
                                $item.hasClass('ld-status-completed') ||
                                $item.find('.ld-status-complete').length > 0;
                
                var statusClass = isCompleted ? 'completed' : 'incomplete';
                var statusIcon = isCompleted ? '✓' : '○';
                var statusText = isCompleted ? 'הושלם' : 'לא הושלם';
                
                flatListHtml += '<div class="ld-flat-item ' + statusClass + '">';
                flatListHtml += '<a href="' + linkHref + '" class="ld-flat-link">';
                flatListHtml += '<span class="ld-flat-title">' + titleText + '</span>';
                flatListHtml += '<span class="ld-flat-status">' + statusIcon + ' ' + statusText + '</span>';
                flatListHtml += '</a>';
                flatListHtml += '</div>';
            }
        });
        
        flatListHtml += '</div>';
        
        // Replace the accordion content
        var $accordion = $('.ld-accordion, .ld-item-list');
        if ($accordion.length > 0) {
            $accordion.html(flatListHtml);
            console.log('LILAC: Replaced accordion with flat list');
        } else {
            // If no accordion found, append to course content
            $courseContent.append(flatListHtml);
            console.log('LILAC: Appended flat list to course content');
        }
        
        // Apply CSS styles
        addFlatListStyles();
    }
    
    function addFlatListStyles() {
        var css = `
        <style id="lilac-flat-list-styles">
        .ld-flat-list-container {
            direction: rtl;
            text-align: right;
            font-family: 'Assistant', sans-serif;
            background: white;
            border: 1px solid #ddd;
            margin: 20px 0;
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
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 15px 20px !important;
            text-decoration: none !important;
            color: #333 !important;
            background: white !important;
            transition: background 0.2s !important;
            border: none !important;
            margin: 0 !important;
        }
        
        .ld-flat-link:hover {
            background: #f9f9f9 !important;
            color: #2C3391 !important;
        }
        
        .ld-flat-title {
            font-weight: 500 !important;
            flex-grow: 1 !important;
            text-align: right !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .ld-flat-status {
            font-size: 0.9rem !important;
            color: #666 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .ld-flat-item.completed .ld-flat-status {
            color: #22c55e !important;
        }
        
        /* Hide original accordion elements */
        .ld-accordion__expand-button,
        .ld-accordion__item-header,
        .ld-item-list-section-heading {
            display: none !important;
        }
        
        /* Force flat display for any remaining elements */
        .ld-accordion__item-steps,
        .ld-item-list-items {
            display: block !important;
            max-height: none !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        </style>
        `;
        
        // Remove existing styles and add new ones
        $('#lilac-flat-list-styles').remove();
        $('head').append(css);
    }
    
    // Run when document is ready
    $(document).ready(function() {
        console.log('LILAC: Document ready, attempting flat list transformation...');
        
        // Try immediately
        createFlatList();
        
        // Try again after a short delay (in case content loads dynamically)
        setTimeout(createFlatList, 1000);
        
        // Try again after longer delay
        setTimeout(createFlatList, 3000);
    });
    
    // Also run when window loads
    $(window).on('load', function() {
        console.log('LILAC: Window loaded, attempting flat list transformation...');
        createFlatList();
    });
    
})(jQuery);
