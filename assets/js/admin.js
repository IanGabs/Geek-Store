function toggleForm() {
    const form = document.getElementById('add-product-form');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

// Confirmar antes de remover produto
document.addEventListener('DOMContentLoaded', () => {
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Tem certeza que deseja remover este produto?')) {
                e.preventDefault();
            }
        });
    });
});