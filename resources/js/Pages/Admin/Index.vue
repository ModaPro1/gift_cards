<script setup>
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Link, useForm } from '@inertiajs/vue3';
  import { ref, watch } from 'vue';
  import { throttle } from "lodash";
  import Swal from 'sweetalert2';

  const props = defineProps(['users', 'usersCount', 'ordersCount', 'contactCount', 'searchVal'])
  const searchVal = ref(props.searchVal || '')
  const searchForm = useForm({
    search: ''
  })
  const deleteForm = useForm({})

  // Define the throttled search function here
  const thr = throttle(() => {
    searchForm.search = searchVal.value
    searchForm.get(route('admin.index'), {
      preserveState: true,
    })
  }, 1000)

  // Call the throttled search function when the input value changes
  watch(searchVal, thr)

  function deleteUser(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "Do this if you want to delete this user.",
      icon: 'warning',
      showCancelButton: true, 
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Delete user'
    }).then((result) => {
      if (result.isConfirmed) {
        deleteForm.post(route('admin.delete', id), {
          onFinish() {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })
            Toast.fire({
              icon: 'success',
              title: "User successfully deleted."
            })
          }
        })
      }
    })
  }

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
          <Link :href="route('admin.contacts')" class="small-box-footer">
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
                <th style="width: 5%;">
                  ID
                </th>
                <th style="width: 20%;">
                  User Name
                </th>
                <th style="width: 30%;">
                  User Email
                </th>
                <th>
                  <input type="search" placeholder="Search for user..." v-model="searchVal" @input="search"
                    class="form-control">
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
                  <Link class="btn btn-info btn-sm mr-1 text-white" :href="route('admin.edit', user.id)">
                  <i class="fas fa-pencil-alt mr-1"></i>
                  Edit
                  </Link>
                  <button @click="deleteUser(user.id)" class="btn btn-danger btn-sm" href="#">
                    <i class="fas fa-trash mr-1"></i>
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="footer p-3 bg-grey d-flex justify-content-between flex-wrap">
        <button class="btn btn-outline-primary">
          <i class="fas fa-user-plus me-1"></i>
          Add
        </button>
        <div v-if="props.users.links.length > 3">
          <ul class="pagination m-0 ml-auto">
            <li class="page-item" v-for="link in props.users.links" :class="{ active: link.active, disabled: link.url == null }">
              <a v-if="link.url === null" class="page-link" v-html="link.label"></a>
              <Link v-else class="page-link" :href="link.url" v-html="link.label" preserve-scroll>
              </Link>
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