//Exercicio11 - Construir um programa em JAVA que contenha dois métodos. O primeiro metodo
//que cadastre e mostre os dados dos professores de uma escola: nome e formação. O segundo
//metodo que cadastre e mostre os dados das disciplinas: nome e quantidade de aulas.
import javax.swing.JOptionPane;
import java.util.Scanner;

public class Exercicio11 {

    public static void main(String[] args) {
        String nomeProf, formProf, nomeDisciplina;
        int QntAulas;
        nomeProf = JOptionPane.showInputDialog("Nome: ");
        formProf = JOptionPane.showInputDialog("Formação: ");

        ValidarProf(nomeProf, formProf);

        nomeDisciplina = JOptionPane.showInputDialog("Disciplina: ");
        QntAulas = Integer.parseInt(JOptionPane.showInputDialog("Quantidade de aulas:"));

        ValidarDisciplina(nomeDisciplina, QntAulas);
        System.exit(0);
    }

    public static void ValidarProf(String nome, String form) {
        JOptionPane.showMessageDialog(null, "Nome: " + nome);
        JOptionPane.showMessageDialog(null, "Formação: " + form);
    }

    public static void ValidarDisciplina(String disciplina, int qntidadeAulas) {
        JOptionPane.showMessageDialog(null, "Disciplina: " + disciplina);
        JOptionPane.showMessageDialog(null, "Quantidade de aulas: " + qntidadeAulas);
    }

}
