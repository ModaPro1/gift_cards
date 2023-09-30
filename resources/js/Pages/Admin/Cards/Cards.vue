<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import useToast from '@/hooks/toast';
import Swal from 'sweetalert2';

export default {
  components: {
    AdminLayout,
    Link
  },
  props: ['cats', 'success'],
  data() {
    return {
      editingCategoryId: null,
      editCategoryValue: '',
    }
  },
  methods: {
    editCategory(id, value) {
      this.editingCategoryId = id
      this.editCategoryValue = value
      this.$nextTick(() => {
        this.$refs.editCategoryInput[0].focus();
      });
    },
    deleteCategory(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "If you did this you will delete orders and cards in this category.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          router.post(route('admin.deleteCat'), {
            id: id
          }, {
            onSuccess() {
              useToast('success', 'Category Deleted Successfully.')
            }
          })
        }
      })
    },
    deleteCard(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "If you did this you will delete orders on this card.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          router.post(route('admin.deleteCard'), {
            id: id
          }, {
            onSuccess() {
              useToast('success', 'Card Deleted Successfully.')
            }
          })
        }
      })

    }
  },
  mounted() {
    window.addEventListener('keyup', (e) => {
      if (this.editingCategoryId && e.code == 'Enter') {
        const id = this.editingCategoryId
        const self = this
        this.$inertia.post(route('admin.changeCategoryName'), {
          id: id,
          value: this.editCategoryValue
        }, {
          onSuccess() {
            useToast('success', 'Successfully Changed.')
            self.editingCategoryId = null
          },
          onError() {
            useToast('error', 'An Error Occured.')
            self.editingCategoryId = null
          }
        })
      }
    })

    if (this.success) {
      useToast('success', this.success)
    }
  },
}
</script>

<template>
  <AdminLayout title="Manage Cards & Categories">
    <div class="mb-3" v-for="cat in this.cats">
      <div class="title">
        <div v-if="cat.id == this.editingCategoryId" class="d-flex gap-2">
          <input type="text" v-model="editCategoryValue" ref="editCategoryInput" class="form-control mb-2 category-input">
        </div>
        <div class="d-flex gap-2" v-else>
          <h5>{{ cat.category_name.toUpperCase() }}</h5>
          <i class="fas fa-pen fa-sm mt-1 text-info action" @click="editCategory(cat.id, cat.category_name)"></i>
          <i class="fas fa-trash-alt fa-sm mt-1 text-danger action" @click="deleteCategory(cat.id)"></i>
        </div>
      </div>
      <div class="cards d-flex gap-3">
        <div v-if="cat.cards && cat.cards.length > 0" v-for="card in cat.cards"
          class="p-2 bg-white rounded-3 shadow-sm position-relative">
          <div class="price" :class="{
            'red': card.type == 'roblox',
            'text-black': card.type == 'googleplay'
          }">
            ${{ card.price }}
          </div>
          <img :src="`../../images/small/${card.image}?${Date.now()}`" width="100" height="125" alt="Card Image"
            class="rounded-3">
          <div class="actions mt-2 d-flex justify-content-center gap-2">
            <Link :href="route('admin.editCard', card.id)" style="line-height: 1;">
            <i class="fas fa-pen text-info"></i>
            </Link>
            <i class="fas fa-trash-alt text-danger action" @click="deleteCard(card.id)"></i>
          </div>
        </div>
        <div v-else>No Cards For This Category.</div>
      </div>
    </div>
    <div class="btns mt-3">
      <Link :href="route('admin.addCard')" class="btn btn-outline-primary me-2">Add Card</Link>
      <Link :href="route('admin.showAddCat')" class="btn btn-outline-info">Add Category</Link>
    </div>
  </AdminLayout>
</template>

<style scoped>
.category-input {
  width: 130px;
  height: 32px;
}

.action {
  cursor: pointer;
}

.price {
  position: absolute;
  left: 12px;
  top: 10px;
  font-weight: bold;
  color: white;
}

.red {
  color: #f35959;
}
</style>