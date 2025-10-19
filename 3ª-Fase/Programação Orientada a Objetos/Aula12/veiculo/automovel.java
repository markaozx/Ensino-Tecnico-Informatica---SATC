package aula12.veiculo;

class automovel extends veiculo {

    private int potencia;

    public automovel(String codigo, String descricao, String marca, String modelo, int potencia) {
        super(codigo, descricao, marca, modelo);
        this.potencia = potencia;
        
    }

    public void dadosAutomovel() {
        super.dados();
        System.out.println("Potencia: " + potencia);
    }
}
