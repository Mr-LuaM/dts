<template>
    <v-container
    class="fill-height  "
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
      </div>
    <v-card
      class="py-8 px-6 text-center mx-auto ma-4 bg-background rounded-lg"
   
  flat
      max-width="400"
      width="100%"
    >
      <h3 class="text-h6 mb-4">Verify Your Account</h3>
  
      <div class="text-body-2">
        We sent a verification code to {{email}} <br>
  
        Please check your email and paste the code below.
      </div>
  
      <v-sheet color="background">
        <v-otp-input
          v-model="otp"
          variant="underlined"
          autofocus
         
        ></v-otp-input>
      </v-sheet>
  
      <v-btn
      @click="verifyOTP"
      class="my-4"
      color="secondary"
      height="40"
      text="Verify"
      variant="flat"
      width="70%"
      :loading="loading"
    >
      Verify
    </v-btn>
      <div class="text-caption">
       Token Expires in 10 minutes!
      </div>
      
      <div class="text-caption">
        Didn't receive the code? <a href="#" @click.prevent="resendOTP" class="text-primary text-decoration-none text-caption">Resend</a>

      </div>
      <br>
      <router-link
      class="text-primary text-decoration-none text-caption"
      to="/signup"
  
    >

    <v-icon icon="mdi-chevron-left"></v-icon>    Back to Sign up 
    </router-link>
    
      
    </v-card>
    <DynamicSnackbar ref="snackbar" />
</v-col>
</v-row>
</v-container>
  </template>
  <script setup>
  import { onMounted, shallowRef, ref } from 'vue'; // Import ref here
  import { useRouter, useRoute } from 'vue-router';
  import DynamicSnackbar from '@/components/snackbars/dynamicSnack.vue';
  import axios from 'axios'; // Ensure axios is imported if you're using it for HTTP requests
  import {jwtDecode} from 'jwt-decode';

  
  let email = ref('');
  const otp = shallowRef('');
  const loading = ref(false); // Use ref for reactive state
  const router = useRouter();
  const route = useRoute();
  const snackbar = ref(null);
  

  onMounted(() => {
  // Extract the JWT token from the query parameter
  const token = route.query.token;
  if (token) {
    try {
      // Decode the JWT to get the email
      const decoded = jwtDecode(token);
      email.value = decoded.email;
    } catch (error) {
      console.error('Invalid token:', error);
      // Handle the error, e.g., show an error message or redirect
    }
  } else {
    // Handle the absence of a token, e.g., show an error message or redirect
  }
});

const verifyOTP = async () => {
    loading.value = true;
    try {
      const response = await axios.post('/auth/verify-otp', {
        email: email.value,
        otp: otp.value,
      });
  
      if (response.status === 200) {
        let successMessage = 'Verification successful, redirecting to login.';
        // Check if the email was already verified (assuming backend sends this data)
        if (response.data.message === 'Email already verified.') {
          successMessage = 'Email already verified, redirecting to login.';
        }
        snackbar.value.openSnackbar(successMessage, 'success');
        setTimeout(() => {
          router.push({ name: 'login' });
        }, 2000);
      } else {
        // This block might not be needed if backend uses HTTP status codes correctly
        snackbar.value.openSnackbar('Wrong verification code.', 'error');
      }
    } catch (error) {
      let errorMessage = 'Error verifying OTP. Please try again.';
      if (error.response && error.response.data && error.response.data.message) {
        // Assuming the backend sends a 'message' field for errors
        errorMessage = error.response.data.message;
      }
      snackbar.value.openSnackbar(errorMessage, 'error');
    } finally {
      loading.value = false;
    }
};


  
  const resendOTP = async () => {
    loading.value = true; // Indicate loading
    try {
        const response = await axios.post('/auth/resend-otp', { email: email.value });
  
      if (response.status === 200) {
        const message = response.data.message || 'OTP resent successfully.';
        snackbar.value.openSnackbar(message, 'info');
      } else {
        const errorMessage = response.data.message || 'Failed to resend OTP. Please try again.';
        snackbar.value.openSnackbar(errorMessage, 'error');      }
    } catch (error) {
      let errorMessage = 'Error resending OTP. Please try again.';
      if (error.response && error.response.data && error.response.data.message) {
        // Use the backend-provided error message if available
        errorMessage = error.response.data.message;
      }
      snackbar.value.openSnackbar(errorMessage, 'error');    } finally {
      loading.value = false; // Reset loading indicator
    }
  };
  </script>
  