

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useToast from '@/hooks/toast';
import Swal from 'sweetalert2';
import { Link } from '@inertiajs/vue3';

export default {
  props: ['orders'],
  components: {
    AdminLayout,
    Link
  },
  data() {
    return {
      sendFormsInvalid: [],
    }
  },
  methods: {
    orderStatus(status) {
      if (status == 'paid') {
        return "Pending"
      } else if (status == 'recieved') {
        return "Recieved"
      } else {
        return "Refused"
      }
    },
    sendCard(order_id, order_status) {
      this.sendFormsInvalid = []
      const card_code = this.$refs[`input-${order_id}`][0].value // get the current input

      if (card_code == "") {
        this.sendFormsInvalid.push(order_id)
        return;
      }

      if (order_status == 'refused') {
        Swal.fire({
          title: "Warning !!",
          text: 'This order is refused by an admin, do this if you are sure you want to change order status and send the card.',
          icon: 'warning',
          confirmButtonText: "Confirm",
          showCancelButton: true
        }).then((res) => {
          if (res.isConfirmed) {
            this.$inertia.post(route('admin.sendCard'), {order_id, card_code}, {
              onFinish() {
                useToast('success', 'Order Successfully Refused.')
              },
              onError() {
                useToast('error', 'There was an error while sending card.')
              }
            })
          }
        })
      } else {
        Swal.fire({
          title: "Are you sure?",
          text: `Do this if you want to send the card (${card_code}) to the user.`,
          icon: 'info',
          confirmButtonText: "Confirm",
          showCancelButton: true
        }).then((res) => {
          if (res.isConfirmed) {
            this.$inertia.post(route('admin.sendCard'), {order_id, card_code}, {
              onFinish() {
                useToast('success', 'Order Successfully Refused.')
              },
              onError() {
                useToast('error', 'There was an error while sending card.')
              }
            })
          }
        })
      }

    },
    refuseCard(order_id) {
      Swal.fire({
        title: "Are you sure?",
        text: "If you are sure, please enter a comment for the user to let them know why did you do that.",
        icon: 'info',
        input: 'text',
        inputValidator: (text) => text == "" && "Please leave a comment to the user.",
        inputPlaceholder: 'Leave a comment here...',
        confirmButtonText: "Confirm",
        confirmButtonColor: "#dc3545",
        showCancelButton: true
      }).then((res) => {
        if (res.isConfirmed) {
          this.$inertia.post(route('admin.refuseCard'), {order_id}, {
            onFinish() {
              useToast('success', 'Order Successfully Refused.')
            },
            onError() {
              useToast('error', 'There was an error while sending card.')
            }
          })
        }
      })
    }
  }
}
</script>

<template>
  <AdminLayout title="Orders Requests">
    <div class="row">
      <div v-if="this.orders && this.orders.length > 0" v-for="order in this.orders" class=" bg-white p-3 rounded-3 d-flex gap-3 mb-3">
        <img :src="`/images/small/${order.card_details.image}`" alt="Image" class="rounded-3" width="121" height="150">
        <div class="w-100">
          <h5>{{ order.card_details.name }}</h5>
          <p class="m-0 d-flex justify-content-between">
            <span>User:
              <Link :href="route('admin.user', order.user.id)">{{ order.user.name }}</Link>
            </span>
            <span class="text-black-50">{{ order.order_date }}</span>
          </p>
          <p class="my-1">Price: <b>${{ order.card_details.price }}</b></p>
          <p class="mb-1">
            Status:
            <span v-if="order.order_status == 'paid'" class="bullet bullet-pending me-1"></span>
            <span v-if="order.order_status == 'recieved'" class="bullet bullet-recieved me-1"></span>
            <span v-if="order.order_status == 'refused'" class="bullet bullet-refused me-1"></span>
            <b>{{ orderStatus(order.order_status) }}</b>
          </p>
          <p v-if="order.order_status == 'recieved'">Order has <span class="text-success">successfully recieved</span> and
            the card code is <b class="text-black">{{ order.card_code }}</b></p>
          <div v-if="order.order_status != 'recieved'" class="input-box d-flex gap-2">
            <div style="flex: 1;">
              <input
              class="form-control w-100"
              :class="{ 'is-invalid': this.sendFormsInvalid.includes(order.order_id) }"
              placeholder="Card Code"
              :ref="`input-${order.order_id}`"
              >
            </div>
            <button class="btn btn-primary" @click="sendCard(order.order_id, order.order_status)">Send</button>
            <button v-if="order.order_status != 'refused'" class="btn btn-danger"
              @click="refuseCard(order.order_id)">Refuse</button>
          </div>
        </div>
      </div>
      <div v-else>
        No Orders Yet
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

.bullet {
  width: 10px;
  height: 10px;
  display: inline-block;
  border-radius: 50%;
}

.bullet-pending {
  background-color: #3B82F6;
}

.bullet-recieved {
  background-color: #34D399;
}

.bullet-refused {
  background-color: #DC2626;
}

</style>