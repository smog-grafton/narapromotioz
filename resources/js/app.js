// Import Bootstrap
import 'bootstrap';

// Custom JavaScript for the application
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any custom functionality here
    console.log('Nara Promotionz app loaded');
    
    // Example: Initialize tooltips if needed
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Example: Initialize popovers if needed
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}); 