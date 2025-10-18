package aula13.empresa;

class gerente extends funcionario {
    private String setor;

    public gerente(String codigo, String nome, String sexo, int horasTrabalhadas, double valorHora, String setor) {
        super(codigo, nome, sexo, horasTrabalhadas, valorHora);
        this.setor = setor;
    }

    public void aplicarBonificacaoGerente() {
        // Gerentes recebem 2% de bonificão a mais (5% do normal mais 2% de gerente)
        double bonificacao = 0.02 * salario;
        this.salario += bonificacao;
    }

    public void mostrarDadosGerente() {
        super.mostrarDados();
        System.out.println("Setor: " + setor);
    }
}