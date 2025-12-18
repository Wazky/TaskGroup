import { init } from "i18next";

export const createFormHandler = (setFormData, setFormErros, formErrors, clearError) =>{
    return (event) => {
        const { name, value } = event.target;

        // Update form data state
        setFormData(prevData => ({
            ...prevData,
            [name]: value
        }));

        // Clear specific field error on change
        if (formErrors[name]) {
            setFormErros(prevErrors => ({
                ...prevErrors,
                [name]: ''
            }));
        }

        if (clearError) clearError(); // Clear general auth error if present

    };
};

export const resetForm = (initialFormState, setFormData, setFormErrors, clearError) => { 
    return () => {
        setFormData(initialFormState);
        setFormErrors({});
        if (clearError) clearError();
    }
};

