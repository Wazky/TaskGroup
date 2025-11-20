// file: public/js/collapse_sections.js
document.addEventListener('DOMContentLoaded', function() {
    // Configuration for all toggle buttons
    document.querySelectorAll('.section-toggle').forEach(button => {
        const targetId = button.getAttribute('data-bs-target');
        const collapseElement = document.querySelector(targetId);
        const toggleIcon = button.querySelector('.toggle-icon');
        const iconCollapsed = button.getAttribute('data-toggle-icon') || 'bi-chevron-down';
        const iconExpanded = 'bi-chevron-up';

        if(collapseElement) {
            // Add hide event listener
            collapseElement.addEventListener('hide.bs.collapse', function() {
                toggleIcon.className = `bi ${iconCollapsed} toggle-icon`;
            });
            // Add show event listener
            collapseElement.addEventListener('show.bs.collapse', function() {
                toggleIcon.className = `bi ${iconExpanded} toggle-icon`;
            });
        }
    });
});