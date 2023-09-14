<script setup>
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Link } from '@inertiajs/vue3';
  import { ref } from 'vue';

  const props = defineProps(['users', 'usersCount', 'ordersCount', 'contactCount'])
  const links = ref(props.users.links)
</script>

<template>
  <AdminLayout title="Main Page">
    <div class="row">
      <div class="col-lg-4">
        <div class="small-box bg-gradient-success">
          <div class="inner">
            <h3>{{ props.usersCount }}</h3>
            <p>User Registrations</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-plus"></i>
          </div>
          <a href="#users" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="small-box bg-gradient-info">
          <div class="inner">
            <h3>{{ props.ordersCount }}</h3>
            <p>Orders Requests</p>
          </div>
          <div class="icon">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <Link :href="route('admin.orders')" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </Link>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="small-box bg-gradient-secondary">
          <div class="inner">
            <h3>{{ props.contactCount }}</h3>
            <p>Contact Requests</p>
          </div>
          <div class="icon">
            <i class="fas fa-phone"></i>
          </div>
          <Link href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </Link>
        </div>
      </div>
    </div>
    <div class="card" id="users">
      <div class="card-header">
        <h3 class="card-title">User Registrations</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped projects">
            <thead>
              <tr>
                <th>
                  ID
                </th>
                <th>
                  User Name
                </th>
                <th>
                  User Email
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in props.users.data">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td class="project-actions text-right">
                  <Link class="btn btn-primary btn-sm mr-1" :href="route('admin.user', user.id)">
                    <i class="fas fa-folder mr-1"></i>
                    View
                  </Link>
                  <Link class="btn btn-info btn-sm mr-1 text-white" :href="route('admin.editUser', user.id)">
                    <i class="fas fa-pencil-alt mr-1"></i>
                    Edit
                  </Link>
                  <a class="btn btn-danger btn-sm" href="#">
                    <i class="fas fa-trash mr-1"></i>
                    Delete
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="footer p-3 bg-grey d-flex justify-content-between flex-wrap">
        <button class="btn btn-outline-primary">Add New User</button>
        <div v-if="links.length > 3">
          <ul class="pagination m-0 ml-auto">
            <li class="page-item" v-for="link in links" :class="{active: link.active, disabled: link.url == null}">
              <a
                v-if="link.url === null"
                class="page-link"
                v-html="link.label"
              ></a>
              <Link
                v-else
                class="page-link"
                :href="link.url"
                v-html="link.label"
                preserve-scroll
                ></Link>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
  td {
    white-space: nowrap;
  }
  .footer {
    border-top: 1px solid #c1c1c1;
    gap: 10px;
  }
</style>