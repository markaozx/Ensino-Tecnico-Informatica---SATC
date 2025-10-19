package aula10;

public class Mamifero extends Animal {
    private String alimento;

    // Construtor
    public Mamifero(String nome, float comprimento, int numeroDePatas, String cor, String ambiente, float velocidadeMedia, String alimento) {
        super(nome, comprimento, numeroDePatas, cor, ambiente, velocidadeMedia);
        this.alimento = alimento;
    }

    // Método para exibir dados do mamífero
    public void dadosMamifero() {
        super.dados(); // Chama os dados da classe Animal
        System.out.println("Alimento: " + alimento);
    }
}