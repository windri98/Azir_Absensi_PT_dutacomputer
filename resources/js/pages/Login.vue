<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-600 to-primary-900 flex items-center justify-center px-4">
    <Card class="w-full max-w-md">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">PT Duta  computer</h1>
        <p class="text-gray-600 mt-2">Sistem Manajemen Absensi Karyawan</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <FormInput
          v-model="form.email"
          type="email"
          label="Email"
          placeholder="your@email.com"
          :error="errors.email"
          required
        />

        <FormInput
          v-model="form.password"
          type="password"
          label="Password"
          placeholder="••••••••"
          :error="errors.password"
          required
        />

        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input type="checkbox" v-model="form.remember" class="rounded border-gray-300">
            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
          </label>
          <a href="#" class="text-sm text-primary-600 hover:text-primary-700">Lupa password?</a>
        </div>

        <Button
          type="submit"
          variant="primary"
          size="lg"
          class="w-full"
          :loading="loading"
        >
          Masuk
        </Button>

        <p v-if="errors.general" class="text-red-500 text-sm text-center">{{ errors.general }}</p>
      </form>

      <div class="mt-6 pt-6 border-t border-gray-200">
        <p class="text-center text-sm text-gray-600">
          Demo Credentials:
          <br>
          <code class="text-xs bg-gray-100 px-2 py-1 rounded">employee1@example.com / password123</code>
        </p>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { useNotificationStore } from '../stores/notification';
import Card from '../components/Card.vue';
import FormInput from '../components/FormInput.vue';
import Button from '../components/Button.vue';

const router = useRouter();
const authStore = useAuthStore();
const notificationStore = useNotificationStore();

const loading = ref(false);
const form = reactive({
  email: '',
  password: '',
  remember: false,
});

const errors = reactive({
  email: '',
  password: '',
  general: '',
});

const handleLogin = async () => {
  // Reset errors
  errors.email = '';
  errors.password = '';
  errors.general = '';

  // Validate
  if (!form.email) {
    errors.email = 'Email is required';
    return;
  }
  if (!form.password) {
    errors.password = 'Password is required';
    return;
  }

  loading.value = true;
  try {
    await authStore.login(form.email, form.password);
    notificationStore.success('Login berhasil!');
    router.push('/');
  } catch (error) {
    if (error.response?.status === 401) {
      errors.general = 'Email atau password salah';
    } else {
      errors.general = error.message || 'Terjadi kesalahan saat login';
    }
    notificationStore.error(errors.general);
  } finally {
    loading.value = false;
  }
};
</script>
