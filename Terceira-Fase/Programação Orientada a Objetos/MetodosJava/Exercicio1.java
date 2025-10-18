// Exercicio1 - Construir um programa em JAVA que contenha um metodo, que leia
// nome e idade de uma pessoa e verifique se é Maior ou Menor de Idade:

import javax.swing.JOptionPane;
public class Exercicio1 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String nome;
        int idade;

        nome  = JOptionPane.showInputDialog("Nome: ");
        idade = Integer.parseInt(JOptionPane.showInputDialog("Idade:"));

        if (idade >= 18)
        {
            JOptionPane.showMessageDialog(null, "Nome :"+nome+" Maior de Idade");   }
        else
        {
            JOptionPane.showMessageDialog(null, "Nome :"+nome+" Menor de Idade");   }
    }

}
