//Exercicio12 - Construir um programa em JAVA que contenha dois métodos. O primeiro metodo que
//cadastre e mostre os dados dos setores de uma empresa: nome do setor, gerente  e telefone. O
// segundo metodo que cadastre e mostre os dados dos funcionários: nome, cargo e salário.
import javax.swing.JOptionPane;

public class Exercicio12 {

    public static void main(String[] args) {
        String nomeProf, gerente, nomeFunci, cargo;
        int telefone;
        double salario;
        nomeProf = JOptionPane.showInputDialog("Nome do setor: ");
        gerente = JOptionPane.showInputDialog("Gerente: ");
        telefone = Integer.parseInt(JOptionPane.showInputDialog("Telefone: "));

        Setor(nomeProf, gerente, telefone);

        nomeFunci = JOptionPane.showInputDialog("Nome do funcionario: ");
        cargo = JOptionPane.showInputDialog("Cargo do funcionario: ");
        salario = Double.parseDouble(JOptionPane.showInputDialog("Salario do funcionario:"));

        funcionarios(nomeFunci, cargo, salario);
        System.exit(0);
    }

    public static void Setor(String nome, String gerente, int telefone) {
        JOptionPane.showMessageDialog(null, "Nome setor: " + nome);
        JOptionPane.showMessageDialog(null, "Gerente: " + gerente);
        JOptionPane.showMessageDialog(null, "Telefone: " + telefone);
    }

    public static void funcionarios(String nome, String cargo, double salario) {
        JOptionPane.showMessageDialog(null, "Nome funcionario: " + nome);
        JOptionPane.showMessageDialog(null, "Cargo: " + cargo);
        JOptionPane.showMessageDialog(null, "Salario: " + salario);
    }

}
