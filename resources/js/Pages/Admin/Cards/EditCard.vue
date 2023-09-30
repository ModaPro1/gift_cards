<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps(['card']);
const fileInput = ref(null);
const cardImage = ref(null);

const submitForm = useForm({
  name: props.card.name,
  price: props.card.price,
  image: null
})

const openFileInput = () => {
  fileInput.value.click();
};

const handleFileChange = (event) => {
  const file = event.target.files[0];
  const supportedExt = ['image/png', 'image/jpg', 'image/webp', 'image/gif', 'image/jpeg'];
  if (file && supportedExt.includes(file.type)) {
    const reader = new FileReader();

    submitForm.image = file

    reader.onload = (e) => {
      // Display the selected image in the card image element
      cardImage.value.src = e.target.result;
    };

    reader.readAsDataURL(file);
  }
};

const submit = () => {
  submitForm.post(route('admin.editCard', props.card.id))
};
</script>


<template>
  <AdminLayout title="Edit Card">
    <form ref="form" enctype="multipart/form-data" method="POST" @submit.prevent="submit"
      class="shadow-sm bg-white rounded-3 p-3 d-flex gap-3 position-relative">
      <div class="card-image position-relative" @click="openFileInput">
        <img :src="`../../images/small/${card.image}`" height="152" width="121" ref="cardImage" alt="Card Image"
          class="rounded-3">
        <input type="file" hidden ref="fileInput" name="image" @change="handleFileChange">
        <i class="fa fa-pen edit-image"></i>
      </div>
      <div class="info">
        <div class="input-box mb-1">
          <label class="mb-1" for="name">Card Name</label>
          <input type="text" v-model="submitForm.name" class="form-control" :class="{'is-invalid': submitForm.errors.name}" name="name" id="name" autocomplete="off">
        </div>
        <div class="input-box">
          <label class="mb-1" for="price">Card Price</label>
          <input type="number" v-model="submitForm.price" class="form-control" :class="{'is-invalid': submitForm.errors.price}" name="price" id="price">
        </div>
        <button class="btn btn-primary align-self-end save">Save</button>
      </div>
    </form>
  </AdminLayout>
</template>

<style scoped>
.card-image {
  cursor: pointer;
}

.card-image::after {
  content: '';
  height: 100%;
  width: 100%;
  position: absolute;
  left: 0;
  top: 0;
  border-radius: var(--bs-border-radius-lg);
  background-color: #fff;
  transition: .3s;
  opacity: 0;
}

.edit-image {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
  transition: .3s;
  opacity: 0;
}

.card-image:hover .edit-image {
  opacity: 1;
}

.card-image:hover::after {
  opacity: .4;
}

.save {
  position: absolute;
  right: 20px;
  bottom: 20px;
}
</style>