document.addEventListener('DOMContentLoaded', () => {
    const carrinhoContainer = document.querySelector('.itens-carrinho');
    
    async function atualizarContador() {
        try {
            const response = await fetch('./api/carrinho.php');
            const data = await response.json();
            
            const carrinhoContador = document.querySelector('.carrinho-contador');
            if (carrinhoContador) {
                carrinhoContador.textContent = data.total_itens;
            }

            if (data.total_itens === 0 && window.location.pathname.includes('carrinho.php')) {
                location.reload();
            }
        } catch (error) {
            console.error('Erro ao atualizar contador:', error);
        }
    }

    if (carrinhoContainer) {
        carrinhoContainer.addEventListener('click', async (event) => {
            const produtoId = event.target.dataset.produtoId;
            
            if (!produtoId) return;

            try {
                // AUMENTAR QUANTIDADE
                if (event.target.classList.contains('btn-aumentar')) {
                    const response = await fetch('./api/carrinho.php', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ produto_id: produtoId, acao: 'aumentar' })
                    });
                    
                    await processarResposta(response);
                }

                // DIMINUIR QUANTIDADE
                if (event.target.classList.contains('btn-diminuir')) {
                    const response = await fetch('./api/carrinho.php', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ produto_id: produtoId, acao: 'diminuir' })
                    });
                    
                    await processarResposta(response);
                }

                // REMOVER ITEM
                if (event.target.classList.contains('btn-remover')) {
                    const response = await fetch('./api/carrinho.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ produto_id: produtoId })
                    });
                    
                    await processarResposta(response);
                }
            } catch (error) {
                console.error('Erro:', error);
                mostrarNotificacao('Erro ao atualizar carrinho!', true);
            }
        });
    }

    async function processarResposta(response) {
        const data = await response.json();
        
        if (data.status === 'success') {
            location.reload();
        } else {
            mostrarNotificacao('Erro ao atualizar carrinho!', true);
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
    atualizarContador();

    const btnFinalizar = document.getElementById('btn-finalizar');
    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', async () => {
            try {
                const response = await fetch('./api/carrinho.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ action: 'finalizar' })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    mostrarNotificacao(data.message);
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    mostrarNotificacao(data.message, true);
                    if (data.message.includes('login')) {
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 2000);
                    }
                }
            } catch (error) {
                console.error('Erro:', error);
                mostrarNotificacao('Erro ao finalizar compra!', true);
            }
        });
    }
});