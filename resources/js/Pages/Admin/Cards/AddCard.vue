<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
const props = defineProps(['cats'])
const form = useForm({
  name: '',
  price: '',
  image: '',
  category: props.cats[0].name
})

const submit = () => {
  form.post(route('admin.addCard'))
}
</script>

<template>
  <AdminLayout title="Add Card">
    <div class="bg-white rounded-3 p-3 shadow-sm">
      <div class="row">
        <div class="col-lg-6">
          <div class="input-box">
            <label for="name">Card Name</label>
            <input id="name" v-model="form.name" type="text" class="form-control" />
            <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="input-box">
            <label for="price">Card Price</label>
            <input id="price" v-model="form.price" type="number" class="form-control" />
            <div v-if="form.errors.price" class="text-danger">{{ form.errors.price }}</div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="input-box mt-2">
            <label for="file">Card Image</label>
            <input id="file" @input="(e) => form.image = e.target.files[0]" type="file" class="form-control" />
            <div v-if="form.errors.image" class="text-danger">{{ form.errors.image }}</div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="input-box mt-2">
            <label for="cat">Category</label>
            <select v-model="form.category" id="cat" class="form-control">
              <option v-for="cat in cats" :value="cat.name">{{ cat.name }}</option>
            </select>
            <div v-if="form.errors.category" class="text-danger">{{ form.errors.category }}</div>
          </div>
        </div>
      </div>
      <button class="btn btn-success mt-3" @click="submit">Add Card</button>
    </div>
  </AdminLayout>
</template>