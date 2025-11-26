function toggleForm() {
    const form = document.getElementById('add-product-form');
    if (form && (form.style.display === 'none' || form.style.display === '')) {
        form.style.display = 'block';
    } else if (form) {
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

    const profileTrigger = document.getElementById('profileTrigger');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileTrigger && profileDropdown) {
        profileTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target) && !profileTrigger.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                profileDropdown.classList.remove('show');
            }
        });
    }
});

function previewImage(input) {
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('image-preview');
    const fileName = document.getElementById('file-name');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImage.src = e.target.result;
            fileName.textContent = input.files[0].name;
            previewContainer.style.display = 'block';
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        previewImage.src = '#';
        previewContainer.style.display = 'none';
    }
}