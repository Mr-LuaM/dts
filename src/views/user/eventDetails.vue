<template>

        <v-toolbar   class="bg-primary">
  
        <v-toolbar-title class="font-weight-black text-h6">
          <!-- Use router-link to navigate back -->
          <router-link :to="{ name: 'userDashboard' }">
            <v-icon class="mt-n1" color="white">mdi-chevron-left</v-icon>
          </router-link>
          Discover
        </v-toolbar-title>  
        <v-spacer></v-spacer>
  
        
      </v-toolbar>

 
  <v-container class="my-5">
    
    <v-row justify="center">
      <v-col cols="12" sm="10" md="8">
        <v-card flat class="mb-4 mt-n7 ml-n2">
          <v-card-title class="text-secondary font-weight-bold" >{{ event.title }}</v-card-title>
          <v-card-text>
            <div >{{ event.description }}</div>
            <div><strong>Location:</strong> {{ event.location }}</div>
            <div><strong>Date & Time:</strong> {{ event.start_time }} to {{ event.end_time }}</div>
          </v-card-text>
        </v-card>

        <h2 class="text-center mb-4">Select Your Ticket</h2>
        <v-radio-group v-model="selectedTicket" class="ticket-selection">
          <v-row>
            <v-col v-for="ticket in tickets" :key="ticket.id" cols="12" md="4">
              <v-card
                :class="`pa-3 ticket-card ${selectedTicket === ticket.id ? 'selected' : ''}`"
                @click="selectTicket(ticket.id)"
                elevation="2"
                hover
              >
                <v-card-title>{{ ticket.type }}</v-card-title>
                <v-card-subtitle>${{ ticket.price }}</v-card-subtitle>
                <v-card-text>
                  <div>Seat: {{ ticket.seat_number || "N/A" }}</div>
                  <div>{{ ticket.description }}</div>
                </v-card-text>
                <v-radio :value="ticket.id" class="d-none"></v-radio>
              </v-card>
            </v-col>
          </v-row>
        </v-radio-group>
        
        <v-btn large color="primary" class="mt-5" @click="buyTicket">Buy Selected Ticket</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      event: {
        title: 'Concert A',
        description: 'A fantastic evening of music.',
        location: 'Downtown Arena, City Name',
        start_time: 'June 25, 2024, 7:00 PM',
        end_time: 'June 25, 2024, 10:00 PM',
      },
      selectedTicket: '',
      tickets: [
      {
        id: 't1',
        type: 'general',
        price: 30.00,
        seat_number: 'G1',
        description: 'General admission with access to standard seating.',
      },
      {
        id: 't2',
        type: 'vip',
        price: 50.00,
        seat_number: 'V1',
        description: 'VIP pass with priority seating and complimentary drinks.',
      },
      {
        id: 't3',
        type: 'vvip',
        price: 100.00,
        seat_number: 'VV1',
        description: 'VVIP experience with backstage access and meet & greet opportunities.',
      },
    ],
    };
  },
  filters: {
    capitalize(value) {
      if (!value) return '';
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
    currency(value) {
      return `$${parseFloat(value).toFixed(2)}`;
    }
  },
  methods: {
    selectTicket(ticketId) {
      this.selectedTicket = ticketId;
    },
    buyTicket() {
      alert(`Buying ticket: ${this.selectedTicket}`);
      // Here, implement the logic to proceed with the purchase
    }
  }
};
</script>
<style scoped>
.selected {
  border: 2px solid #ffab40; /* Highlight color for selected ticket */
}

.ticket-card:hover {
  cursor: pointer;
  transform: scale(1.03);
  transition: transform 0.3s ease;
}

.d-none {
  display: none !important;
}
</style>
