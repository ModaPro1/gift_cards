<script setup>

  import { Head } from '@inertiajs/vue3';
  import { InertiaProgress } from '@inertiajs/progress'
  import AdminSidebar from '@/Components/AdminSidebar.vue'
  import AdminNavbar from '@/Components/AdminNavbar.vue'
  import '@/../css/admin.css' // AdminLTE CSS
  import '@/admin.js' // AdminLTE CSS


  const props = defineProps(['title'])

  InertiaProgress.init({
    delay: .250,
    color: '#fbbf24'
  })

  const handleResize = () => {
    if(window.innerWidth <= 991 && window.innerWidth >= 767) {
      document.body.classList.add('sidebar-collapse')
    }
  }
  handleResize()
  window.addEventListener('resize', handleResize)

</script>

<template>
    <Head>
      <title>{{ props.title }}</title>
      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    </Head>
    <div class="wrapper">
      <AdminNavbar />
      <AdminSidebar/>

      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0 fw-normal">{{ props.title }}</h1>
              </div>
            </div>
          </div>
        </div>

        <div class="content">
          <div class="container-fluid">
            <slot></slot>
          </div>
        </div>
      </div>
    </div>

</template>

<style scoped>
.content-wrapper {
  min-height: calc(100vh - 57px) !important;
  padding-bottom: 10px;
}
</style>
