<template>
    <v-container
    class="fill-height"
    fluid
  >
    <v-row
      align="center"
      justify="center"
    >
      <v-col
        cols="12"
        sm="8"
        md="4"
      >
        <div class="text-center">
                <v-img
                :width="200"
                aspect-ratio="1/1"
                src="/src/assets/logo/divine-word-college-of-calapan-logo.png"
                class=" mx-auto"
              ></v-img>
              <v-card-text>
              
                <span class="text-h5  font-weight-bold" >
                Login
                </span>

                <br>
                <br>
                <v-form validate-on="lazy submit"  ref="form" @submit.prevent="login">

                    <v-text-field
                    v-model="email"
                      label="Email"
                      placeholder="Enter your email"
                      variant="underlined"
                      :rules="emailRule" 
                      autofocus
                    ></v-text-field>
                    <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">
                        <a></a>
                
                        <router-link
                        class="text-caption text-decoration-none text-primary"
                        to="/forgotpassword" 
                      >
                        Forgot login password?
                      </router-link>
                      </div>
                    <v-text-field
                    v-model="password"
                    label="Password"
        :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
        :type="visible ? 'text' : 'password'"
        placeholder="Enter your password"
        variant="underlined"
        @click:append-inner="visible = !visible" 
        :rules="[v => !!v || 'password is required']" 

      ></v-text-field>
      <br>
      <v-card
      class="mb-6"
      variant="tonal"
    >
      <v-card-text class="text-medium-emphasis text-caption">
        Warning: After 11 consecutive failed login attempts, you account will be temporarily locked for 15 minutes. If you must login now, you can also click "Forgot login password?" below to reset the login password.
      </v-card-text>
    </v-card>
    <v-btn
    block
    class="mb-8"
    color="secondary"
    size="large"
    :loading="loading"
    @click="login" 
  >
    Log In
  </v-btn>
  

  <v-card-text class="text-center">
    <router-link
      class="text-primary text-decoration-none text-caption"
      to="/signup"
      rel="noopener noreferrer"
  
    >
      Sign up now <v-icon icon="mdi-chevron-right"></v-icon>
    </router-link>
    
  </v-card-text>
                  </v-form>
              </v-card-text>
  </div>
 

  
      </v-col>
    </v-row>
    
    <DynamicSnackbar ref="snackbar" />
  </v-container>
  </template>
  <script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { emailRule, passwordRule } from '@/composables/validationRules'; // Assuming passwordRule is not used here
import DynamicSnackbar from '@/components/snackbars/dynamicSnack.vue';
import { useUserStore } from '@/stores/userStore';


// Define reactive references
const email = ref('');
const password = ref('');
const visible = ref(false);
const loading = ref(false);
const snackbar = ref(null); // Assuming you have a snackbar component ref
const form = ref(null);
const userStore = useUserStore();
const router = useRouter();

const login = async () => {
  const { valid } = await form.value.validate();
  if (valid) {
    try {
      loading.value = true;
      const response = await axios.post('/auth/login', {
        email: email.value,
        password: password.value,
      });

      if (response.status === 200) {
        userStore.login(response.data.token); // Store the token
      }
    } catch (error) {
      let errorMessage = 'An unknown error occurred. Please try again.';
      
      // Check for rate limiting error
      if (error.response && error.response.status === 429) {
        errorMessage = 'Too many login attempts. Please try again later.';
      } else if (error.response && error.response.data && error.response.data.error) {
        // Use the backend-provided error message if available
        errorMessage = error.response.data.error;
      } else if (error.response && error.response.data && error.response.data.messages) {
        // Joining all error messages into a single string, separated by spaces.
        errorMessage = Object.values(error.response.data.messages).join(' ');
      }

      if (snackbar.value) {
        snackbar.value.openSnackbar(errorMessage, 'error');
      }
    } finally {
      loading.value = false;
    }
  }
};


onMounted(() => {
  // Check if redirected with a specific message and status
  const { message, status } = router.currentRoute.value.query;
  
  // Handle logout due to token expiration
  if (status === 'tokenExpired') {
    snackbar.value.openSnackbar('You have been logged out due to inactivity. Please log in again.', 'info');
  }

  // Handle email verification messages
  if (message && status) {
    const displayMessage = decodeURIComponent(message);
    const snackbarColor = status === 'success' ? 'success' : 'error'; // Set color based on the status
    snackbar.value.openSnackbar(displayMessage, snackbarColor);
  }
});

</script>
