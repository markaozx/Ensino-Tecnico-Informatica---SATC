package arrays;

public class ex02 {
    public static void main(String[] args) {
        String[] animais = {"Cão", "Gato", "Elefante", "Tigre", "Urso", "Leão"};

        System.out.println("Animais com até 5 letras:");
        for (int i = 0; i < animais.length; i++) {
            if (animais[i].length() <= 5) {
                System.out.println(animais[i]);
            }
        }
    }

}
