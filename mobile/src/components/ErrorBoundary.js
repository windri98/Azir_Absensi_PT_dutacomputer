import React from 'react';
import { View, Text, Button, StyleSheet, ScrollView } from 'react-native';

class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      hasError: false,
      error: null,
      errorInfo: null,
    };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true };
  }

  componentDidCatch(error, errorInfo) {
    console.error('Error Boundary caught:', error, errorInfo);
    this.setState({
      error,
      errorInfo,
    });
  }

  resetError = () => {
    this.setState({
      hasError: false,
      error: null,
      errorInfo: null,
    });
  };

  render() {
    if (this.state.hasError) {
      return (
        <View style={styles.container}>
          <ScrollView contentContainerStyle={styles.scrollContent}>
            <View style={styles.errorBox}>
              <Text style={styles.errorTitle}>Terjadi Kesalahan</Text>
              <Text style={styles.errorMessage}>
                Aplikasi mengalami masalah yang tidak terduga. Silakan coba lagi.
              </Text>

              {__DEV__ && (
                <View style={styles.devInfo}>
                  <Text style={styles.devTitle}>Developer Info:</Text>
                  <Text style={styles.errorText}>
                    {this.state.error && this.state.error.toString()}
                  </Text>
                  <Text style={[styles.errorText, { marginTop: 10 }]}>
                    {this.state.errorInfo && this.state.errorInfo.componentStack}
                  </Text>
                </View>
              )}

              <Button
                title="Coba Lagi"
                onPress={this.resetError}
                color="#0284c7"
              />
            </View>
          </ScrollView>
        </View>
      );
    }

    return this.props.children;
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f3f4f6',
  },
  scrollContent: {
    flexGrow: 1,
    justifyContent: 'center',
    padding: 20,
  },
  errorBox: {
    backgroundColor: '#fff',
    borderRadius: 8,
    padding: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  errorTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#dc2626',
    marginBottom: 10,
  },
  errorMessage: {
    fontSize: 16,
    color: '#374151',
    marginBottom: 20,
    lineHeight: 24,
  },
  devInfo: {
    backgroundColor: '#fee2e2',
    borderRadius: 6,
    padding: 12,
    marginBottom: 20,
    borderLeftWidth: 4,
    borderLeftColor: '#dc2626',
  },
  devTitle: {
    fontSize: 12,
    fontWeight: '600',
    color: '#991b1b',
    marginBottom: 8,
  },
  errorText: {
    fontSize: 11,
    color: '#7f1d1d',
    fontFamily: 'monospace',
    lineHeight: 16,
  },
});

export default ErrorBoundary;
