//Construir um programa em JAVA que contenha um metodo, que leia os dados de 100
//funcionários de uma empresa: nome, cargo e salário. Após verifique o total
//dos salários por cargo:
//Analista de Sistemas
//Programador
//Técnico
//Exibir ao final a soma de todos os salários da empresa
import javax.swing.JOptionPane;
public class Exercicio10 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String nome, cargo;
        double salario, salarioA = 0, salarioP = 0, salarioT = 0, total = 0;
        int i = 1;
        int limite = 0;
        int Analista = 0, Programador = 0, Tecnico = 0;
        while (i==1) {
            limite = limite + 1;
            nome = JOptionPane.showInputDialog("Nome: ");
            cargo = JOptionPane.showInputDialog("A - Analista de Sistemas\n" +
                    "P - Programador\n" +
                    "T - Técnico");
            salario = Double.parseDouble(JOptionPane.showInputDialog("Idade: "));

            total = total + salario;

            if (cargo.equalsIgnoreCase("m")) {
                salarioA = salarioA + salario;
            } else if (cargo.equalsIgnoreCase("f")) {
                salarioP = salarioP + salario;
            } else if (cargo.equalsIgnoreCase("t")) {
                salarioT = salarioT + salario;
            }

            if (limite == 100) {
                i = 2;
            }
        }
        JOptionPane.showMessageDialog(null, "Salario Tecnico: " + salarioT +
                "\nSalario Programador: " + salarioP +
                "\nSalario Analista: " + salarioA +
                "\nSalario Total: " + total);
    }
}
