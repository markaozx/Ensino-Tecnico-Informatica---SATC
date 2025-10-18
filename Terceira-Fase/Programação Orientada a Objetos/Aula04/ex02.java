import javax.swing.JOptionPane;
public class ex02 {

public static void main(String[] args) {
String nome,curso,disciplina;

nome        = JOptionPane.showInputDialog("Nome: ");
curso       = JOptionPane.showInputDialog("Curso: ");
disciplina  = JOptionPane.showInputDialog("Disciplina:");
double nota1 = Double.parseDouble(JOptionPane.showInputDialog("Nota1:  "));
double nota2 = Double.parseDouble(JOptionPane.showInputDialog("Nota2:  "));
double nota3 = Double.parseDouble(JOptionPane.showInputDialog("Nota3:  "));
double media = (nota1 + nota2 + nota3) /3;
JOptionPane.showMessageDialog(null,"Média Final:   "+media);
System.exit(0);

}
}
