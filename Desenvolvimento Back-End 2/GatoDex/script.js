document.getElementById('get-cat-btn').addEventListener('click', function(event) {
    event.preventDefault();
    
    const catImage = document.getElementById('cat-image');
    const button = document.getElementById('get-cat-btn');
    
    // Mostra loading
    button.textContent = 'Buscando...';
    button.disabled = true;
    catImage.src = 'https://static.wixstatic.com/media/68315b_30dbad1140034a3da3c59278654e1655~mv2.gif';
    catImage.alt = 'Carregando...';
    
    fetch('buscar_gato.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                catImage.src = data.image;
                catImage.alt = 'Imagem de um gato';
            } else {
                catImage.src = 'https://via.placeholder.com/400x300?text=Erro+ao+carregar+imagem';
                catImage.alt = 'Erro ao carregar imagem';
                console.error('Erro:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            catImage.src = 'https://via.placeholder.com/400x300?text=Erro+de+conexao';
            catImage.alt = 'Erro de conexão';
        })
        .finally(() => {
            // Restaura o botão
            button.textContent = 'Buscar';
            button.disabled = false;
        });
});