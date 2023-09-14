<script setup>
  import AdminLayout from '@/Layouts/AdminLayout.vue';

  const props = defineProps(['orders'])
  const orderStatus = (status) => {
    return status == "unpaid" ? "Pending" : "Recieved"
  }
</script>

<template>
  <AdminLayout title="Orders Requests">
    <div v-for="order in props.orders">
      <div class="order bg-white p-3 rounded-3 d-flex gap-3">
        <img :src="`/images/small/${order.card_details.image}`" alt="Image" class="rounded-3" width="121" height="150">
        <div>
          <h5>{{ order.card_details.name }}</h5>
          <p class="m-0 d-flex justify-content-between">
            <span>User: <a href="">Q{{ order.user.name }}</a></span>
            <span class="text-black-50">{{ order.order_date }}</span>
          </p>
          <p class="my-1">Price: <b>${{ order.card_details.price }}</b></p>
          <p class="mb-1">
            Status:
            <span class="bullet bullet-pending me-1"></span>
            <b>{{ orderStatus(order.order_status) }}</b>
          </p>
          <div class="input-box d-flex gap-2">
            <input class="form-control card-input" placeholder="Card Code">
            <button class="btn btn-primary">Send</button>
            <button class="btn btn-danger">Refuse</button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.order {
  width: fit-content;
}
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

.card-input {
  width: 300px;
  max-width: 300px;
}
</style>