<script setup>
  import { ref, reactive, computed } from 'vue'
  import DialogModal from './DialogModal.vue';
  import SecondaryButton from './SecondaryButton.vue';
  import { usePage } from '@inertiajs/vue3';
  import { toast } from 'vue3-toastify';
  import PrimaryButton from './PrimaryButton.vue';
  import axios from 'axios';

  const props = defineProps(['name', 'data']);
  const page = usePage()
  const user = ref(page.props.auth.user)
  
  const confirmModal = ref(false)
  const confirmButtonDisabled = ref(false)

  const selectedCard = reactive({
    id: '',
    name: '',
    price: ''
  })

  function cardClick(id, name, price) {
    if(!user.value) {
      // user not logged in
      toast.error('Please Login First.', {
        pauseOnHover: false
      });
    } else {
      // user logged in
      confirmModal.value = true
      selectedCard.id = id
      selectedCard.name = name
      selectedCard.price = price
    }
  }

  const confirmMessage = computed(() => {
    return `$${selectedCard.price} ${selectedCard.name} Card`
  })

  const priceClass = computed(() => {
    if(props.name == 'pubg mobile') {
      return 'text-white'
    }else if(props.name == 'roblox') {
      return 'text-red-500'
    }else {
      return ''
    }
  })

  const csrf_token = ref(document.querySelector('meta[name="csrf-token"]').getAttribute('content'))

  function submitForm(e) {
    confirmButtonDisabled.value = true
    axios.post('/checkout', {
      cardId: selectedCard.id
    })
    .then(data => {
      window.location.href = data.data.redirect
      // console.log(data);
    })
  }
</script>

<template>
  <section>
    <h3 class="text-gray-400 uppercase text-xl text-space tracking-widest">{{ props.name }}</h3>
    <div class="cat" v-for="cat in props.data">
      <button @click="cardClick(cat.id, cat.name, cat.price)" class="bg-white rounded-xl shadow-xl text-center">
        <div :class="priceClass" class="price">${{ cat.price }}</div>
        <img :src="`/images/small/${cat.image}`" class="w-100">
        <p class="text-gray-500 uppercase text-center">{{ cat.name }}</p>
      </button>
    </div>
  </section>

  <DialogModal :show="confirmModal" @close="confirmModal = false">
      <template #title>
          Payment Method
      </template>

      <template #content>
        Please confirm that your are buying <b>{{confirmMessage}}</b>.
        <br>
        This will redirect to a payment form, please fill it and click pay to proceed.

      </template>

      <template #footer>
          <SecondaryButton class="me-2" @click="confirmModal = false">
              Cancel
          </SecondaryButton>
          <form method="post" action="/checkout" @submit.prevent="submitForm">
            <input type="hidden" name="_token" :value="csrf_token" />

            <PrimaryButton
              :disabled="confirmButtonDisabled"
              :class="{'opacity-25': confirmButtonDisabled}"
              >
              Confirm
            </PrimaryButton>
          </form>
      </template>
  </DialogModal>
</template>

<style scoped>
.cats section:not(:first-child) {
  margin-top: 30px;
}

.cats section {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.cats section h3 {
  width: 100%;
}

.cats .cat {
  width: 150px;
  display: inline-block;
  position: relative;
}

.cats .cat button {
  transition: .3s;
  display: block;
  padding-bottom: 5px;
  width: 100%;
}

.cats .cat button:hover {
  transform: scale(1.1);
}

.cats .cat button img {
  width: 100%;
  margin-bottom: 5px;
}

.cats .cat .price {
  position: absolute;
  font-weight: bold;
  top: 10px;
  left: 10px;
}</style>