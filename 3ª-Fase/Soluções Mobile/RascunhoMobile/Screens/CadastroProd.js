import {View, Text, Image, StyleSheet, TextInput, Button, ImageBackground} from 'react-native';
import {useState} from 'react';
import { db } from '../controller';
import { collection, addDoc } from 'firebase/firestore';


export default function CadastroProd() {

    const [imagem, setImagem] = useState("");
    const [nome, setNome] = useState("");
    const [valor, setValor] = useState("");

    const CadastrarProduct = async () => {
        try {
            await addDoc(collection(db, 'produtos'), {
                nome,
                valor: parseFloat(valor),
                imagem
            });
            setNome();
            setValor();
            setImagem();
        }
        catch {
            console.log('erro ao cadastrar', error)
        }
    }
  return(
    <ImageBackground source={{uri: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS6hKeZf7ni18SykGsxdkyYJ1X4MdlCZwbf9Q&s'}} style= {{width: '100%', height: '100%'}}>
    <View style={styles.container}>
      <Text style={styles.titulo}>Cadastro de Produto</Text>
      <View style={styles.caixa}>
          <Text style={styles.txt}>URL da imagem: </Text>
          <TextInput
            style={styles.txtinput}
            placeholder='imagem'
            placeholderTextColor={'black'}
            value={imagem}
            onChangeText={setImagem}
          />
      </View>
      <View style={styles.caixa}>
          <Text style={styles.txt}>Nome: </Text>
          <TextInput
            style={styles.txtinput}
            placeholder='nome'
            placeholderTextColor={'black'}
            value={nome}
            onChangeText={setNome}
          />
      </View>
      <View style={styles.caixa}>
          <Text style={styles.txt}>Valor: </Text>
          <TextInput
            style={styles.txtinput}
            placeholder='valor'
            placeholderTextColor={'black'}
            value={valor}
            onChangeText={setValor}
          />
      </View>
      <View style={styles.botao}>
      <Button title="Cadastrar" color='black' onPress={CadastrarProduct}/>
      </View>
      <View style={styles.baixo}>
      <Text style={styles.credito}>Direitos autorais Liberty Walk ©
      2025</Text>
      </View>
    </View>
    </ImageBackground>
    )
}

const styles = StyleSheet.create({
    container: {
      flex: 1,
      width: '100%',
      padding: 25,
      paddingTop: 5,
      color: 'black',
    },

    logo1: { 
      width: 35,
      height: 35,
    },
    
    txt: {
      color:'black',
      paddingTop: 10,
      fontSize: 20,
      alignSelf: 'flex-start',
      width: 270,
    },
    
    titulo: {
      color:'black',
      fontWeight: 'bold',
      fontFamily: 'Trattatello',
      fontSize: 30,
    },

    credito: { 
      paddingTop: 576,
      color: 'white',
      fontSize: 15,
      alignSelf: 'center',
    },

    baixo: {
        justifyContent: 'flex-end',
    },

    caixa: {
        paddingTop: 7,
        width: '100%',
        // Removed fixed height to allow flexible input height
    },

    txtinput: {
      // Removed padding and height to allow natural sizing
      borderColor: 'black',
      borderWidth: 1,
      borderRadius: 3,
      backgroundColor: 'white', // Added background color for visibility
      color: 'black',
      height: 30,
      fontSize: 20,
    },

    botao: {
        paddingTop: 50,
    },
    botao2: {
      paddingTop: 10,
    }
});
