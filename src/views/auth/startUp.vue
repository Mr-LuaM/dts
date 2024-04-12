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
            <v-window v-model="step">
                <v-window-item :value="1">
                    <div class="text-center">
                    <v-img
                    :width="200"
                    aspect-ratio="1/1"
                    src="/src/assets/logo/divine-word-college-of-calapan-logo.png"

                    class=" mx-auto"
                  ></v-img>
                    </div>
                  <v-card-text>
                  
                    <span class="text-h5  font-weight-bold text-uppercase" >
                        Welcome <br><span class="text-lowercase">to</span><br> Divine Ticketing System
                    </span>

                    <br>
                    <br>
                    <span class="text-caption  text-primary">
                        Swipe left to navigate
                        </span>
                  </v-card-text>
                </v-window-item>
          
                <v-window-item :value="2">
                    <div class="text-center">
                        <v-img
                        :width="200"
                        aspect-ratio="1/1"
                        src="/src/assets/logo/divine-word-college-of-calapan-logo.png"

                        class=" mx-auto"
                      ></v-img>
                        </div>
                      <v-card-text>
                      
                        <span class="text-caption  " >
    The Divine Ticketing System is an innovative solution designed to streamline the event management and ticketing process for the Divine Word College of Calapan. It facilitates seamless event creation, ticket sales, and attendee management, ensuring a smooth experience for both organizers and participants.
 
                        </span>
    
                
                      </v-card-text>
                </v-window-item>
          
                <v-window-item :value="3">
                    <div class="text-center">
                        <v-img
                        :width="200"
                        aspect-ratio="1/1"
                        src="/src/assets/logo/divine-word-college-of-calapan-logo.png"

                        class=" mx-auto"
                      ></v-img>
                        </div>
                      <v-card-text>
                      
                        <span class="text-caption">
                            "Experience hassle-free event planning and ticket purchasing with our Divine Ticketing System. Log in today and discover the ease of managing and attending events at DWCC."
                          </span> <br>
    <br>
    
    <v-btn
    block
    class="text-none mb-4"
    color="secondary"
    size="large"
    variant="flat"
    to="/login"  
  >
    Continue to Login
  </v-btn>
  
                      </v-card-text>
                </v-window-item>
              </v-window>
  </div>
 

  <v-card-actions class="justify-center">
    
    
    <v-item-group v-model="step" class="text-center" mandatory>
      <v-item v-for="n in 3" :key="`btn-${n}`" :value="n">
        <v-btn size="small" :variant="step === n ? 'tonal' : 'text'" :color="step === n ? 'primary' : ''"  icon="mdi-record" @click="() => step = n"></v-btn>
      </v-item>
    </v-item-group>
  </v-card-actions>
      </v-col>
    </v-row>
  </v-container>
  </template>
  <script setup>
  import { ref } from 'vue'

  const step = ref(1)


</script>