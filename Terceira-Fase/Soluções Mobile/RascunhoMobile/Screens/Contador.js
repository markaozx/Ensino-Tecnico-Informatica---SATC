import {View, Text, StyleSheet, Button, TouchableOpacity} from 'react-native';
import {useState} from 'react';
import {ImageBackground } from 'react-native-web';


export default function Count() {
    const [count, setCount] = useState(0);

    function Aumentar() {
        setCount(count + 1);
    }
    function Diminuir() {
        setCount(count - 1);
    }

    return (
        <ImageBackground style={styles.fundo} source={{uri: 'https://i.pinimg.com/736x/a2/48/16/a248160d7683465d05452723bad942a7.jpg'}}>
        <View style={styles.container}>
            <Text style={styles.countText}>Contador: {count}</Text>
            <View style={styles.buttonContainer}>
                <TouchableOpacity style={styles.button} onPress={Aumentar}>
                    <Text style={styles.buttonText}>Aumentar</Text>
                </TouchableOpacity>
                <TouchableOpacity style={styles.button} onPress={Diminuir}>
                    <Text style={styles.buttonText}>Diminuir</Text>
                </TouchableOpacity>
            </View>
        </View>
        </ImageBackground>
    );
}

const styles = StyleSheet.create({
    fundo: {
        flex: 1,
        justifyContent: 'center',
    },
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
    countText: {
        fontSize: 32,
        marginBottom: 20,
        fontWeight: 'bold',
    },
    buttonContainer: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        width: '60%',
    },
    button: {
        backgroundColor: '#007AFF',
        paddingVertical: 10,
        paddingHorizontal: 20,
        borderRadius: 8,
    },
    buttonText: {
        color: '#fff',
        fontSize: 18,
    },
});
