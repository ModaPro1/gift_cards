<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
const props = defineProps(['contacts'])
</script>

<template>
  <AdminLayout title="Contact Requests">
    <div v-if="contacts && contacts.length > 0" v-for="contact in contacts" class="bg-white rounded-3 p-3 mb-3">
      <div class="info d-flex justify-content-between">
        <span><b>Name</b>: {{ contact.user_name }}</span>
        <div class="text-muted">{{ new Date(contact.created_at).toLocaleString().replace(',', '') }}</div>
      </div>
      <div class="info"><b>Email</b>: <a :href="`mailto:${contact.user_email}`">{{ contact.user_email }}</a></div>
      <button class="btn btn-outline-primary mt-2" data-toggle="collapse" :href="`#messageCollapse${contact.id}`" role="button" aria-expanded="false"
        :aria-controls="`messageCollapse${contact.id}`">
        View Message
      </button>
      <div class="collapse" :id="`messageCollapse${contact.id}`">
        <div class="pt-2">
          {{ contact.message }}
        </div>
      </div>
    </div>
    <div v-else>
      No Contact Requests Yet.
    </div>
  </AdminLayout>
</template>