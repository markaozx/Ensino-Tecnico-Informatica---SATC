package arrays.herança.animais;

class Cao extends AnimalDomestico {
    String raca;
    public void alimentacao() {
        System.out.println("O cão " + nome + " está comendo ração.");
    }
    public void informacoesEspecificas() {
        System.out.println("Raça do cão: " + raca);
    }
}