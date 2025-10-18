package aula13.empresa;

class engenheiro extends funcionario {
    private String nrCREA;
    private String especializacao;

    public engenheiro(String codigo, String nome, String sexo, int horasTrabalhadas, double valorHora, String nrCREA, String especializacao) {
        super(codigo, nome, sexo, horasTrabalhadas, valorHora);
        this.nrCREA = nrCREA;
        this.especializacao = especializacao;
    }

    public void aplicarBonificacaoEngenheiro() {
        // Engenheiros recebem 5% de bonifica��o como funcion�rios comuns
        super.aplicarBonificacao();
    }

    public void mostrarDadosEngenheiro() {
        super.mostrarDados();
        System.out.println("N�mero CREA: " + nrCREA);
        System.out.println("Especializa��o: " + especializacao);
    }
}