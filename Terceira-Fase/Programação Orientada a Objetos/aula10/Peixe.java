package aula10;

public class Peixe extends Animal {
    private String caracteristica;

    // Construtor
    public Peixe(String nome, float comprimento, int numeroDePatas, String cor, String ambiente, float velocidadeMedia, String caracteristica) {
        super(nome, comprimento, numeroDePatas, cor, ambiente, velocidadeMedia);
        this.caracteristica = caracteristica;
    }

    // Método para exibir dados do peixe
    public void dadosPeixe() {
        super.dados(); // Chama os dados da classe Animal
        System.out.println("Característica: " + caracteristica);
    }
}