document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. LÓGICA DO CARRINHO DE COMPRAS ---
    const addToCartButtons = document.querySelectorAll('.btn-adicionar-carrinho');
    const carrinhoContador = document.querySelector('.carrinho-contador');

    // Atualiza contador ao carregar a página
    atualizarContadorCarrinho();

    addToCartButtons.forEach(button => {
        button.addEventListener('click', async (event) => {
            // Pega o ID do botão clicado (suporta clique no ícone ou no botão)
            const target = event.target.closest('.btn-adicionar-carrinho');
            const produtoId = target.dataset.produtoId;
            
            try {
                const response = await fetch('./api/carrinho.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ produto_id: produtoId })
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    if (carrinhoContador) {
                        carrinhoContador.textContent = data.total_itens;
                    }
                    mostrarNotificacao('Produto adicionado ao carrinho!');
                } else {
                    mostrarNotificacao('Erro ao adicionar ao carrinho!', true);
                }
            } catch (error) {
                console.error('Erro:', error);
                mostrarNotificacao('Erro ao adicionar ao carrinho!', true);
            }
        });
    });

    async function atualizarContadorCarrinho() {
        try {
            const response = await fetch('./api/carrinho.php');
            const data = await response.json();
            
            if (data.status === 'success' && carrinhoContador) {
                carrinhoContador.textContent = data.total_itens;
            }
        } catch (error) {
            console.error('Erro ao carregar contador:', error);
        }
    }

    function mostrarNotificacao(mensagem, isError = false) {
        const notificacao = document.createElement('div');
        notificacao.classList.add('notificacao');
        if (isError) {
            notificacao.classList.add('notificacao-erro');
        }
        notificacao.textContent = mensagem;
        document.body.appendChild(notificacao);

        setTimeout(() => {
            notificacao.remove();
        }, 3000);
    }

    // --- 2. LÓGICA DO MENU DE PERFIL (DROPDOWN) ---
    const profileTrigger = document.getElementById('profileTrigger');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileTrigger && profileDropdown) {
        // Alternar ao clicar na imagem
        profileTrigger.addEventListener('click', (e) => {
            e.stopPropagation(); // Impede que o clique feche imediatamente
            profileDropdown.classList.toggle('show');
        });

        // Fechar ao clicar fora
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target) && !profileTrigger.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });

        // Fechar ao apertar ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                profileDropdown.classList.remove('show');
            }
        });
    }
});