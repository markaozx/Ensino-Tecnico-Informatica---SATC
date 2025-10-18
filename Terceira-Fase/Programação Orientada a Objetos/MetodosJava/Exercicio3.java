//Exercicio3 - Construir um programa em JAVA que contenha um metodo, que leia
//nome, peso e altura de uma pessoa. Após calcule e mostre o seu IMC, de acordo
// com a fórmula:   IMC = peso / (altura * altura)

import javax.swing.JOptionPane;
public class Exercicio3 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String nome;
        double peso,altura;
        nome  = JOptionPane.showInputDialog("Nome: ");
        peso = Double.parseDouble(JOptionPane.showInputDialog("Peso: "));
        altura = Double.parseDouble(JOptionPane.showInputDialog("Altura: "));
        double imc = peso / (altura * altura);
        JOptionPane.showMessageDialog(null, nome + ", seu IMC é: " + imc);
    }
}
