Ismael Salgado López 44663770R


Dependencias instaladas junto con REACT:
    
    (Dependencias de Producción)
react-router-dom (Enrutamiento SPA)
i18next + react-i18next (Internacionalización)
    
    (Dependencias de Desarrollo)
@types/react + @types/react-dom (Tipos para Typescript)
eslint (Análisis de código)



Habría que ajustar en los REST para sustituir el empleo de i18n para traducir en back
los mensajes a enviar, en su lugar mandar las claves de traducción para poder efectuar 
la traducción en frontend (a mayores se podría seguir incluyendo el mensaje en un idioma
por defecto para facilitar interpretación humana en algunos casos).