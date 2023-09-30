<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
const form = useForm({
  name: '',
  email: '',
  password: '',
})
const showPassword = ref(false)

const submit = () => {
  form.post(route('admin.addUser'))
}
</script>

<template>
  <AdminLayout title="Add User">
    <div class="bg-white p-3 rounded-3 shadow-sm">
      <div class="row">
        <div class="col-lg-6">
          <div class="input-box">
            <label for="name">Name</label>
            <input id="name" v-model="form.name" type="text" class="form-control">
            <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="input-box">
            <label for="email">Email</label>
            <input id="email" v-model="form.email" type="text" class="form-control" autocomplete="off">
            <div v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="input-box mt-2">
            <label for="password">Password</label>
            <div class="position-relative">
              <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" class="form-control" autocomplete="off">
              <i @click="showPassword = !showPassword" v-if="!showPassword" class="fas fa-eye eye"></i>
              <i @click="showPassword = !showPassword" v-if="showPassword" class="fas fa-eye-slash eye"></i>
            </div>
            <div v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</div>
          </div>
        </div>
      </div>
      <button class="btn btn-success mt-3" @click="submit">
        <i class="fa fa-plus me-1"></i>
        Add
      </button>
    </div>
  </AdminLayout>
</template>

<style scoped>
#password {
  padding-right: 35px;
}
.eye {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
}
</style>