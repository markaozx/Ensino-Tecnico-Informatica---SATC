import {View, Text, Image, StyleSheet, TextInput} from 'react-native';
import { Button } from 'react-native-web';

export default function Jerboa() {
  return(
    <View style={styles.container}>
      <Text style={styles.titulo}>Jerboa Pigmeu do Egito</Text>
      <View style={styles.viewimg}>
      <Image style={styles.img1}source={require('../assets/jerboa.jpg')}/>
      </View>
      <Text style={styles.txt}>Jerboa é o nome genérico
        dado aos roedores da família 
        Dipodidae. Com orelhas longas,
        um rabo comprido e saltitante
        como um canguru, esse animal
        corre risco de extinção. Vive
        no Deserto de Gobi, na Mongólia
        e na China e também no nordeste
        da África. Mede menos de dez
        centímetros. Comem insetos e
        têm hábitos noturnos.</Text>

      <View style={styles.viewimg2}>
      <Image style={styles.img1}source={{uri: 'https://akm-img-a-in.tosshub.com/indiatoday/images/story/201602/jerboa_story-and-fb_647_022416051239.jpg?VersionId=X_AWlNR84b5kRxN2fUMHrCg8PHKmBcXh'}}/>
      <Image style={styles.img1}source={{uri: 'https://vivimetaliun.wordpress.com/wp-content/uploads/2015/02/13900-2tfguao.jpg?w=584'}}/>
      </View>
      <Text style={styles.credito}>Creditos: Marcus Zanin</Text>
      <TextInput
      style={styles.txtinput}
      placeholder='Nome'
      placeholderTextColor={'black'}
      />
      <TextInput
      style={styles.txtinput}
      placeholder='Mensagem'
      placeholderTextColor={'black'}
      />
      <Button
      title="Enviar"
      color='blue'
      />
    </View>
    )
}

const styles = StyleSheet.create({
    container: {
      flex: 1,
      padding: 20,
      paddingTop: 50,
      backgroundColor: '#FFE4C4',
    },
    viewimg: {
      paddingTop: 5,
      paddingBottom: 5,
      alignItems: 'flex-end',
    },

    img1: { 
      width: 150,
      height: 150,
    },
    
    txt: {
      fontSize: 20,
      alignSelf: 'flex-end',
      width: 300,
    },
    
    titulo: {
      fontSize: 30,
    },

    viewimg2: {
      paddingTop: 5,
      paddingBottom: 5,
      flexDirection: 'row',
      justifyContent: 'space-between',
    },

    credito: { 
      fontSize: 10,
      alignSelf: 'center',
    },

    txtinput: {
      padding: 5,
      height: 30,
      borderColor: 'black',
      borderWidth: 1,
      borderRadius: 3,
    }
});
