package arrays;

public class ex03 {
    public static void main(String[] args) {
        String[] frutas = {"Maçã", "Uva", "Banana", "Pera", "Kiwi"};
        boolean encontrada = false;

        for (int i = 0; i < frutas.length; i++) {
            if (frutas[i].equals("Banana")) {
                encontrada = true;
                break;
            }
        }
        if (encontrada) {
            System.out.println("A fruta Banana está na lista!");
        } else {
            System.out.println("A fruta Banana NÃO está na lista.");
        }
    }

}
