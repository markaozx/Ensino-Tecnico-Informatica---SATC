//Exercicio9 - Construir um programa em JAVA que contenha um metodo, que leia os dados
//de 100 alunos de uma Universidade: nome, idade e sexo. Após verifique a quantidade
//de alunos por:
//Total de alunos maior e menor de idade (18 anos)
//Total de alunos por sexo (masculino e feminino)
import javax.swing.JOptionPane;
public class Exercicio9 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String nome, sexo;
        double idade;
        int i = 1;
        int limite = 0;
        int MaiorIdade = 0, MenorIdade = 0, SexoM = 0, SexoF = 0;
        while (i==1) {
            limite = limite + 1;
            nome = JOptionPane.showInputDialog("Nome: ");
            sexo = JOptionPane.showInputDialog("M - Masculino\nF - Feminino\nSexo: ");
            idade = Double.parseDouble(JOptionPane.showInputDialog("Idade: "));

            if (idade >= 18) {
                MaiorIdade = MaiorIdade + 1;
            } else if (idade < 18) {
                MenorIdade = MenorIdade + 1;
            }

            if (sexo.equalsIgnoreCase("m")) {
                SexoM = SexoM + 1;
            } else if (sexo.equalsIgnoreCase("f")) {
                SexoF = SexoF + 1;
            }

            if (limite == 100) {
                i = 2;
            }
        }
        JOptionPane.showMessageDialog(null, "Menor de idade: " + MenorIdade +
                "\nMaior de idade: " + MaiorIdade +
                "\nSexo Masculino: " + SexoM +
                "\nSexo Feminino: " + SexoF);
    }
}
