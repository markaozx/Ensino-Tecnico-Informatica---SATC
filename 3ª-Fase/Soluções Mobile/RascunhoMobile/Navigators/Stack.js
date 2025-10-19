// import Jerboa from './Componentes/test';

import Login from './Screens/Login';
import Home from './Screens/Home';

import 'react-native-gesture-handler';

import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';

export default function Stack() {

  const Stack = createStackNavigator();

  return ( 
    <NavigationContainer>
      <Stack.Navigator initialRouteName='Login'>   
        <Stack.Screen name="Login" component={Login} /> 
        <Stack.Screen name="Home" component={Home} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}
