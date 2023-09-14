<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import CategoryCard from '@/Components/CategoryCard.vue';

  const props = defineProps(['cards'])
  const googlePlayCards = props.cards.filter(card => card.type == 'googleplay')
  const pubgCards = props.cards.filter(card => card.type == 'pubg')
  const robloxCards = props.cards.filter(card => card.type == 'roblox')

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

          <CategoryCard
            v-if="googlePlayCards && googlePlayCards.length > 0"
            name="google play"
            :data="googlePlayCards"
            />

            <CategoryCard
            v-if="pubgCards && pubgCards.length > 0"
            name="pubg mobile"
            :data="pubgCards"
            />

            <CategoryCard
            v-if="robloxCards && robloxCards.length > 0"
            name="roblox"
            :data="robloxCards"
            />

        </div>
      </div>

    </AppLayout>
</template>