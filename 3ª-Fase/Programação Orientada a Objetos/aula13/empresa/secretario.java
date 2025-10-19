package aula13.empresa;

class secretario extends funcionario {
    private String categoria;
    private String setor;

    public secretario(String codigo, String nome, String sexo, int horasTrabalhadas, double valorHora, String categoria, String setor) {
        super(codigo, nome, sexo, horasTrabalhadas, valorHora);
        this.categoria = categoria;
        this.setor = setor;
    }

    public void aplicarBonificacaoSecretario() {
        // Secret�rios recebem 5% de bonifica��o como funcion�rios comuns
        super.aplicarBonificacao();
    }

    public void mostrarDadosSecretario() {
        super.mostrarDados();
        System.out.println("Categoria: " + categoria);
        System.out.println("Setor: " + setor);
    }
}