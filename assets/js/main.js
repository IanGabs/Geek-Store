document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.btn-adicionar-carrinho');
    const carrinhoContador = document.querySelector('.carrinho-contador');

    atualizarContadorCarrinho();

    addToCartButtons.forEach(button => {
        button.addEventListener('click', async (event) => {
            const produtoId = event.target.dataset.produtoId;
            
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

    // Atualiza contador ao carregar a página
    async function atualizarContadorCarrinho() {
        try {
            const response = await fetch('./api/carrinho.php');
            const data = await response.json();
            
            if (data.status === 'success' && carrinhoContador) {
                carrinhoContador.textContent = data.total_itens;
            }
        } catch (error) {
            console.error('Erro ao carregar contador do carrinho:', error);
        }
    }
});