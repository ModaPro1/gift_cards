<script setup>
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Link } from '@inertiajs/vue3';
  const props = defineProps(['user', 'ordersCount', 'successMessage'])

</script>

<template>
  <AdminLayout :successMessage="successMessage" :title="`Show User (${props.user.id})`">
    <div class="bg-white rounded-2 p-2">
      <div class="input-box mb-2">
        <label for="name">Name</label>
        <input type="text" id="name" class="form-control" disabled :value="props.user.name">
      </div>
      <div class="input-box mb-2">
        <label for="email">Email</label>
        <input type="text" id="email" class="form-control" disabled :value="props.user.email">
      </div>
      <div class="mb-2">
        <b>Created At</b>: {{ new Date(props.user.created_at).toLocaleString().replace(',', '') }}
      </div>
      <div class="mb-2">
        <b>Orders</b>: {{ props.ordersCount }}
      </div>
      <div class="btns">
        <Link :href="route('admin.edit', props.user.id)" class="btn btn-primary me-2">Edit</Link>
        <Link :href="route('admin.index')" class="btn btn-secondary">Back</Link>
      </div>
    </div>
  </AdminLayout>
</template>
