// cadastrar
package aula13.empresa;
import java.util.Scanner;
import java.util.ArrayList;

public class cadastrarFuncionarios {
	public static void main (String[] args) {
		Scanner scanner = new Scanner(System.in);
		ArrayList<funcionario> funcionarios = new ArrayList<>();
        
        while (true) {
            System.out.println("Escolha uma opcao:");
            System.out.println("1 - Cadastrar Engenheiro");
            System.out.println("2 - Cadastrar Diretor");
            System.out.println("3 - Cadastrar Secretario");
            System.out.println("4 - Cadastrar Gerente");
            System.out.println("5 - Calcular Salarios e Aumentos");
            System.out.println("6 - Sair");
            
            int opcao = scanner.nextInt();
            scanner.nextLine(); 

            if (opcao == 6) {
            	System.out.print("Cadastros finalizado.... ");
            	break;
            }

            System.out.print("Codigo: ");
            String codigo = scanner.nextLine();
            System.out.print("Nome: ");
            String nome = scanner.nextLine();
            System.out.print("Sexo: ");
            String sexo = scanner.nextLine();
            System.out.print("Horas trabalhadas: ");
            int horasTrabalhadas = scanner.nextInt();
            System.out.print("Valor hora: ");
            double valorHora = scanner.nextDouble();
            scanner.nextLine(); // Consome a nova linha do buffer

            switch (opcao) {
                case 1:
                    System.out.print("Numero CREA: ");
                    String nrCREA = scanner.nextLine();
                    System.out.print("Especializacao: ");
                    String especializacao = scanner.nextLine();
                    engenheiro engenheiro = new engenheiro(codigo, nome, sexo, horasTrabalhadas, valorHora, nrCREA, especializacao);
                    funcionarios.add(engenheiro);
                    engenheiro.mostrarDadosEngenheiro();
                    break;

                case 2:
                    System.out.print("Area de Gestao: ");
                    String areaGestao = scanner.nextLine();
                    diretor diretor = new diretor(codigo, nome, sexo, horasTrabalhadas, valorHora, areaGestao);
                    funcionarios.add(diretor);
                    diretor.mostrarDadosDiretor();
                    break;

                case 3:
                    System.out.print("Categoria: ");
                    String categoria = scanner.nextLine();
                    System.out.print("Setor: ");
                    String setor = scanner.nextLine();
                    secretario secretario = new secretario(codigo, nome, sexo, horasTrabalhadas, valorHora, categoria, setor);
                    funcionarios.add(secretario);
                    secretario.mostrarDadosSecretario();
                    break;

                case 4:
                    System.out.print("Setor: ");
                    String setorGerente = scanner.nextLine();
                    gerente gerente = new gerente(codigo, nome, sexo, horasTrabalhadas, valorHora, setorGerente);
                    funcionarios.add(gerente);
                    gerente.mostrarDadosGerente();
                    break;

                case 5:
                    System.out.println("Calculando salários e aumentos...");
                    System.out.println("----------");
                    for (funcionario f : funcionarios) {
                        f.calcularSalario();
                        if (f instanceof gerente) {
                            ((gerente) f).aplicarBonificacaoGerente();
                            ((gerente) f).mostrarDadosGerente();
                        } else if (f instanceof diretor) {
                            ((diretor) f).aplicarBonificacaoDiretor();
                            ((diretor) f).mostrarDadosDiretor();
                        } else if (f instanceof secretario) {
                            ((secretario) f).aplicarBonificacaoSecretario();
                            ((secretario) f).mostrarDadosSecretario();
                        } else if (f instanceof engenheiro) {
                            ((engenheiro) f).aplicarBonificacaoEngenheiro();
                            ((engenheiro) f).mostrarDadosEngenheiro();
                        } else {
                            // fallback for any other funcionario type
                            f.aplicarBonificacao();
                            f.mostrarDados();
                        }
                        System.out.println("----------");
                    }
                    break;

                default:
                    System.out.println("Opcao invalida.");
            }
        }
        scanner.close();
    }
}