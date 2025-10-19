import java.util.Scanner;

public class Salario {
	public static void main(String[] args) {
		double salario, total;
		String nome, cargo;
		
		Scanner entrada = new Scanner(System.in);
		
		System.out.println("Nome: ");
		nome = entrada.nextLine();
		
		System.out.println("cargo: ");
		cargo = entrada.nextLine();
		
		System.out.println("salario: ");
		salario = entrada.nextDouble();
		
		total = salario + (salario * 0.05);
		
		System.out.println("Total: " + total);
		
	}
}