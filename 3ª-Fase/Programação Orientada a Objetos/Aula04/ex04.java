import javax.swing.JOptionPane;

public class ex04 {

public static void main(String[] args) {
String nome, produto;

nome        = JOptionPane.showInputDialog("Nome Cliente: ");
produto     = JOptionPane.showInputDialog("Produto: ");
int quantidade    = Integer.parseInt(JOptionPane.showInputDialog("Quantidade : "));
double preco      = Double.parseDouble(JOptionPane.showInputDialog("Preço: "));
double total = (quantidade * preco);
JOptionPane.showMessageDialog(null,"Total da Compra R$:   " + total);
System.exit(0);
}
}