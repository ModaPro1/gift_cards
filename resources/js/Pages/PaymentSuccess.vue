<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';


</script>

<template>
    <AppLayout title="Payment Success">
      <div class="success-container">
        <div class="card bg-white rounded">
          <img src="/images/check-mark.png" alt="">
          <h2>Payment Success</h2>
          <p>Your Payment has successfully completed, please check <Link href="/orders">your orders</Link> to see the payment details.</p>
        </div>
      </div>
    </AppLayout>
</template>

<style scoped>
.success-container {
  margin-top: 200px;
  text-align: center;
}
.card {
  width: fit-content;
  margin: auto;
  padding: 40px;
  width: 400px;
  text-align: center;
}
img {
  width: 150px;
  margin: 0 auto;
}
a {
  text-decoration: underline;
  color: inherit;
}
h2 {
  font-weight: bold;
  color: #009045;
  font-size: 20px;
  margin-bottom: 15px;
}
@media (max-width:767px) {
  .card {
    width: 100%;
  }
}
</style>