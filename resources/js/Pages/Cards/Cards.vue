<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import CategoryCard from '@/Components/CategoryCard.vue';

  const props = defineProps(['cards'])
</script>

<template>
    <AppLayout title="Available Cards">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Available Cards
        </h2>
      </template>

      <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 pb-6">
        <div class="cats mt-6">

          <CategoryCard v-if="cards.length > 0" v-for="card in cards" :name="card.category_name" :data="card.cards"/>
          <CategoryCard v-else :name="cards.category_name" :data="cards.cards"/>
        </div>
      </div>

    </AppLayout>
</template>