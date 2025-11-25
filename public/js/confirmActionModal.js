//file: public/js/confirmActionModal.js


    function openConfirmModal(options) {

        // Set modal texts (title and message)
        document.getElementById('confirmActionModalTitle').innerText = options.title;
        document.getElementById('confirmActionMessage').innerText = options.message;

        // Set action of the form
        document.getElementById('confirmActionForm').action = options.action;

        // Optional id
        if (options.id != undefined) {
            document.getElementById('confirmActionId').value = options.id;
        }

        // Set confirm button text and class
        document.getElementById('confirmActionButton').innerText = options.confirmButtonText ?? "Confirm";
        let btn = document.getElementById('confirmActionButton');
        btn.className = "btn " + (options.confirmButtonClass ?? "btn-danger");
        
        // Clean previous inputs if any
        const inputContainer = document.getElementById('confirmActionInputs');
        inputContainer.innerHTML = '';

        // If there are additional inputs, create and append them
        if (Array.isArray(options.inputs)) {

            options.inputs.forEach(input => {

                let html = `<div class="mb-3"> 
                    <label class="form-label">${input.label ?? ''}</label>
                    <input type="${input.type ?? 'text'}"
                        name="${input.name}"
                        value="${input.value ?? ''}"
                        placeholder="${input.placeholder ?? ''}"
                        class="form-control"                        
                        required
                    >
                </div>`;
                inputContainer.insertAdjacentHTML('beforeend', html);
            })
        }
        
        // Show the modal
        let modalEl = document.getElementById('confirmActionModal');
        let modal = new bootstrap.Modal(modalEl);
        modal.show();

    }
