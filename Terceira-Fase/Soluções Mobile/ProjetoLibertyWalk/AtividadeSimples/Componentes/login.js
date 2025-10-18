import {View, Text, Image, StyleSheet, TextInput} from 'react-native';
import { Button, ImageBackground } from 'react-native-web';

export default function Login() {
  return(
    <ImageBackground source={{uri: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS6hKeZf7ni18SykGsxdkyYJ1X4MdlCZwbf9Q&s'}} style= {{width: '100%', height: '100%'}}>
    <View style={styles.container}>
      <Text style={styles.titulo}>Login de Usuario</Text>
      <View style={styles.caixa}>
      <Text style={styles.txt}>Nome:</Text>
      <TextInput
      style={styles.txtinput}
      placeholder=' '
      placeholderTextColor={'black'}
      />
      </View>
      <View style={styles.caixa}>
      <Text style={styles.txt}>Senha: </Text>
      <TextInput
      style={styles.txtinput}
      placeholder=' '
      placeholderTextColor={'black'}
      />
      </View>
      <View style={styles.botao}>
      <Button title="Enviar" color='black' />
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
      paddingTop: 50,
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
      paddingTop: 20,
      color: 'white',
      fontSize: 15,
      alignSelf: 'center',
    },

    baixo: {
        justifyContent: 'flex-end',
    },

    txtinput: {
      padding: 5,
      height: 30,
      borderColor: 'black',
      borderWidth: 1,
      borderRadius: 3,
    },

    caixa: {
        paddingTop:20,
        width: '100%',
        height: 50,
    },
    
    botao: {
        paddingTop: 50,
    }
});
