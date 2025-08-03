/**
 * Auto-dismiss session timeout dialogs to prevent interference during layout testing
 */
(function() {
    'use strict';
    
    function dismissSessionDialog() {
        // Look for session timeout dialog and dismiss it
        const sessionDialog = document.querySelector('[role="dialog"]');
        const yesButton = document.querySelector('button:contains("Yes"), input[value="Yes"]');
        
        if (sessionDialog && yesButton) {
            yesButton.click();
            console.log('Session dialog auto-dismissed');
        }
        
        // Also look for any modal overlays
        const modalOverlay = document.querySelector('.modal-overlay, .dialog-overlay');
        if (modalOverlay) {
            modalOverlay.style.display = 'none';
        }
    }
    
    // Run immediately
    dismissSessionDialog();
    
    // Run periodically to catch new dialogs
    setInterval(dismissSessionDialog, 2000);
    
    // Run on DOM changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                setTimeout(dismissSessionDialog, 100);
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
})();
