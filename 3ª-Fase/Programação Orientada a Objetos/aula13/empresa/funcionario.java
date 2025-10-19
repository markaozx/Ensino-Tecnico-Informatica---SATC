// funcionario pai

package aula13.empresa;

class funcionario {
    protected String codigo;
    protected String nome;
    protected String sexo;
    protected int horasTrabalhadas;
    protected double valorHora;
    protected double salario;

    public funcionario(String codigo, String nome, String sexo, int horasTrabalhadas, double valorHora) {
        this.codigo = codigo;
        this.nome = nome;
        this.sexo = sexo;
        this.horasTrabalhadas = horasTrabalhadas;
        this.valorHora = valorHora;
        calcularSalario();
    }

    // metodos para calcular salario e aplicar bonus
    public void calcularSalario() {
        this.salario = this.horasTrabalhadas * this.valorHora;
    }

    public void aplicarBonificacao() {
        double bonificacao = 0.05 * salario;  
        this.salario += bonificacao;
    }

    public void mostrarDados() {
        System.out.println("C�digo: " + codigo);
        System.out.println("Nome: " + nome);
        System.out.println("Sexo: " + sexo);
        System.out.println("Horas trabalhadas: " + horasTrabalhadas);
        System.out.println("Valor hora: R$ " + valorHora);
        System.out.println("Sal�rio: R$ " + salario);
    }
}