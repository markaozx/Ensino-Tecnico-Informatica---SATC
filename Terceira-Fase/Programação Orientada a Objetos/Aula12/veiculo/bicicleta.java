package aula12.veiculo;

class bicicleta extends veiculo {

    private int marchas;

    public bicicleta(String codigo, String descricao, String marca, String modelo, int marchas) {
        super(codigo, descricao, marca, modelo);
        this.marchas = marchas;
    }

    public void dadosBicicleta() {
        super.dados();
        System.out.println("marchas: " + marchas);
    }

}
