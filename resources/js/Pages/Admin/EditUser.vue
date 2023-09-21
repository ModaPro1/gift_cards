<script setup>
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Link, useForm } from '@inertiajs/vue3';
  import { ref } from 'vue';
  const props = defineProps(['user', 'ordersCount'])
  
  const form = useForm({
    name: props.user.name,
    email: props.user.email
  })
  
  const buttonLoading = ref(false)
  
  function edit() {
    form.post(route('admin.edit', props.user.id), {
      onStart: () => buttonLoading.value = true,
      onFinish: () =>  buttonLoading.value = false
    })
  }
</script>

<template>
  <AdminLayout :title="`Edit User (${props.user.id})`">
    <div class="bg-white rounded-2 p-2">
      <div class="input-box mb-2">
        <label for="name">Name</label>
        <input type="text" id="name" class="form-control" :class="{'is-invalid': form.errors.name}" v-model="form.name">
        <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
      </div>
      <div class="input-box mb-2">
        <label for="email">Email</label>
        <input type="text" id="email" class="form-control" :class="{'is-invalid': form.errors.email}" v-model="form.email">
        <div v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</div>
      </div>
      <div class="mb-2">
        <b>Created At</b>: {{ new Date(props.user.created_at).toLocaleString().replace(',', '') }}
      </div>
      <div class="mb-2">
        <b>Orders</b>: {{ props.ordersCount }}
      </div>
      <div class="btns">
        <button v-if="!buttonLoading" @click="edit" class="btn btn-success me-2">Save</button>
        <button v-else class="btn btn-success me-2" type="button" disabled>
          <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
          <span role="status">Save</span>
        </button>
        <Link :href="route('admin.index')" class="btn btn-secondary">Back</Link>
      </div>
    </div>
  </AdminLayout>
</template>