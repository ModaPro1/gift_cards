<script setup>
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { useForm } from '@inertiajs/vue3';

  const props = defineProps(['name', 'email'])
  const form = useForm({
    name: props.name,
    email: props.email,
    message: ''
  })

  function submit() {
    form.post(route('contact'))
  }
</script>

<template>
  <AppLayout title="Contact Me">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Contact Me
      </h2>
    </template>
    <div class="py-12 max-w-2xl mx-auto">
      <form @submit.prevent="submit">
          <div>
              <InputLabel for="name" value="Name" />
              <TextInput
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="mt-1 block w-full"
                  required
                  autocomplete="name"
              />
              <InputError class="mt-2" :message="form.errors.name" />
          </div>

          <div class="mt-4">
              <InputLabel for="email" value="Email" />
              <TextInput
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="mt-1 block w-full"
                  required
                  autocomplete="username"
              />
              <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <div class="mt-4">
              <InputLabel for="message" value="Message" />
              <textarea
                id="message"
                class="border-gray-300 focus:border-amber-400 focus:ring-amber-400 rounded-md shadow-sm mt-1 block w-full"
                required
                rows="7"
                v-model="form.message"
                ></textarea>
              <InputError class="mt-2" :message="form.errors.message" />
          </div>

          <div class="flex items-center mt-4">
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  Send
              </PrimaryButton>
          </div>
      </form>
    </div>
  </AppLayout>
</template>