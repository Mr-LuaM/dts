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
            <v-row justify="center">
                <v-col cols="auto">
            <v-img
            :width="200"
            aspect-ratio="16/9"
            cover
            
            src="/src/assets/logo/divine-word-college-of-calapan-logo.png"
          ></v-img>
          </v-col>
          </v-row>
              <v-card-text>
              
                <span class="text-h5  font-weight-bold" >
                Register an account
                </span>

                <br>
                <br>
                <v-form validate-on="blur" ref="form"  @submit.prevent="signup" class="px-0"> 
                    <v-row>
                        <v-col cols="12">
                          <v-text-field
                            v-model="schoolID"
                            label="School ID"
                            variant="underlined"
                            :rules="[v => !!v || 'School ID is required']" 
                            autofocus
                            append-inner-icon="mdi-card-account-details-outline"
                          ></v-text-field>
                        </v-col>
                     </v-row>
                    <v-row>
                    <v-col cols="6" >
                      <v-text-field
                        v-model="fname"
                        label="First Name"
                        variant="underlined"
                        :rules="nameRule" 
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" >
                      <v-text-field
                        v-model="lname"
                        label="Last Name"
                        variant="underlined"
                        :rules="nameRule" 
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-text-field
    v-model="email"
     label="Email"
     variant="underlined"
     :rules="emailRule"  
   ></v-text-field>
               
    <v-text-field
                  v-model="password"
                  label="Password"
      :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
      :type="visible ? 'text' : 'password'"

      variant="underlined"
      @click:append-inner="visible = !visible"
      hint= "Password must be at least 8 characters with a combination of 1 uppercase, 1 lowercase, 1 digit, and 1 special character"
      persistent-hint
      :rules="passwordRule" 
      class="mb-2"
    ></v-text-field>
    
   <v-text-field
   v-model="confirmPassword"
   label="Confirm Password"
:append-inner-icon="visible2 ? 'mdi-eye-off' : 'mdi-eye'"
:type="visible2 ? 'text' : 'password'"

variant="underlined"
@click:append-inner="visible2 = !visible2"
:rules="confirmPasswordRules" 
></v-text-field>
<v-row>
  <v-col cols="6" >
    <v-text-field
    v-model="birthdate"
     label="Birthdate"
     variant="underlined"
     :rules="birthdateRule"  
     type="date"
   ></v-text-field>
  </v-col>
  <v-col cols="6" >
    <v-autocomplete
      v-model="sex"
      label="Sex"
      :items="['Male', 'Female', 'Prefer not to say']"
      variant="underlined"
      :rules="sexRule"  
    ></v-autocomplete>
  </v-col>
</v-row>
 <v-text-field
    v-model="contactNumber"
     label="Contact Number"
     variant="underlined"
     :rules="contactNumberRule"  
   ></v-text-field>
                  
    <v-btn
    block
    class=" mt-2"
    color="secondary"
    size="large"
    @click="signup"
    :loading="loading"
   
  >
    Sign Up
  </v-btn>

  <v-card-text class="text-center">
    <router-link
      class="text-primary text-decoration-none text-caption"
      to="/login"
      rel="noopener noreferrer"
  
    >
      Login <v-icon icon="mdi-chevron-right"></v-icon>
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
  import { ref } from 'vue';
  import {  emailRule,
  passwordRule,
  nameRule,
  birthdateRule,
  sexRule,
  contactNumberRule,
  confirmPasswordRule
   } from '@/composables/validationRules'; // Directly import the rule
   import axios from 'axios';
   import { useRouter } from 'vue-router';

   import DynamicSnackbar from '@/components/snackbars/dynamicSnack.vue';

const form = ref(null);
const snackbar = ref(null);
const loading = ref(false)

const schoolID = ref('');
const fname = ref('');
const lname = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const birthdate = ref('');
const sex = ref(null);
const contactNumber = ref('');
const visible = ref(false);
const visible2 = ref(false);

const confirmPasswordRules = confirmPasswordRule(password);
const router = useRouter();

// Function to submit form data
const signup = async () => {
  if (await form.value.validate()) {
    try {
      const formData = {
        schoolID: schoolID.value,
        fname: fname.value,
        lname: lname.value,
        email: email.value,
        password: password.value,
        birthdate: birthdate.value,
        sex: sex.value,
        contact_number: contactNumber.value,
      };
      loading.value = true;

      const response = await axios.post('/auth/signup', formData);

      if (response.status === 201 || response.status === 202) {
        // Check if needs verification or successfully created and returned token
        if (response.data.token) {
          // If a token is returned, redirect to verification with the token
          router.push({ name: 'verification', query: { token: response.data.token } });
        } else if (response.data.needsVerification) {
          // Handle the case where the account exists but isn't verified
          // This could include redirecting to a verification page, or showing a message to check their email
          snackbar.value.openSnackbar(response.data.message, 'info');
          router.push({ name: 'verification', query: { token: response.data.token } });

        }
      }
    }catch (error) {
  let errorMessage = 'An unknown error occurred. Please try again.';
  if (error.response) {
    if (error.response.status === 422) {
      // Validation errors
      const errors = Object.values(error.response.data.errors).map(err => err.join(' ')).join('. ');
      errorMessage = errors;
    } else if (error.response.status === 429) {
      // Rate limiting errors
      errorMessage = 'Too many attempts. Please try again later.';
    } else if (error.response.data) {
      // Other errors with a custom message
      errorMessage = error.response.data.error || error.response.data.message || errorMessage;

      // Append details if available
      if (error.response.data.details) {
        errorMessage += ` Details: ${error.response.data.details}`;
      }
    }
  }

  // Display the error message using the snackbar
  if (snackbar.value) {
    snackbar.value.openSnackbar(errorMessage, 'error');
  }
} finally {
  loading.value = false;
}

  }
};

  </script>
  