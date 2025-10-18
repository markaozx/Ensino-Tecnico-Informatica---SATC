package aula12.veiculo;

public class Oficina {
	
     
    // M�todos para ser chamado nas classes filhas
    
    public void fazerRevisao() {
        System.out.println("Verificando as condi��es gerais do ve�culo...");
        //entrada dados revisao (data, hora...
    }

    public void fazerManutencao() {
        System.out.println("Realizando ajustes e manuten��o no ve�culo...");
      //entrada dados manutencao (defeito, valor...
    }

    public void fazerLimpeza() {
        System.out.println("Realizando limpeza do ve�culo...");
      //entrada dados limpeza (tipo, valor...
    }
    
    public void fazerAbastecimento() {
        System.out.println("Realizando abastecimento do ve�culo...");
      //entrada dados abastecimento (tipo combustivel, qtde, valor...
    }
    
}


