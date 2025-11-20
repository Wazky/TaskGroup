document.addEventListener('click', function(e) {
    
    const element = e.target.closest('[data-entity][data-id]');
    
    if (element && !e.target.closest('a, button')) {
        const entity = element.dataset.entity;
        const id = element.dataset.id;
        window.location.href = `index.php?controller=${entity}s&action=detail&id=${id}`;
    }
});