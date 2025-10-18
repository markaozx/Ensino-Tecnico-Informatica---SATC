package aula12.veiculo;

class veiculo {
    protected String codigo;
    protected String descricao;
    protected String marca;
    protected String modelo;

    public veiculo(String codigo, String descricao, String marca, String modelo) {
        this.codigo = codigo;
        this.descricao = descricao;
        this.marca = marca;
        this.modelo = modelo;
    }

    public String getCodigo() {
        return codigo;
    }
    public String getDescricao() {
        return descricao;
    }
    public String getMarca() {
        return marca;
    }
    public String getModelo() {
        return modelo;
    }

    public void dados() {
        System.out.println("C�digo: " + codigo);
        System.out.println("Descri��o: " + descricao);
        System.out.println("Marca: " + marca);
        System.out.println("Modelo: " + modelo);
    }
    
}