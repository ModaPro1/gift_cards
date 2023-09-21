<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage()

function calculateDiff(notification) {
  if(notification.daysAgo > 0) {
    return `${notification.daysAgo} ${notification.daysAgo > 1 ? 'days' : 'day'} ago`
  } else if(notification.hoursAgo > 0) {
    return notification.hoursAgo + 'hr ago'
  }else if(notification.minutesAgo > 0) {
    return notification.minutesAgo + 'min ago'
  } else {
    return 'now'
  }
}
</script>

<template>
  <AdminLayout title="Notifications">
    <div v-for="notification in page.props.notificationsData" class="bg-white p-3 rounded-3 mb-3" :class="{'seen': !notification.seen}">
      <div class="d-flex justify-content-between">
        <div v-if="notification.type == 'order'">
          User <Link :href="route('admin.user', notification.user.id)">{{ notification.user.name }}</Link> Bought a ${{ notification.card.price }} {{ notification.card.name }} Card, <Link :href="route('admin.order', notification.id)">Check the order</Link>
        </div>
        <div v-else>
          User <Link :href="route('admin.user', notification.user.id)">{{ notification.user.name }}</Link> Sent you a <Link :href="route('admin.contact', notification.id)">Message</Link>
        </div>
        <div class="text-muted">{{ calculateDiff(notification) }}</div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.seen {
  position: relative;
  padding-left: 27px !important;
}
.seen::before {
  content: '';
  position: absolute;
  background-color: #0766FF;
  height: 10px;
  width: 10px;
  border-radius: 50%;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
}
</style>