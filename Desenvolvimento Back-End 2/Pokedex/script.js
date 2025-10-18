document.getElementById('search-form').addEventListener('submit', function(event){
    event.preventDefault();
    
    const form = event.target;
    const pokemonNome = form.elements['pokemon_name'].value;
    console.log(pokemonNome);

    const imagem = document.getElementById('pokemon-image')
    const nome = document.getElementById('pokemon-name')
    const id = document.getElementById('pokemon-id')
    const tipo = document.getElementById('pokemon-type')
    const desc = document.getElementById('pokemon-description')

    nome.textContent = "Buscando...";
    id.textContent = "";
    tipo.textContent = "";
    desc.textContent = "Buncando descrição..."
    imagem.src = "https://i.pinimg.com/originals/0a/50/6f/0a506fe0f6c211128cf1ed370655c6a1.gif"
    
    fetch(`buscar.php?pokemonNome=${pokemonNome}`)
                .then(response => response.json())
                .then(data => {
                    // se der certo cai aqui
                    imagem.src = data.image;
                    nome.textContent = data.name;
                    id.textContent = data.id;
                    tipo.textContent = data.type;
                    desc.textContent = data.descripction;


                })
                .catch(error => {
                    console.log(error);
                })


})