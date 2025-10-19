// import Jerboa from './Componentes/test';

import Login from './Screens/Login';
import Home from './Screens/Home';                               

import 'react-native-gesture-handler';

import { NavigationContainer } from '@react-navigation/native';
import { createDrawerNavigator } from '@react-navigation/drawer';

export default function Drawer() {

  const Drawer = createDrawerNavigator();

  return ( 
    <NavigationContainer>
      <Drawer.Navigator initialRouteName='Login'>   
        <Drawer.Screen name="Login" component={Login} /> 
        <Drawer.Screen name="Home" component={Home} />
      </Drawer.Navigator>
    </NavigationContainer>
  );
}
