//Exercicio6 - Construir um programa em JAVA que contenha um metodo, que leia
//dois valores e escolha a operação matemática desejada (adição, subtração,
//multiplicação e divisão). Ao final exibir o resultado.
//Exemplo:  valor1 =  2
//		    valor2 =  3
//          operação = adição
//          resultado = 5

import javax.swing.JOptionPane;
public class Exercicio6 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String sigla;
        double n1, n2;
        sigla = JOptionPane.showInputDialog("A - Adição \n" +
                "S - Subtração \t\n" +
                "M - Multiplicação \n" +
                "D - Divisão");
        n1 = Double.parseDouble(JOptionPane.showInputDialog("Valor 1: "));
        n2 = Double.parseDouble(JOptionPane.showInputDialog("Valor 2: "));
        if (sigla.equalsIgnoreCase("a")){
            double R = n1 + n2;
            JOptionPane.showMessageDialog(null,"Resultado: " + R);
        } else if (sigla.equalsIgnoreCase("s")) {
            double R = n1 - n2;
            JOptionPane.showMessageDialog(null,"Resultado: " + R);
        } else if (sigla.equalsIgnoreCase("m")) {
            double R = n1 * n2;
            JOptionPane.showMessageDialog(null,"Resultado: " + R);
        } else if (sigla.equalsIgnoreCase("d")) {
            double R = n1 / n2;
            JOptionPane.showMessageDialog(null,"Resultado: " + R);
        }
    }
}
