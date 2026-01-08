/**
 * Simple dropdown menu - Works like a drawer
 * Always opens downward from the button and stays visible
 */
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.dropdown');
    const dropdownToggle = dropdown?.querySelector('.dropdown-toggle');
    const dropdownMenu = dropdown?.querySelector('.dropdown-menu');
    
    if (!dropdown || !dropdownToggle || !dropdownMenu) return;
    
    // Ensure dropdown opens below button
    function ensureVisibility() {
        if (!dropdownMenu.classList.contains('show')) return;
        
        const rect = dropdownToggle.getBoundingClientRect();
        const menuRect = dropdownMenu.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        
        // Make sure menu doesn't go above viewport
        if (menuRect.top < 0) {
            dropdownMenu.style.top = '100%';
            dropdownMenu.style.marginTop = Math.max(8, Math.abs(menuRect.top) + 10) + 'px';
        }
        
        // Make sure menu doesn't go below viewport
        if (menuRect.bottom > viewportHeight) {
            const maxHeight = viewportHeight - rect.bottom - 20;
            dropdownMenu.style.maxHeight = maxHeight + 'px';
        }
    }
    
    // Toggle dropdown
    dropdownToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const wasOpen = dropdownMenu.classList.contains('show');
        dropdownMenu.classList.toggle('show');
        
        if (!wasOpen) {
            // Just opened, ensure it's visible
            setTimeout(ensureVisibility, 10);
        }
    });
    
    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
    
    // Prevent menu from closing when clicking inside
    dropdownMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdownMenu.classList.remove('show');
        }
    });
});
