package aula12.veiculo;

class caminhao extends veiculo {

    private int eixos;

    public caminhao(String codigo, String descricao, String marca, String modelo, int eixos) {
        super(codigo, descricao, marca, modelo);
        this.eixos = eixos;
    }

    public void dadosCaminhao() {
        super.dados();
        System.out.println("Eixos: " + eixos);
    }

}
