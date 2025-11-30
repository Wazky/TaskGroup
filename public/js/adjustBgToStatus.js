// file: public/js/adjustBgToStatus.js

/**
 * Función para actualizar los colores según el estado de la tarea
 */
function updateStatusColors() {
    const statusSelect = document.getElementById('task_status');
    const selectedStatus = statusSelect.value;
    const headerElement = document.getElementById('header-status');
    const formElement = document.getElementById('form-status');
    const saveButton = document.getElementById('save-button');
    
    if (selectedStatus === 'completed') {
        // Colores para estado "completed"
        headerElement.className = 'd-flex justify-content-center align-items-center mb-2 bg-tg-primary-dark rounded';
        formElement.className = 'd-flex justify-content-center bg-tg-primary rounded shadow-sm p-4';
        saveButton.className = 'btn btn-lg bg-tg-secondary text-light fw-bold';
    } else {
        // Colores para estado "to do"
        headerElement.className = 'd-flex justify-content-center align-items-center mb-2 bg-tg-secondary-dark rounded';
        formElement.className = 'd-flex justify-content-center bg-tg-secondary rounded shadow-sm p-4';
        saveButton.className = 'btn btn-lg bg-tg-primary text-light fw-bold';
    }
}

/**
 * Inicializa los event listeners para el cambio de estado
 */
function initStatusColorChanger() {
    const statusSelect = document.getElementById('task_status');
    
    if (statusSelect) {
        // Agregar event listener para cambios en el select
        statusSelect.addEventListener('change', updateStatusColors);
        
        // Inicializar colores al cargar la página
        updateStatusColors();
    }
}

// Inicializar cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    initStatusColorChanger();
});