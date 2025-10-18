public class Temperatura
{
    public static void main(String[] args) {
        //Criar as variaveis
        double celsius, F, K, Re, Ra;
        
        //valor incial da Temperatura
        celsius = 30;
        
        F = celsius * 1.8 + 32;
        K = celsius + 273.15;
        Ra = celsius * 1.8 + 32 + 459.67;
        Re = celsius * 0.8;
        
        System.out.println("Fahrenheint: " + F);
        System.out.println("Kelvin: " + K);
        System.out.println("Reaumur: " + Ra);
        System.out.println("Rankine: " + Re);
    }
}