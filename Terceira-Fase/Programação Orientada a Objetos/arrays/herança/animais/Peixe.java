package arrays.herança.animais;

class Peixe extends AnimalDomestico {
    String tipo;
    public void alimentacao() {
        System.out.println("O peixe " + nome + " está comendo flocos de peixe.");
    }
    public void informacoesEspecificas() {
        System.out.println("Tipo de peixe: " + tipo);
    }
}
