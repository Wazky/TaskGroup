<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskGroup - Project</title>

    <link rel="stylesheet" href="/public/css/projects.css">
    <link rel="stylesheet" href="/public/css/tasks.css">
</head>
<body>
    <!-- #include virtual="/views/shared/components/header.html" -->
    
    <main class="project-detail">
        <div class="project-header">
            <h1>Project Title</h1>

            <div class=""projects-actions>
                <button onclick="openAddUserModal();">Add User</button>
                <button onclick="openCreateTaskModal();">New Task</button>
                <button onclick="viewSummary();">View Summary</button>
                <button onclick="deleteProject();">Delete Project</button>
            </div>

        </div>

        <div class="task-board">
            <div class="tasks-column">
                <h2>To Do</h2>

                <div class="task-list" id="pendingTasks">
                    <!-- Tasks will be dynamically loaded here -->
                </div>
            </div>

            <div class="tasks-column">
                <h2>Solved</h2>

                <div class="task-list" id="completedTasks">
                    <!-- Tasks will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </main>

    <!-- Modals -->
    <!--#include virtual="/views/shared/modals/add-user-modal.html" -->
    <!--#include virtual="/views/shared/modals/create-task-modal.html" -->
    <!--#include virtual="/views/shared/modals/confirm-delete-modal.html" -->
    
    <script src="/public/js/projects.js"></script>
    <script src="/public/js/tasks.js"></script>

</body>
</html>