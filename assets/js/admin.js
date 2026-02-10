/**
 * Admin Panel JavaScript
 * Ensures admin navigation works properly
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin JS loaded - forcing navigation handlers');
    
    // AGGRESSIVE FIX: Remove href and add direct onclick handlers
    const adminNavLinks = document.querySelectorAll('.admin-nav-link');
    console.log('Found', adminNavLinks.length, 'admin nav links');
    
    adminNavLinks.forEach((link, index) => {
        const originalHref = link.getAttribute('href');
        console.log(`Link ${index + 1}:`, link.textContent.trim(), '→', originalHref);
        
        // Remove href to prevent any default behavior
        link.removeAttribute('href');
        
        // Add cursor pointer style
        link.style.cursor = 'pointer';
        
        // Add direct onclick handler
        link.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Navigating to:', originalHref);
            window.location.href = originalHref;
            return false;
        };
    });
    
    console.log('All admin nav links converted to onclick handlers');
});
