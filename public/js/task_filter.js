// file: public/js/task_filter.js

document.addEventListener('DOMContentLoaded', function() {
    // Set up filters for "Your tasks"
    console.log('Initializing task filters...');
    setupTaskFilters('your-tasks-container', 'your-tasks-filter');
    
    // Set up filters for "All tasks"
    setupTaskFilters('all-tasks-container', 'all-tasks-filter');

    function setupTaskFilters(containerClass, filterGroup) {
        console.log(`Setting up filters for container: ${containerClass}, filter group: ${filterGroup}`);
        const filterButtons = document.querySelectorAll(`[aria-label="Filter ${filterGroup === 'your-tasks-filter' ? 'Your Tasks' : 'All Tasks'}"] .filter-btn`);
        const taskItems = document.querySelectorAll(`.${containerClass} .task-item`);

        filterButtons.forEach(button => {
            console.log(`Attaching click event to button: ${button.getAttribute('data-filter')}`);
            button.addEventListener('click', function() {
                console.log(`Filter button clicked: ${this.getAttribute('data-filter')}`);
                const filterValue = this.getAttribute('data-filter');

                // Remove 'active' class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add 'active' class to the clicked button
                this.classList.add('active');

                // Filter tasks based on the selected filter
                taskItems.forEach(task => {
                    const taskStatus = task.getAttribute('data-status');

                    if (filterValue === 'all' || taskStatus === filterValue) {
                        task.style.display = 'block';
                    } else {
                        task.style.display = 'none';
                    }
                });
            });
        });

    }

});