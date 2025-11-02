<!-- Create Task Modal -->
<div id="createTaskModal" class="modal">
    <div class="modal-content">
        <h2>Create New Task</h2>

        <form id="createTaskForm">
            <input type="text" name="name" placeholder="Task name" required>
            
            <select name="assignedUser" id="assignedUserSelect">
                <!-- User options will be dynamically loaded here -->    
            </select>

            <div class="modal-actions">
                <button type="button" onclick="closeModal('openCreateTaskModal')">Cancel</button>

                <button type="submit">Create Task</button>
            </div>
        </form>
    </div>
</div>