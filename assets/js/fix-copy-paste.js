/**
 * Ensure copy/paste/select always works in forms
 * This fixes any potential blocking of user input interactions
 */

(function() {
    'use strict';
    
    // Remove any potential blockers on inputs and textareas
    function enableCopyPaste() {
        const editableElements = document.querySelectorAll('input, textarea, [contenteditable="true"]');
        
        editableElements.forEach(element => {
            // Ensure element can be selected
            element.style.userSelect = 'text';
            element.style.webkitUserSelect = 'text';
            element.style.mozUserSelect = 'text';
            element.style.msUserSelect = 'text';
            
            // Remove any blocking event listeners by allowing events to propagate
            ['copy', 'paste', 'cut', 'selectstart', 'contextmenu'].forEach(eventType => {
                element.addEventListener(eventType, function(e) {
                    // Allow the event to proceed normally
                    e.stopImmediatePropagation();
                }, true); // Use capture phase to intercept before other handlers
            });
        });
    }
    
    // Run immediately and on DOM changes (for dynamically added elements)
    enableCopyPaste();
    
    // Re-apply when new elements are added
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                enableCopyPaste();
            }
        });
    });
    
    // Wait for body to be available before observing
    function setupObserver() {
        if (document.body) {
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        } else {
            // Retry after a short delay
            setTimeout(setupObserver, 10);
        }
    }
    
    setupObserver();
    
    // Also run after DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        enableCopyPaste();
        setupObserver();
    });
    
    console.log('✓ Copy/Paste/Select enabled for all form inputs');
})();
