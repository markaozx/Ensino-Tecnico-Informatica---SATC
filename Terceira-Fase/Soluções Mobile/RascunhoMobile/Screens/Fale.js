import { StyleSheet, Text, View, TextInput, Image, ImageBackground, Button} from 'react-native';
export default function Fale(){
  return (
    <ImageBackground style={styles.fundo} source={{uri: 'https://i.pinimg.com/736x/2e/96/65/2e96651aa1d65a128847b36e06b27c01.jpg'}}>
    <View style={styles.container}>
      <Text style={styles.titulo}>Fale Conosco:</Text>
      <View style={styles.campos}>
        <Text style={styles.text}>Nome:</Text>
        <TextInput style={styles.input}></TextInput>
        <Text style={styles.text}>Email:</Text>
        <TextInput style={styles.input}></TextInput>
        <Text style={styles.text}>Mensagem:</Text>
        <TextInput style={styles.input}></TextInput>
      </View>
      <Button title="Enviar" color="red"/>
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
      fontSize: 20,
      color: 'white',
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
    titulo: {
      fontFamily: 'arialbold',
      color: 'red',
      paddingTop: 190,
      fontSize: 35,
    },
    fundo: {
      flex: 1,
    justifyContent: 'center',
    }
  });