<template>
    <v-card class="fill-height" flat>
 
            <v-col
            cols="12"
            sm="8"
            md="4"
            class="mx-auto pb-0 mb-0 "
            
          >
         <v-toolbar class="bg-surface mt-n3 py-1 ">  
        <div >
            <v-img
            :width="45"
    class="mx-auto"
            aspect-ratio="1/1"
            src="/src/assets/logo/divine-word-college-of-calapan-logo.png"
           
          ></v-img>
        </div>
            <v-toolbar-title class="font-weight-black  text-primary text-h6 ml-2">DTS</v-toolbar-title>
            <v-spacer></v-spacer>
           
            <v-btn variant="flat" color="secondary"  size="x-small" @click="userStore.logout">
               Logout 
            </v-btn></v-toolbar>
                <v-card variant="tonal" class="bg-primary mb-3">
                    <v-row>
                      <!-- Left column (70%) -->
                      <v-col cols="7">
                        <v-card-item>
                          <v-card-title>Welcome {{ userDetails.fname }} {{ userDetails.lastname }}</v-card-title>
                          <v-card-subtitle>{{ userDetails.email }}</v-card-subtitle>
                        </v-card-item>
                        <v-card-text>
                          DWCC Ticketing System.
                          <br/>
                          Browse and Enjoy!!!
                        </v-card-text>
                      </v-col>
                  
                      <!-- Right column (30%) with image -->
                      <v-col cols="5">
                        <v-img
                          src="/src/assets/icons/ticket.png"
                          height="100%"
                          :width="155"
                          class="mx-auto"
                                  aspect-ratio="1/1"
                        ></v-img>
                      </v-col>
                    </v-row>
                  </v-card>
                  
        
            <v-row justify="center">
                <v-col cols="4" class="d-flex align-stretch mb-2">
                    <v-card @click="() => router.push('dashboard')" class="d-flex flex-column justify-center align-center" flat>
                      <v-card-title class="text-center">{{ ticketCounts.upcoming }}</v-card-title>
                      <v-card-subtitle class="text-center">Upcoming Events</v-card-subtitle>
                    </v-card>
                  </v-col>
                  
                  <v-col cols="4" class="d-flex align-stretch mb-2">
                    <v-card @click="() => router.push('past-events')" class="d-flex flex-column justify-center align-center" flat>
                      <v-card-title class="text-center">{{ ticketCounts.past }}</v-card-title>
                      <v-card-subtitle class="text-center">Past Events</v-card-subtitle>
                    </v-card>
                  </v-col>
                  
                  <v-col cols="4" class="d-flex align-stretch mb-2">
                    <v-card @click="() => router.push('pending-tickets')" class="d-flex flex-column justify-center align-center" flat>
                      <v-card-title class="text-center">{{ ticketCounts.pending }}</v-card-title>
                      <v-card-subtitle class="text-center">Courses Finished</v-card-subtitle>
                    </v-card>
                  </v-col>
                  
              </v-row>
              <v-divider></v-divider>
              <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between mt-3">
                <p  class="text-caption font-weight-black  text-secondary mb-3">Events News Update</p>
        
                <router-link
                class="text-caption text-decoration-none text-primary mb-3"
                to="petGallery" 
              >
                Buy Tickets<v-icon icon="mdi-chevron-right"></v-icon>
              </router-link>
              </div>
              <v-divider></v-divider>
              <!-- Skeleton Loader for Loading State -->
              
              <v-skeleton-loader v-if="loadingEvents"  type="card" class="mt-4"></v-skeleton-loader> 
              <v-skeleton-loader v-if="loadingEvents"  type="card" class="mt-4"></v-skeleton-loader> 
             

              <v-container v-else class="scrollable-card mb-6 py-3 bg-on-surface-variant" border :elevation="2">                <v-card 
                v-for="event in events" :key="event.id"  
                class="mx-auto mb-4"
                max-width="344"
                variant="flat"
                >
                <v-img :src="event.image || '/src/assets/icons/no-img.jpg' " height="150px" cover></v-img>
        
                    <v-card-title>{{ event.title }}</v-card-title>
                    <v-card-subtitle>{{ event.start_time }} - {{ event.end_time }}</v-card-subtitle>
                    <v-card-text>
                        <span :class="['font-weight-bold', ticketClass(event.availableTickets)]">
                            Available Tickets: {{ event.availableTickets }}
                          </span>
                          
                      </v-card-text>
                      <v-divider></v-divider>
                    <v-card-actions>
                        <v-btn
                        color="orange-lighten-2"
                        variant="text"
                        :to="{ name: 'eventDetails', params: { id: event.id } }"
                      >
                        Explore
                      </v-btn>
                  
                        <v-spacer></v-spacer>
                  
                        <v-btn
                          :icon="show ? 'mdi-chevron-up' : 'mdi-chevron-down'"
                          @click="show = !show"
                        ></v-btn>
                      </v-card-actions>
        
                    <v-expand-transition>
                        <div v-show="show">
                            <v-divider></v-divider>
                            <v-card-text>{{ event.description }}</v-card-text>
 <!-- Display tickets per type -->
 <v-card-text v-for="(details, type) in event.ticketsPerType" :key="type" class="pt-2">
    <v-chip :color="getChipColor(type)" size="x-small">{{ type.toUpperCase() }}</v-chip>
    <span>: {{ details.available }},</span>
  </v-card-text>
  
                        </div>
                      </v-expand-transition>
                      <v-divider></v-divider>
                  </v-card>
              </v-container>
              
              </v-col>
              
            </v-card>
            
