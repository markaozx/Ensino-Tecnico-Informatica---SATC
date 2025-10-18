import javax.swing.JOptionPane;

public class ex06 {
	public static void main(String[] args) {
		int i = 1;
		while (i==1) {
			double x = Double.parseDouble(JOptionPane.showInputDialog("Valor da base: "));
			double y = Double.parseDouble(JOptionPane.showInputDialog("Valor da altura: "));
			double area = (x * y) / 2;
			JOptionPane.showMessageDialog(null, "Area: " + area);
	        String deci = JOptionPane.showInputDialog("Quer continuar?");
	        if (deci.equals("n") || (deci.equals("não"))){
	        	i = 2;
	    	    System.exit(0);
	        }
			
		}
	}

}
