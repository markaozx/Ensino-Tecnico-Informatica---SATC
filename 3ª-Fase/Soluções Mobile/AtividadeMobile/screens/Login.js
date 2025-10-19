
import { StyleSheet, Text, View, TextInput, Image, ImageBackground, Button} from 'react-native';
export default function Login({navigator}) {
  return (
    <ImageBackground style={styles.fundo} source={{uri: 'https://i.redd.it/jrcc8f36q9l51.jpg'}}>
    <View style={styles.container}>
      <Image style={styles.img} source={{uri: 'https://starplast.com.br/wp-content/uploads/2022/09/logo-kings-sneakers.png'}}/>
      <Text style={styles.titulo}>Login de usuario:</Text>
      <View style={styles.campos}>
        <Text style={styles.text}>Nome:</Text>
        <TextInput style={styles.input}></TextInput>
        <Text style={styles.text}>Senha:</Text>
        <TextInput style={styles.input}></TextInput>
      </View>
      <Button onPress={() => navigation.navigate("Estoque.js")} title="Logar" color="red"/>
    </View>
    </ImageBackground>
  );
}

const styles = StyleSheet.create({
    container: {
      padding: 10,
      paddingTop: 25,
      flex: 1,
    },

    input: {
      borderWidth: 1,
      borderColor: 'white',
      height: 30,
    },
    campos: {
      width: '100%',
      paddingTop: 10,
      paddingBottom: 10,
      justifyContent: 'center'
    },
    text: {
      fontStyle: 'italic',
      fontSize: 25,
      color: 'white',
    },
    img: {
      width: 100,
      height: 100,
      justifyContent: 'flex-start',
    },
    titulo: {
      fontFamily: 'arialbold',
      color: 'red',
      paddingTop: 200,
      fontSize: 35,
    },
    fundo: {
      flex: 1,
    justifyContent: 'center',
    }
  });