import javax.swing.JOptionPane;

public class ex07 {
	public static void main(String[] args) {
		int i = 1;
		while (i==1) {
			double x = Double.parseDouble(JOptionPane.showInputDialog("Peso: "));
			double y = Double.parseDouble(JOptionPane.showInputDialog("Altura: "));
			double imc = x / (y * y);
			JOptionPane.showMessageDialog(null, "IMC: " + imc);
	        String deci = JOptionPane.showInputDialog("Quer continuar?");
	        if (deci.equals("n") || (deci.equals("não"))){
	        	i = 2;
	    	    System.exit(0);
	        }
			
		}
	}

}
