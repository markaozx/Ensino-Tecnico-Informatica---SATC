//Exercicio3 - Construir um programa em JAVA que contenha um metodo, que leia
//Construir um programa em JAVA que contenha um metodo, que leia nome e média
//final de um aluno e verifique sua situação:
//Média < 5            – Situação Reprovado
//Média entre 5 e 7    – Situação Recuperação
//Média >= 7           – Situação Aprovado

import javax.swing.JOptionPane;
public class Exercicio4 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String nome;
        double media;
        nome  = JOptionPane.showInputDialog("Nome: ");
        media = Double.parseDouble(JOptionPane.showInputDialog("Media: "));
        if (media < 5) {
            JOptionPane.showMessageDialog(null, "Reprovado");
        } else if (media >= 5 && media < 7) {
            JOptionPane.showMessageDialog(null, "Recuperação");
        } else if (media >= 7) {
            JOptionPane.showMessageDialog(null,"Aprovado");
        } else {
            JOptionPane.showMessageDialog(null, "Valor Invalido");
        }
    }
}
