package arrays.herança.animais;

class AnimalDomestico {
    String nome;
    int idade;

    public void alimentacao() {
        System.out.println("O animal " + nome + " está se alimentando.");
        // colocar mais itens
    }
    // Método para exibir informações específicas da classe filha
    public void informacoesEspecificas() {
        System.out.println("Informações específicas não disponíveis.");
        // colocar mais itens
    }
}