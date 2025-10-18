//Exercicio5 - Construir um programa em JAVA que contenha um metodo, que
//leia nome e estado civil de uma pessoa e verificar a classificação:
//Sigla	Estado Civil
//S			Solteiro
//C			Casado
//V			Viúvo
//D			Divorciado

import javax.swing.JOptionPane;
public class Exercicio5 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String nome, sigla;
        nome  = JOptionPane.showInputDialog("Nome: ");
        sigla = JOptionPane.showInputDialog("S - Solteiro \n" +
                "C - Casado \t\n" +
                "V - Viúvo \n" +
                "D - Divorciado");
        if (sigla.equalsIgnoreCase("s")){
            JOptionPane.showMessageDialog(null,"Solteiro");
        } else if (sigla.equalsIgnoreCase("c")) {
            JOptionPane.showMessageDialog(null, "Casado");
        } else if (sigla.equalsIgnoreCase("v")) {
            JOptionPane.showMessageDialog(null, "Viuvo");
        } else if (sigla.equalsIgnoreCase("D")) {
            JOptionPane.showMessageDialog(null,"Divorciado");
        }
    }
}
