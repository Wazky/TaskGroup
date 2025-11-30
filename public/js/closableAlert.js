// file: public/js/closableAlert.js

function initFlashMessages() {
    const flashMessage = document.getElementById('flash-message');
    
    if (flashMessage) {
        const autoDismissTime = flashMessage.getAttribute('data-auto-dismiss');
        
        if (autoDismissTime && !isNaN(autoDismissTime)) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(flashMessage);
                bsAlert.close();
            }, parseInt(autoDismissTime));
        }
    }
}


// Inicializar cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    initFlashMessages();
});