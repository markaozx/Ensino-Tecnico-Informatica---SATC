import {View, Text, Image, StyleSheet, TextInput, Button, ImageBackground} from 'react-native';
import { createUserWithEmailAndPassword } from 'firebase/auth';
import { auth } from '../controller';
import {useState} from 'react';

export default function Cadastro({navigation}) {

    const [email, setEmail] = useState("");
    const [senha, setSenha] = useState("");

    const RegistroUsuario = () => {
        createUserWithEmailAndPassword(auth, email, senha)
            .then((userCredential) => {
                // Signed up 
                console.log('usuario cadastrado', userCredential.user.email);
                navigation.navigate('Login');
                
            })
            .catch((error) => {
                console.log('erro', error.message);
              
            });

    }
  return(
    <ImageBackground source={{uri: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS6hKeZf7ni18SykGsxdkyYJ1X4MdlCZwbf9Q&s'}} style= {{width: '100%', height: '100%'}}>
    <View style={styles.container}>
      <Text style={styles.titulo}>Cadastro de Usuario</Text>
      <View style={styles.caixa}>
          <Text style={styles.txt}>Email: </Text>
          <TextInput
            style={styles.txtinput}
            placeholder='email'
            placeholderTextColor={'black'}
            value={email}
            onChangeText={setEmail}
          />
      </View>
      <View style={styles.caixa}>
          <Text style={styles.txt}>Senha: </Text>
          <TextInput
            style={styles.txtinput}
            placeholder='senha'
            placeholderTextColor={'black'}
            value={senha}
            onChangeText={setSenha}
            secureTextEntry={true}
          />
      </View>
      <View style={styles.botao}>
      <Button title="Cadastrar" color='black' onPress={RegistroUsuario}/>
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
