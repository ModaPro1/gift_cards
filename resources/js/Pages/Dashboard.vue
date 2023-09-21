<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { Link } from '@inertiajs/vue3';
  import useToast from '@/hooks/toast.js'

  const props = defineProps(['categories', 'successMessage'])


  if(props.successMessage) {
    useToast('success', props.successMessage)
  }
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-3 flex intro-container">
                  <div class="imgs">
                    <img src="images/pubg.webp" class="img1" alt="PUBG"/>
                    <img src="images/razer.webp" class="img2" alt="RAZER"/>
                  </div>
                  <div class="mt-3 intro">
                    <h3 class="font-extrabold text-xl lg:text-4xl md:text-2xl">Welcome To <span class="text-amber-400">Gift Cards</span></h3>
                    <p class="text-gray-500 mt-2 lg:text-xl md:text-md sm:text-md">
                      Your one-stop destination for buying and selling gift cards! Whether you have unused gift cards collecting dust or you're searching for the perfect gift, we've got you covered. With a wide selection of retailers and brands to choose from, you can find the perfect gift card for any occasion Happy shopping and selling!
                    </p>
                  </div>
                </div>
            </div>
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
              <div class="categories-section">
                <h3 class="text-gray-400 uppercase text-center text-xl text-space tracking-widest">Categories</h3>
                <div class="cats flex justify-center flex-wrap gap-9 mt-6">
                  <Link v-for="cat in props.categories" :aria-label="cat.name" :href="`/cards${cat.url}`" class="cat bg-white rounded-xl shadow-xl">
                    <img alt="Card Image" width="120" height="151" :src="`images/small/${cat.image}`">
                    <p class="text-gray-500 uppercase text-center my-1">{{cat.name}}</p>
                  </Link>
                </div>
              </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
  .imgs {
    position: relative;
    width: 50%;
    margin-right: 30px;
  }
  .imgs img {
    width: 320px;
    position: absolute;
  }
  .imgs .img2 {
    right: 0;
    top: 70px;
  }

  .intro {
    flex: 1;
  }

  .categories-section {
    margin-top: 230px;
  }
  .cats .cat {
    transition: .3s;
  }
  .cats .cat:hover {
    transform: scale(1.1);
  }
  
  @media (max-width: 990px) {
    .imgs img {
      width: 250px;
    }
  }
  @media (max-width: 767px) {
    .intro-container {
      flex-direction: column;
    }
    .imgs {
      display: flex;
    }
    .imgs img:first-child {
      margin-right: 10px;
    }
    .imgs img {
      position: static;
    }
  }
</style>
