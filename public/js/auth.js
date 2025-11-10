// file: public/js/auth.js

// Mostrar/ocultar error al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    initializeErrorHandling();
    
    // Configurar desvanecimiento automático después de 5 segundos
    setTimeout(autoCloseErrorDiv, 5000);
    
    // Ocultar error al escribir en los campos o hacer click
    const forms = ['login-form', 'register-form'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('input', closeErrorDiv);
            });
        }
    });

    // Inicializar validación de contraseñas en el formulario de registro
    initializePasswordValidation();

    // Inicializar toggles de visibilidad de contraseña
    initializePasswordToggles();
});

function initializeErrorHandling() {
    // Show or hide general error
    toggleErrorDisplay();

    // Initialize field-specific error handling
    initializeFieldErrors();
}

function toggleErrorDisplay() {
    const errorDiv = document.getElementById('error-div');
    if (!errorDiv) return;
    
    const errorText = errorDiv.querySelector('p')?.textContent.trim();
    errorDiv.style.display = errorText ? 'flex' : 'none';
    
    // Si hay error, iniciar temporizador para desvanecer
    if (errorText) {
        setTimeout(autoCloseErrorDiv, 5000);
    }
}

function initializeFieldErrors() {
    // Encontrar todos los campos con errores (que tienen border-danger)
    const errorFields = document.querySelectorAll('.border-danger');
    
    errorFields.forEach(field => {
        // Mostrar el mensaje de error asociado SOLO si tiene contenido
        const errorMessage = field.nextElementSibling;
        if (errorMessage && errorMessage.classList.contains('error-message')) {
            const messageText = errorMessage.textContent.trim();
            // Solo mostrar si el mensaje no está vacío
            if (messageText) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        }
        
        // Añadir evento para limpiar error al interactuar con el campo
        field.addEventListener('focus', clearFieldError);
        field.addEventListener('input', clearFieldError);
    });
    
    // Añadir evento para cerrar mensajes de error al hacer click en ellos
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(message => {
        // Solo añadir evento a mensajes que tienen contenido
        const messageText = message.textContent.trim();
        if (messageText) {
            message.addEventListener('click', function() {
                this.style.display = 'none';
                // También quitar border-danger del campo asociado
                const field = this.previousElementSibling;
                if (field && field.classList.contains('border-danger')) {
                    field.classList.remove('border-danger');
                }
            });
        } else {
            // Ocultar mensajes vacíos
            message.style.display = 'none';
        }
    });    
}

function clearFieldError() {
    // Quitar border-danger del campo
    this.classList.remove('border-danger');
    
    // Ocultar mensaje de error asociado
    const errorMessage = this.nextElementSibling;
    if (errorMessage && errorMessage.classList.contains('error-message')) {
        errorMessage.style.display = 'none';
    }
}

function closeErrorDiv() {
    const errorDiv = document.getElementById('error-div');
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
}

function autoCloseErrorDiv() {
    const errorDiv = document.getElementById('error-div');
    if (errorDiv && errorDiv.style.display !== 'none') {
        // Añadir efecto de desvanecimiento
        errorDiv.style.opacity = '1';
        errorDiv.style.transition = 'opacity 0.5s ease-out';
        
        // Iniciar desvanecimiento
        setTimeout(() => {
            errorDiv.style.opacity = '0';
        }, 100);
        
        // Ocultar completamente después de la animación
        setTimeout(() => {
            errorDiv.style.display = 'none';
            errorDiv.style.opacity = '1'; // Reset para la próxima vez
        }, 600);
    }
}

function initializePasswordValidation() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const errorDiv = document.querySelector('.form-group:has(#confirm_password) .error');
    
    // Solo inicializar si estamos en la página de registro
    if (!passwordInput || !confirmPasswordInput || !errorDiv) return;
    
    // Ocultar mensaje de error inicialmente
    errorDiv.style.display = 'none';
    
    // Validar en tiempo real
    passwordInput.addEventListener('input', validatePasswordMatch);
    confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    
    // Validar al enviar el formulario
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validatePasswordMatch()) {
                e.preventDefault(); // Prevenir envío si las contraseñas no coinciden
            }
        });
    }
}

function validatePasswordMatch() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const errorDiv = document.querySelector('.form-group:has(#confirm_password) .error');
    
    if (!passwordInput || !confirmPasswordInput || !errorDiv) return true;
    
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    
    // Solo validar si ambos campos tienen contenido
    if (password && confirmPassword) {
        if (password !== confirmPassword) {
            // Mostrar error
            errorDiv.style.display = 'block';
            confirmPasswordInput.classList.add('border-danger');
            return false;
        } else {
            // Ocultar error
            errorDiv.style.display = 'none';
            confirmPasswordInput.classList.remove('border-danger');
            return true;
        }
    } else {
        // Ocultar error si algún campo está vacío
        errorDiv.style.display = 'none';
        confirmPasswordInput.classList.remove('border-danger');
        return true;
    }
}

// Cerrar con click en el error-div
document.addEventListener('click', function(e) {
    if (e.target.closest('#error-div')) {
        closeErrorDiv();
    }
});

// También puedes cerrar con la tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeErrorDiv();
    }
});