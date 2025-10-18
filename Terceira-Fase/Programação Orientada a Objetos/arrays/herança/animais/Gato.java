package arrays.herança.animais;

class Gato extends AnimalDomestico {
    String raca;
    public void alimentacao() {
        System.out.println("O gato " + nome + " está comendo peixe.");
    }
    public void informacoesEspecificas() {
        System.out.println("Raça do gato: " + raca);
    }
}