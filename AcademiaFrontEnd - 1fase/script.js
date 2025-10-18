// Função para alternar as imagens do banner
const banners = document.querySelectorAll('.banner img');
let currentIndex = 0;

function switchBanner() {
  banners[currentIndex].classList.remove('active');
  currentIndex = (currentIndex + 1) % banners.length; // Lógica para ir de 0 até o número de imagens
  banners[currentIndex].classList.add('active');
}

// Iniciar a troca de banners a cada 5 segundos
setInterval(switchBanner, 5000); // Troca a cada 5 segundos

// Validação do formulário de inscrição
document.getElementById("formInscricao").addEventListener("submit", function (event) {
    const nome = document.getElementById("nome").value.trim();
    const cep = document.getElementById("cep").value.trim();
    const telefone = document.getElementById("telefone").value.trim();
  
    // Verificar se os campos obrigatórios estão preenchidos
    if (!nome || !cep || !telefone) {
      alert("Por favor, preencha todos os campos obrigatórios.");
      event.preventDefault();
      return;
    }
  
    // Validar o CEP (deve ter 8 números)
    if (cep.length !== 8 || isNaN(cep)) {
      alert("O CEP deve conter 8 números.");
      event.preventDefault();
      return;
    }
  
    // Validar o telefone (apenas números)
    const telefoneRegex = /^\d+$/;
    if (!telefoneRegex.test(telefone)) {
      alert("O telefone deve conter apenas números.");
      event.preventDefault();
    }
});
