import { StyleSheet, Text, View, TextInput, Image, ImageBackground, Button} from 'react-native';
import { useState } from 'react';

export default function Estoque({navigator}){
  const [count, setCount] = useState(0);
  function somar() {
    setCount(count + 1);
  }
  function diminui() {
    setCount(count - 1);
  }
  return (
    
    <ImageBackground style={styles.fundo} source={{uri: 'https://wallpapers-max.b-cdn.net/wallpapers/2024/13feb/thumb/deep-black-surface-with-wavy-lines-wallpaper.jpg'}}>
    <View style={styles.container}>
      <View style={styles.topo}>
        <Image style={styles.img} source={{uri: 'https://starplast.com.br/wp-content/uploads/2022/09/logo-kings-sneakers.png'}}/>
        <Text style={styles.titulo}>Nosso Estoque:</Text>
      </View>
      <View style={styles.campos}>
        <View style={styles.prod}>
          <Image style={styles.tenis} source={{uri: 'https://yourid.jetassets.com.br/produto/multifotos/hd/20241122170325_1540998460_DZ.jpg?ims=fit-in/1500x/filters:quality(100):format(webp)'}}/>
          <Text style={styles.text}>Adidas</Text>
          <View style={styles.botoes}>
            <Button title="Mais" color="red" onClick={somar}/>
            <Text style={styles.cont}>{count}</Text>  
            <Button title="Menos" color="red" onClick={diminui}/>
            </View>
        </View>
        <View style={styles.prod}>
          <Image style={styles.tenis} source={{uri: 'https://yourid.jetassets.com.br/produto/multifotos/hd/20241122170325_1540998460_DZ.jpg?ims=fit-in/1500x/filters:quality(100):format(webp)'}}/>
          <Text style={styles.text}>Adidas</Text>
          <View style={styles.botoes}>
            <Button title="Mais" color="red" onClick={somar}/>
            <Text style={styles.cont}>{count}</Text>  
            <Button title="Menos" color="red" onClick={diminui}/>
            </View>
        </View>
      </View>
      <View style={styles.campos}>
        <View style={styles.prod}>
          <Image style={styles.tenis} source={{uri: 'https://yourid.jetassets.com.br/produto/multifotos/hd/20241122170325_1540998460_DZ.jpg?ims=fit-in/1500x/filters:quality(100):format(webp)'}}/>
          <Text style={styles.text}>Adidas</Text>
          <View style={styles.botoes}>
            <Button title="Mais" color="red" onClick={somar}/>
            <Text style={styles.cont}>{count}</Text>  
            <Button title="Menos" color="red" onClick={diminui}/>
            </View>
        </View>
        <View style={styles.prod}>
          <Image style={styles.tenis} source={{uri: 'https://yourid.jetassets.com.br/produto/multifotos/hd/20241122170325_1540998460_DZ.jpg?ims=fit-in/1500x/filters:quality(100):format(webp)'}}/>
          <Text style={styles.text}>Adidas</Text>
          <View style={styles.botoes}>
            <Button title="Mais" color="red" onClick={somar}/>
            <Text style={styles.cont}>{count}</Text>  
            <Button title="Menos" color="red" onClick={diminui}/>
            </View>
        </View>
      </View>
      <View style={styles.campos}>
        <View style={styles.prod}>
          <Image style={styles.tenis} source={{uri: 'https://yourid.jetassets.com.br/produto/multifotos/hd/20241122170325_1540998460_DZ.jpg?ims=fit-in/1500x/filters:quality(100):format(webp)'}}/>
          <Text style={styles.text}>Adidas</Text>
          <View style={styles.botoes}>
            <Button title="Mais" color="red" onClick={somar}/>
            <Text style={styles.cont}>{count}</Text>  
            <Button title="Menos" color="red" onClick={diminui}/>
            </View>
        </View>
        <View style={styles.prod}>
          <Image style={styles.tenis} source={{uri: 'https://yourid.jetassets.com.br/produto/multifotos/hd/20241122170325_1540998460_DZ.jpg?ims=fit-in/1500x/filters:quality(100):format(webp)'}}/>
          <Text style={styles.text}>Adidas</Text>
          <View style={styles.botoes}>
            <Button title="Mais" color="red" onClick={somar}/>
            <Text style={styles.cont}>{count}</Text>  
            <Button title="Menos" color="red" onClick={diminui}/>
            </View>
        </View>
      </View>
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
    topo: {
      flexDirection: 'row',
    },
    tenis: {
      width: 120,
      height: 120,
    },
    prod: {
      padding: 15
    },
    input: {
      borderWidth: 1,
      borderColor: 'white',
      height: 30,
    },
    campos: {
      flexDirection: 'row',
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
      width: 50,
      height: 50,
      justifyContent: 'flex-start',
    },
    titulo: {
      fontFamily: 'arialbold',
      color: 'red',
      paddingTop: 5,
      paddingLeft: 35,
      fontSize: 35,
    },
    fundo: {
      flex: 1,
    justifyContent: 'center',
    },
    botoes: {
      flexDirection: 'row',
    },
    cont: {
      padding: 5,
      fontSize: 25,
      color: 'red'
    }
  });