//Exercicio7 - Construir um programa em JAVA que contenha um metodo, que leia os dados
//de 50 funcionários. A partir dos dados de entrada: nome e salário,  verificar o
//total de funcionários:
//Total que recebem até R$ 3.000 - cont1
//Total que recebe entre R$ 3000 e R$ 5000 - cont2
//Total que recebem mais que R$ 5000 - cont3

import javax.swing.JOptionPane;
public class Exercicio7 {

    public static void main(String[] args) {
        verificar();
        System.exit(0);
    }

    public static void verificar() {
        String nome;
        double salario;
        int i = 1;
        int limite = 0;
        int cont1 = 0, cont2 = 0, cont3 = 0;
        while (i==1) {
            limite = limite + 1;
            nome = JOptionPane.showInputDialog("Nome: ");
            salario = Double.parseDouble(JOptionPane.showInputDialog("Salario: "));
            if (salario < 3000) {
                cont1 = cont1 + 1;
            } else if (salario >= 3000 && salario <= 5000) {
                cont2 = cont2 + 1;
            } else if (salario > 5000) {
                cont3 = cont3 + 1;
            }
            if (limite == 50) {
                i = 2;
            }
        }
        JOptionPane.showMessageDialog(null, "Total que recebem até R$ 3.000: " + cont1
                + "\nTotal que recebe entre R$ 3000 e R$ 5000: " + cont2
                + "\nTotal que recebem mais que R$ 5000: " + cont3);
    }
}
