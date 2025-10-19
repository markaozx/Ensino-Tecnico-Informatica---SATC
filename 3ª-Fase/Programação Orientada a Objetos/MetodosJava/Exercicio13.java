//Exercicio13 - Construir um programa em JAVA que contenha três métodos. O primeiro metodo
//que cadastre e mostre os dados dos cursos de uma escola: nome do curso  e período. O
//segundo metodo que cadastre e mostre os dados dos coordenadores: nome, e salário. O
//terceiro metodo que cadastre e mostre os dados dos alunos: nome, idade e sexo.
import javax.swing.JOptionPane;

public class Exercicio13 {

    public static void main(String[] args) {
        String nomeCurso, periodo, nomeCoord, nomeAluno, sexo;
        int idade;
        double salario;

        nomeCurso = JOptionPane.showInputDialog("Nome do curso: ");
        periodo = JOptionPane.showInputDialog("Periodo: ");

        curso(nomeCurso, periodo);

        nomeCoord = JOptionPane.showInputDialog("Nome do coordenador: ");
        salario = Double.parseDouble(JOptionPane.showInputDialog("Salario do coordenador:"));

        coordenador(nomeCoord, salario);

        nomeAluno = JOptionPane.showInputDialog("Nome do Aluno: ");
        idade = Integer.parseInt(JOptionPane.showInputDialog("Idade:"));
        sexo = JOptionPane.showInputDialog("Sexo do aluno: ");

        aluno(nomeAluno, idade, sexo);
        System.exit(0);
    }

    public static void curso(String nome, String periodo) {
        JOptionPane.showMessageDialog(null, "Nome setor: " + nome);
        JOptionPane.showMessageDialog(null, "Periodo: " + periodo);
    }

    public static void coordenador(String nome, double salario) {
        JOptionPane.showMessageDialog(null, "Nome funcionario: " + nome);
        JOptionPane.showMessageDialog(null, "Salario: " + salario);
    }

    public static void aluno(String nome, int idade, String sexo) {
        JOptionPane.showMessageDialog(null, "Nome aluno: " + nome);
        JOptionPane.showMessageDialog(null, "Idade: " + idade);
        JOptionPane.showMessageDialog(null, "Sexo: " + sexo);
    }

}
