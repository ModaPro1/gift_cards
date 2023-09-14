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
            <Link :href="route('admin.orders')" class="nav-link" :class="{'active': route().current('admin.orders')}">
              <i class="nav-icon fa fa-shopping-cart"></i>
              <p>Orders</p>
            </Link>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link" :class="{'active': route().current('admin.contacts')}">
              <i class="nav-icon fa fa-phone"></i>
              <p>Contacts</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link" :class="{'active': route().current('admin.notifications')}">
              <i class="nav-icon fa fa-bell"></i>
              <p>Notifications</p>
            </a>
          </li>
          <li class="nav-item logout">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>

<style scoped>
.logout:hover a {
  background-color: var(--danger) !important;
}
</style>