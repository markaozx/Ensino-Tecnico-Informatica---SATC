import javax.swing.JOptionPane;

public class ex03 {

public static void main(String[] args) {
double celsius = Double.parseDouble(JOptionPane.showInputDialog("Temperatura Celsius: "));
double fahrenheit = ((9/5)*celsius) + 32;
JOptionPane.showMessageDialog(null,"Temperatura Fahrenheit:   "+fahrenheit);
System.exit(0);

}

}
