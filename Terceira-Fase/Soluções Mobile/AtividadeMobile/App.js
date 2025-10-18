import Login from "./screens/Login";
import Estoque from "./screens/Estoque";
import Fale from "./screens/Fale";
import { createDrawerNavigator } from '@react-navigation/drawer';
import { createStackNavigator } from "@react-navigation/stack";
import { NavigationContainer } from "@react-navigation/native";

const Stack = createStackNavigator();
const Drawer = createDrawerNavigator();

export default function App() {
  return (
    <NavigationContainer>{
      <Stack.Navigator>
        <Stack.Screen name="Login" component={Login} />
        <Stack.Screen name="Menu" component={Menu} />
      </Stack.Navigator>
    }</NavigationContainer>
  );
}

function Menu(){
  return (
      <Drawer.Navigator>
        <Drawer.Screen name="Estoque" component={Estoque} />
        <Drawer.Screen name="Fale Conosco" component={Fale} />
      </Drawer.Navigator>
  )
}