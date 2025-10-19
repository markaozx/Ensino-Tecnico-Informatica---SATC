import javax.swing.JOptionPane;

public class ex08 {
	public static void main(String[] args) {
		double soma = 0;
		int i = 1;
		while (i==1) {
			
			String nome;
			nome = JOptionPane.showInputDialog("Nome funcinário: ");
			double horas = Double.parseDouble(JOptionPane.showInputDialog("Horas trabalhadas: "));
			double valor = Double.parseDouble(JOptionPane.showInputDialog("Valor da hora: "));
			double bruto = horas * valor;
			double inss = bruto - (bruto * 0.02);
			JOptionPane.showMessageDialog(null, "Salario final: " + inss);
			soma = soma + bruto;
	        String deci = JOptionPane.showInputDialog("Quer continuar?");
	        if (deci.equals("n") || (deci.equals("não"))){
	        	JOptionPane.showMessageDialog(null, "Folha de pagamento: " + soma);
	        	i = 2;
	    	    System.exit(0);
	        }
			
		}
	}

}
