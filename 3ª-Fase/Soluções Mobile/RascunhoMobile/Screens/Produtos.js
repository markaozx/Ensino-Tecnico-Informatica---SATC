import {View, Text, StyleSheet, Image, FlatList} from 'react-native';
import {ImageBackground } from 'react-native-web';
import { useState, useEffect } from 'react';
import Card from './Card';
import { db } from '../controller';
import { collection, doc, getDocs } from "firebase/firestore"; 
import { useCarrinho } from '../CarrinhoProvider';
import Carrinho from './Carrinho';

export default function Produtos({navigation}){

    const [produtos, setProdutos] = useState([]);
    const {adicionarProduto} = useCarrinho()

    useEffect(() => {
        async function carregarProdutos() {
            try {
                const querySnapshot = await getDocs(collection(db, 'produtos'));
                const lista = []
                querySnapshot.forEach((doc) => {
                    lista.push({id:doc.id, ...doc.data() });
                });
                setProdutos(lista)
            } catch(error){
                console.log('erro no podruto')
            }
        }
        carregarProdutos();
    }, []);

    return ( 
        <ImageBackground style={styles.fundo} source={{uri: 'https://i.pinimg.com/564x/c4/8a/da/c48ada5ee56d733592ca14c1483efd16.jpg'}}>
        <View style={styles.container}>
            <Text style={styles.title}>Body Kits</Text>
            <FlatList
                data={produtos}
                renderItem={({item}) => (
                    <Card
                    imagem = {item.imagem}
                    nome = {item.nome}
                    valor = {item.valor}
                    comprar={()=> {
                        adicionarProduto(item)
                        navigation.navigate(Carrinho)
                    }}
                    />
            )}
            keyExtractor={item => item.id}
            contentContainerStyle={{paddingBottom: 20}}
            />
        </View>
        </ImageBackground>
    )
}

const styles =  StyleSheet.create({
    container:{
        flex:1,
        padding: 20,
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        marginBottom: 20,
        textAlign: 'center',
        color: '#333',
    },
    fundo: {
        flex: 1,
      justifyContent: 'center',
      }
})
