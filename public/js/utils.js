// file: public/js/utils.js

export function getCSSVariableValue(variableName, defaultValue = '') {
    try {
        const value = getComputedStyle(document.documentElement)
            .getPropertyValue(variableName)
            .trim();
        return value || defaultValue;
    } catch (error) {
        console.warn('Variable CSS not found:', variableName);
        return defaultValue;
    }
}

window.getCSSVariableValue = getCSSVariableValue;

