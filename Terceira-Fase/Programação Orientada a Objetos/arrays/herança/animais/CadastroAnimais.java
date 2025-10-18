package arrays.herança.animais;
import java.util.Scanner;

public class CadastroAnimais {

    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        AnimalDomestico[] animal = new AnimalDomestico[3];    

        for (int i = 0; i < animal.length; i++) {
            System.out.println("\nCadastro do animal:  " + (i + 1));

            System.out.println("Escolha o tipo de animal (1 - Cão, 2 - Gato, 3 - Peixe): ");
            int opcao = scanner.nextInt();
            scanner.nextLine(); 

            System.out.print("Digite o nome: ");
            String nome = scanner.nextLine();

            System.out.print("Digite a idade: ");
            int idade = scanner.nextInt();
            scanner.nextLine(); 
            if (opcao == 1) {
                Cao cao = new Cao();
                cao.nome = nome;
                cao.idade = idade;
                System.out.print("Digite a raça do cachorro: ");
                cao.raca = scanner.nextLine();
                animal[i] = cao;                } 
            else if (opcao == 2) {
                Gato gato = new Gato();
                gato.nome = nome;
                gato.idade = idade;
                System.out.print("Digite a raça do gato: ");
                gato.raca = scanner.nextLine();
                animal[i] = gato;              } 
            else if (opcao == 3) {
                Peixe peixe = new Peixe();
                peixe.nome = nome;
                peixe.idade = idade;
                System.out.print("Digite o tipo de peixe: ");
                peixe.tipo = scanner.nextLine();
                animal[i] = peixe;         }
                else {
                    System.out.println("Opção inválida!");
                    AnimalDomestico animalGenérico = new AnimalDomestico();
                    animalGenérico.nome = nome;
                    animalGenérico.idade = idade;
                    animal[i] = animalGenérico;
                }
            }
    
    // Exibir todos os animais
            System.out.println("\n--- Lista de Animais Cadastrados ---");
            for (int i = 0; i < animal.length; i++) {
                System.out.println("\nAnimal #" + (i + 1));
                System.out.println("Nome: " + animal[i].nome);
                System.out.println("Idade: " + animal[i].idade);
                animal[i].alimentacao(); // Chama o método alimentacao() para cada animal
                animal[i].informacoesEspecificas(); // Exibe informações específicas
            }
    
            scanner.close();
        }
    }
    