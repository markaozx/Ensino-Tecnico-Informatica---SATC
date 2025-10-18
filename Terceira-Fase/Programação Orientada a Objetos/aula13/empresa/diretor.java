// filho diretor

package aula13.empresa;

class diretor extends funcionario {
    private String areaGestao;

    public diretor(String codigo, String nome, String sexo, int horasTrabalhadas, double valorHora, String areaGestao) {
        super(codigo, nome, sexo, horasTrabalhadas, valorHora);
        this.areaGestao = areaGestao;
    }

    public void aplicarBonificacaoDiretor() {
        // Diretores recebem 2% de bonifica��o
        double bonificacao = 0.02 * salario;
        this.salario += bonificacao;
    }

    public void mostrarDadosDiretor() {
        super.mostrarDados();
        System.out.println("�rea de Gest�o: " + areaGestao);
    }
}