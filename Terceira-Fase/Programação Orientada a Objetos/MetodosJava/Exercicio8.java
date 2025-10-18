//Construir um programa em JAVA que contenha um metodo, que leia os dados de 300 alunos.
//A partir dos dados de entrada  nome e formação dos alunos verificar o total de alunos
//em cada formação:
//Ensino Fundamental
//Ensino Médio
//Ensino Técnico
import javax.swing.JOptionPane;
public class Exercicio8 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String nome;
        double ensino;
        int i = 1;
        int limite = 0;
        int cont1 = 0, cont2 = 0, cont3 = 0;
        while (i==1) {
            limite = limite + 1;
            nome = JOptionPane.showInputDialog("Nome: ");
            ensino = Double.parseDouble(JOptionPane.showInputDialog("1 - Ensino Fundamental\n2 - Ensino Medio\nEnsino Tecnico"));
            if (ensino == 1) {
                cont1 = cont1 + 1;
            } else if (ensino == 2) {
                cont2 = cont2 + 1;
            } else if (ensino == 3) {
                cont3 = cont3 + 1;
            }
            if (limite == 300) {
                i = 2;
            }
        }
        JOptionPane.showMessageDialog(null, "Total de aluno no ensino fundamental: " + cont1
                + "\nTotal de alunos no ensino medio: " + cont2
                + "\nTotal de alunos no ensino tecnico: " + cont3);
    }
}
