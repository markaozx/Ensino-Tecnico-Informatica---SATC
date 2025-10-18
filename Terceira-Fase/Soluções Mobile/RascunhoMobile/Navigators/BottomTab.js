// import Jerboa from './Componentes/test';

import Login from './Screens/Login';
import Home from './Screens/Home';                               

import 'react-native-gesture-handler';

import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';

export default function BottomTab() {

  const BottomTab = createBottomTabNavigator();

  return ( 
    <NavigationContainer>
      <BottomTab.Navigator initialRouteName='Login'>   
        <BottomTab.Screen name="Login" component={Login} /> 
        <BottomTab.Screen name="Home" component={Home} />
      </BottomTab.Navigator>
    </NavigationContainer>
  );
}
