<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage()

function calculateDiff(notification) {
  if (notification.daysAgo > 0) {
    return `${notification.daysAgo} ${notification.daysAgo > 1 ? 'days' : 'day'} ago`
  } else if (notification.hoursAgo > 0) {
    return notification.hoursAgo + 'hr ago'
  } else if (notification.minutesAgo > 0) {
    return notification.minutesAgo + 'min ago'
  } else {
    return 'now'
  }
}
</script>

<template>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
              <i class="far fa-bell"></i>
              <span class="badge badge-danger navbar-badge">{{ page.props.notificationsCount }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
              <span class="dropdown-item dropdown-header">Notifications</span>
              <div class="dropdown-divider"></div>
              <Link :class="{ 'unseen': !notification.seen }"
                v-if="page.props.notifications && page.props.notifications.length > 0"
                v-for="notification in page.props.notificationsData.slice(0, 3)"
                :href="notification.type == 'order' ? route('admin.order', notification.id) : route('admin.contact', notification.id)"
                class="dropdown-item position-relative">

                <div v-if="notification.type == 'order'">
                  <i class="fas fa-shopping-cart mr-2"></i>
                  new order
                  <span class="float-right text-muted text-sm">{{ calculateDiff(notification) }}</span>
                </div>
                
                <div v-else>
                  <i class="fas fa-envelope mr-2"></i>
                  new message
                  <span class="float-right text-muted text-sm">{{ calculateDiff(notification) }}</span>
                </div>

              </Link>
              <Link v-else href="#" class="dropdown-item position-relative">
                No Notifications Yet
              </Link>
              <div class="dropdown-divider"></div>
              <Link :href="route('admin.notifications')" class="dropdown-item dropdown-footer">See All Notifications</Link>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.unseen {
  padding-right: 25px !important;
}

.unseen::after {
  content: '';
  width: 10px;
  height: 10px;
  position: absolute;
  background-color: #0766FF;
  border-radius: 50%;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
}

.navbar-badge {
  font-size: 11px;
}
</style>