</template>
<script setup>
import { useRoute, useRouter } from 'vue-router'; // Import useRoute and useRouter for navigation
import { useUserStore } from '@/stores/userStore'; // Adjust the path to your store as needed
import { ref, onMounted } from 'vue';
import axios from 'axios';
const loadingEvents = ref(true); // true when events are being fetched


// Use the store
const userStore = useUserStore();
const userDetails = userStore.userDetails;
const router = useRouter(); // Instantiate useRouter for navigation
const show = ref(false);
const ticketCounts = ref({
  upcoming: 0,
  past: 0,
  pending: 0,
});
const events = ref([]); // Added to store events

const ticketClass = (availableTickets) => {
  if (availableTickets >= 10) return 'text-success';
  if (availableTickets > 0) return 'text-warning';
  return 'text-error';
};


const getChipColor = (type) => {
  const colorMap = {
    'vvip': 'primary',
    'vip': 'success',
    'general': 'warning',
  };
  return colorMap[type.toLowerCase()] || 'grey'; // Default color if type not found
};

// Function to fetch ticket counts
const fetchTicketCounts = async () => {
  try {  loadingEvents.value = true;
    const response = await axios.get('/user/ticketCounts', {
      headers: { Authorization: `Bearer ${userStore.token}` }
    });

    const { upcoming, past, pending } = response.data;

    ticketCounts.value.upcoming = upcoming;
    ticketCounts.value.past = past;
    ticketCounts.value.pending = pending;
  } catch (error) {
    console.error('Error fetching ticket counts:', error);
  }
};

// Newly added function to fetch events
const fetchEvents = async () => {
  try {
    const response = await axios.get('/user/fetchEvents', {
      headers: { Authorization: `Bearer ${userStore.token}` }
    });

    // Assuming the response directly contains an array of events
    events.value = response.data;loadingEvents.value = false;
  } catch (error) {
    console.error('Error fetching events:', error);loadingEvents.value = false;
  }
};

onMounted(() => {
  fetchTicketCounts();
  fetchEvents(); // Call fetchEvents on component mount
});
</script>

<style scoped>
.scrollable-card {
    transform: none;
    scale: 1;
  height: 30.3rem; /* Adjust the height as needed */
  overflow-y: auto;
  
}
</style>