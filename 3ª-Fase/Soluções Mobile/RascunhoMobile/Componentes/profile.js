import {View, Text, Image, StyleSheet} from 'react-native';

export default function Profile() {
    return (
        <View style={styles.container}>
            <Text>Olá Mundo</Text>
            
        </View>
    );
}           


export function Artist() {
    return(
        <View style={styles.container3}>
            <Text>Esses são meus artistas</Text>

        </View>
    )
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f99',
        alignItems: 'center',
        justifyContent: 'center',
    },
    container2: {
        flex: 2,
        backgroundColor: '#f99',
        alignItems: 'center',
        justifyContent: 'center',
    },
    container3: {
        flex: 3,
        backgroundColor: '#fff',
        alignItems: 'center',
        justifyContent: 'center',
    },
});
