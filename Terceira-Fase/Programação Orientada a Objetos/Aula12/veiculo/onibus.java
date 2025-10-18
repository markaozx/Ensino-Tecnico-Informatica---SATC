package aula12.veiculo;

class onibus extends veiculo {
    private int passageiros;

    public onibus(String codigo, String descricao, String marca, String modelo, int passageiros) {
        super(codigo, descricao, marca, modelo);
        this.passageiros = passageiros;
    }

    public void dadosOnibus() {
        super.dados();
        System.out.println("passageiros: " + passageiros);
    }
}
