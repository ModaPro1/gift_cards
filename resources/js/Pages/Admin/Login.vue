<script setup>
import '@/../css/admin.css' // AdminLTE CSS
import '@/admin.js' // AdminLTE JS

import { Head, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: '',
  password: '',
});

function submit() {
  form.post(route('admin.login'))
}
</script>

<template>
  <Head title="Admin">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  </Head>
  <div class="auth-container">
    <div>
      <a class="d-block text-center w-100" aria-label="Link" :href="'/'">
        <ApplicationLogo></ApplicationLogo>
      </a>
      <div class="auth-card bg-white p-3 rounded">
        <form @submit.prevent="submit">
          <div class="bg-danger text-white rounded p-2 fw-bold mb-2 text-center">
            Admin Login
          </div>
          <div>
            <label for="email">Email</label>
            <input id="email" v-model="form.email" type="email" class="mt-1 form-control" required autofocus
              autocomplete="username">
            <div v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</div>
          </div>

          <div class="mt-3">
            <label for="password">Password</label>
            <input id="password" v-model="form.password" type="password" class="mt-1 form-control" required>
            <div v-if="form.errors.email" class="text-danger">{{ form.errors.password }}</div>
          </div>

          <button @click="submit" class="btn btn-primary mt-3" :disabled="form.processing">
            Log in
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-container {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #F3F4F6;
}

.auth-card {
  width: 500px;
}
</style>