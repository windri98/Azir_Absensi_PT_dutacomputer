import React, { useEffect, useState } from 'react';
import { ActivityIndicator, View } from 'react-native';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createStackNavigator } from '@react-navigation/stack';
import { useAuthStore } from './src/store/authStore';
import ErrorBoundary from './src/components/ErrorBoundary';
import { initDatabase } from './src/services/database';
import { LoginScreen } from './src/screens/LoginScreen';
import { HomeScreen } from './src/screens/HomeScreen';
import { AttendanceScreen } from './src/screens/AttendanceScreen';
import { HistoryScreen } from './src/screens/HistoryScreen';
import { ReportsScreen } from './src/screens/ReportsScreen';
import { ProfileScreen } from './src/screens/ProfileScreen';
import { ActivityHistoryScreen } from './src/screens/ActivityHistoryScreen';
import { ActivityCreateScreen } from './src/screens/ActivityCreateScreen';
import { ActivityDetailScreen } from './src/screens/ActivityDetailScreen';

const Tab = createBottomTabNavigator();
const Stack = createStackNavigator();

// Deep linking configuration
const linking = {
  prefixes: ['pt-duta-computer://', 'https://dutacomputer.app/', 'https://www.dutacomputer.app/'],
  config: {
    screens: {
      Main: {
        screens: {
          Home: 'home',
          Attendance: 'attendance',
          Activities: {
            screens: {
              ActivityHistory: 'activities',
              ActivityCreate: 'activities/create',
              ActivityDetail: 'activities/:id',
            },
          },
          History: 'history',
          Reports: 'reports',
          Profile: 'profile',
        },
      },
      Login: 'login',
    },
  },
};

const ActivityStack = () => (
  <Stack.Navigator>
    <Stack.Screen
      name="ActivityHistory"
      component={ActivityHistoryScreen}
      options={{ title: 'Aktivitas' }}
    />
    <Stack.Screen
      name="ActivityCreate"
      component={ActivityCreateScreen}
      options={{ title: 'Aktivitas Baru' }}
    />
    <Stack.Screen
      name="ActivityDetail"
      component={ActivityDetailScreen}
      options={{ title: 'Detail Aktivitas' }}
    />
  </Stack.Navigator>
);

const MainTabs = () => (
  <Tab.Navigator screenOptions={{ headerShown: false }}>
    <Tab.Screen name="Home" component={HomeScreen} />
    <Tab.Screen name="Attendance" component={AttendanceScreen} />
    <Tab.Screen name="Activities" component={ActivityStack} />
    <Tab.Screen name="History" component={HistoryScreen} />
    <Tab.Screen name="Reports" component={ReportsScreen} />
    <Tab.Screen name="Profile" component={ProfileScreen} />
  </Tab.Navigator>
);

export default function App() {
  const { token, initialize } = useAuthStore();
  const [ready, setReady] = useState(false);

  useEffect(() => {
    const setupApp = async () => {
      try {
        // Initialize database
        await initDatabase();
      } catch (error) {
        console.warn('Database initialization error (non-critical):', error);
      }
      
      // Initialize auth
      await initialize();
    };

    setupApp().finally(() => setReady(true));
  }, []);

  if (!ready) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
        <ActivityIndicator size="large" color="#0284c7" />
      </View>
    );
  }

  return (
    <ErrorBoundary>
      <SafeAreaProvider>
        <NavigationContainer
          linking={linking}
          fallback={
            <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
              <ActivityIndicator size="large" color="#0284c7" />
            </View>
          }
        >
          <Stack.Navigator screenOptions={{ headerShown: false }}>
            {token ? (
              <Stack.Screen name="Main" component={MainTabs} />
            ) : (
              <Stack.Screen name="Login" component={LoginScreen} />
            )}
          </Stack.Navigator>
        </NavigationContainer>
      </SafeAreaProvider>
    </ErrorBoundary>
  );
}
