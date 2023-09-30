<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
const form = useForm({
  name: '',
  url: '',
  image: ''
})

const submit = () => {
  form.post(route('admin.addCat'))
}
</script>

<template>
  <AdminLayout title="Add Card">
    <div class="bg-white rounded-3 p-3 shadow-sm">
      <div class="row">
        <div class="col-lg-6">
          <div class="input-box">
            <label for="name">Category Name</label>
            <input id="name" v-model="form.name" type="text" class="form-control" />
            <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="input-box">
            <label for="url">Category URL</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">/</div>
              </div>
              <input id="url" v-model="form.url" type="text" class="form-control" />
            </div>
            <div v-if="form.errors.url" class="text-danger">{{ form.errors.url }}</div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="input-box mt-2">
            <label for="file">Default Image</label>
            <input id="file" @input="(e) => form.image = e.target.files[0]" type="file" class="form-control" />
            <div v-if="form.errors.image" class="text-danger">{{ form.errors.image }}</div>
          </div>
        </div>
      </div>
      <button class="btn btn-success mt-3" @click="submit">Add Category</button>
    </div>
  </AdminLayout>
</template>

<style scoped>
.form-select:focus {
  box-shadow: none;
}
</style>