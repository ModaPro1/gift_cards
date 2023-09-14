<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  const props = defineProps(['orders'])

</script>

<template>
    <AppLayout title="Your Orders">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Your Orders
        </h2>
      </template>

      <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 pb-6">
        <div class="orders grid grid-cols-1 lg:grid-cols-2 gap-3 mt-5" v-if="props.orders && props.orders.length > 0">
          <div v-for="order in props.orders" class="order bg-white rounded-xl shadow-lg p-3 flex">
            <img class="rounded-xl" :src="`/images/small/${order.card_details.image}`" alt="">
            <div class="ml-5">
              <h2 class="font-bold text-xl uppercase">{{ order.card_details.name }}</h2>
              <h4 v-if="order.order_status == 'paid'">Status: <span class="bullet bullet-pending"></span> Pending</h4>
              <h4 v-if="order.order_status == 'recieved'">Status: <span class="bullet bullet-recieved"></span> Recieved</h4>
              <h4 v-if="order.order_status == 'refused'">Status: <span class="bullet bullet-refused"></span> Refused</h4>

              <h4>Price: <b>${{ order.card_details.price }}</b></h4>
              <hr class="my-2">
              <p v-if="order.order_status == 'paid'">You have bought <b>{{ order.card_details.price }}$ {{ order.card_details.name }}</b> card</p>

              <p v-if="order.order_status == 'paid'">
                The card is in <b class="text-blue-500">Pending</b> status so please be patient untill we review your order.
              </p>
              <p v-if="order.order_status == 'recieved'">
                Your card has successfully <b class="text-green-500">recieved</b>, Card Code:
              </p>
              <p v-if="order.order_status == 'recieved'">
                5185-2383-1923-5912
              </p>
              <p v-if="order.order_status == 'refused'">
                Your card purchase has <b class="text-red-500">refused</b> due to some circumstances, please contact us for more info.
              </p>
            </div>
          </div>
        </div>
        <div class="orders mt-5" v-else>
          <h2 class="font-bold text-xl">There's Not Orders Yet.</h2>
        </div>
      </div>

    </AppLayout>
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