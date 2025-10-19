import {View, Text, FlatList, Image, StyleSheet } from 'react-native';
import { useCarrinho } from '../CarrinhoProvider';

export default function Carrinho(){
    const {carrinho} = useCarrinho();
    return (
        <View style={styles.container}>
            <Text style={styles.title}>Carrinho</Text>
            <FlatList
            data={carrinho}
            keyExtractor={(item, index) => index.toString()}
            renderItem={({item}) => (
                <View style={styles.itemContainer}>
                    <Image source={{uri: item.imagem}} style={styles.image}/>
                    <View style={styles.textContainer}>
                        <Text style={styles.name}>{item.nome}</Text>
                        <Text style={styles.value}>R$ {item.valor}</Text>
                    </View>
                </View>
            )}
            />
        </View>
    )
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 16,
        backgroundColor: '#f5f5f5',
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        marginBottom: 16,
        color: '#333',
        textAlign: 'center',
    },
    itemContainer: {
        flexDirection: 'row',
        backgroundColor: '#fff',
        borderRadius: 8,
        padding: 12,
        marginBottom: 12,
        alignItems: 'center',
        shadowColor: '#000',
        shadowOpacity: 0.1,
        shadowRadius: 4,
        shadowOffset: { width: 0, height: 2 },
        elevation: 3,
    },
    image: {
        width: 60,
        height: 60,
        borderRadius: 8,
        marginRight: 12,
    },
    textContainer: {
        flex: 1,
        justifyContent: 'center',
    },
    name: {
        fontSize: 18,
        fontWeight: '600',
        color: '#222',
    },
    value: {
        fontSize: 16,
        color: '#888',
        marginTop: 4,
    },
});
