import java.util.Scanner;

public class Media
{
    public static void main(String[] args){
        Scanner entrada = new Scanner(System.in);
        
        //Criar Variaveis
        String nome, curso, disciplina;
        double n1, n2, n3, n4, media;
        
        //Notas
        System.out.println("Digite seu nome: ");
        nome = entrada.nextLine();
        
        System.out.println("Digite seu curso: ");
        curso = entrada.nextLine();
        
        System.out.println("Digite seu disciplina: ");
        disciplina = entrada.nextLine();
        
        System.out.println("nota 1: ");
        n1 = entrada.nextDouble();
        
        System.out.println("nota 2: ");
        n2 = entrada.nextDouble();
        
        System.out.println("nota 3: ");
        n3 = entrada.nextDouble();
        
        System.out.println("nota 4: ");
        n4 = entrada.nextDouble();
        
        //Calcular
        media = (n1 + n2 + n3 + n4)/4;
        
        //Exibir
        System.out.println("Media: " + media);
    }
}
