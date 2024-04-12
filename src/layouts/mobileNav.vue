<template>

  <v-app>
    <v-main>
        <router-view></router-view>
       
   
 </v-main>
  <v-bottom-navigation v-model="value" class="overflow-visible" active color="primary" grow :elevation="0" density="default">          <v-btn to="/user/dashboard" variant="flat">
          <v-icon>mdi-home</v-icon>
          Home
        </v-btn>

        <v-btn to="/user/events" variant="flat">
          <v-icon>mdi-ticket</v-icon>
          Tickets
        </v-btn>

        <v-btn to="/user/qr-code" >
        <v-img
              src="/src/assets/icons/qr-code.png"
              alt="John"
              aspect-ratio="1/1"
              :width="40"
              cover
            ></v-img>
         
        </v-btn>

        <v-btn to="/user/transaction" variant="flat">
          <v-icon>mdi-transaction</v-icon>
          Transactions
        </v-btn>
        <v-btn to="/profile" variant="flat">
          <v-icon>mdi-account</v-icon>
          Profile
        </v-btn>
        

        
      </v-bottom-navigation>
 </v-app>


  

  </template>
  


<script setup>
import { ref, watch } from 'vue';
import { useRoute } from 'vue-router';

const value = ref(0); // Default active index
const route = useRoute();

const routeToIndexMap = {
  '/user/dashboard': 0,
  '/user/events': 1,
  '/user/qr-code': 2,
  '/user/transaction': 3,
  '/profile': 4,
};


watch(() => route.path, (newPath) => {
  console.log('New path:', newPath); // Debugging line
  value.value = routeToIndexMap[newPath] ?? null;
});
import { onMounted } from 'vue';

onMounted(() => {
  value.value = routeToIndexMap[route.path] ?? null;
});

</script>
