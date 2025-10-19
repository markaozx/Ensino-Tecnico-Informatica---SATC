import java.util.Scanner;

public class Produto {
	public static void main(String[] args) {
		double quantidade, preco, total;
		String nome, produto;
		
		Scanner entrada = new Scanner(System.in);
		
		System.out.println("Nome: ");
		nome = entrada.nextLine();
		
		System.out.println("Produto: ");
		produto = entrada.nextLine();
		
		System.out.println("quantidade: ");
		quantidade = entrada.nextDouble();
		
		System.out.println("Preço: ");
		preco = entrada.nextDouble();
		
		total = preco * quantidade;
		
		System.out.println("Total: " + total);
		
	}
}