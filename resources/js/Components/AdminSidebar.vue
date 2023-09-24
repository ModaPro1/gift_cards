<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLogo from './AdminLogo.vue'
</script>

<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-2">
    <Link href="/admin" class="brand-link text-decoration-none">
      <AdminLogo class="m-0"></AdminLogo>
      <span class="brand-text font-weight-light">Admin Page</span>
    </Link>
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <Link href="/admin" class="nav-link" :class="{'active': route().current('admin.index')}">
              <i class="nav-icon fas fa-home"></i>
              <p>Main Page</p>
            </Link>
          </li>
          <li class="nav-item">
            <Link :href="route('admin.cards')" class="nav-link" :class="{'active': route().current('admin.cards')}">
              <i class="nav-icon far fa-credit-card"></i>
              <p>Manage Cards</p>
            </Link>
          </li>
          <li class="nav-item">
            <Link :href="route('admin.orders')" class="nav-link" :class="{'active': route().current('admin.orders')}">
              <i class="nav-icon fa fa-shopping-cart"></i>
              <p>Orders</p>
            </Link>
          </li>
          <li class="nav-item">
            <Link :href="route('admin.contacts')" class="nav-link" :class="{'active': route().current('admin.contacts')}">
              <i class="nav-icon fa fa-phone"></i>
              <p>Contacts</p>
            </Link>
          </li>
          <li class="nav-item">
            <Link :href="route('admin.notifications')" class="nav-link" :class="{'active': route().current('admin.notifications')}">
              <i class="nav-icon fa fa-bell"></i>
              <p>Notifications</p>
            </Link>
          </li>
          <li class="nav-item logout">
            <Link as="button" method="POST" :href="route('admin.logout')" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </Link>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>

<style scoped>
.logout button {
  text-align: left;
  color: #c2c7d0;
}
.logout:hover button {
  background-color: var(--danger) !important;
}
</style>