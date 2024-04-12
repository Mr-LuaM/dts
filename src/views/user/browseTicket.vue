<template>
    <v-container class="my-5">
      <v-row justify="center">
        <v-col cols="12" sm="10" md="8">
          <h2 class="text-center mb-4">Select Your Ticket</h2>
          <v-radio-group v-model="selectedTicket" class="ticket-selection">
            <v-row>
              <v-col v-for="ticket in tickets" :key="ticket.id" cols="12" md="4">
                <v-card 
                  :class="`pa-3 ticket-card ${selectedTicket === ticket.id ? 'selected' : ''}`" 
                  @click="selectTicket(ticket.id)"
                >
                  <v-card-title>{{ ticket.eventName }}</v-card-title>
                  <v-card-subtitle>{{ ticket.duration }} minutes</v-card-subtitle>
                  <v-card-text>
                    <v-icon left>mdi-cash</v-icon> ${{ ticket.price }}
                  </v-card-text>
                  <v-radio
                  :value="ticket.id"
                  :label="`Select ${ticket.eventName}`"
                  color="orange darken-4"
                  hide-details
                ></v-radio>
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
        selectedTicket: '',
        tickets: [
          { id: 't1', eventName: 'Concert A', duration: 60, price: 30 },
          { id: 't2', eventName: 'Concert B', duration: 90, price: 40 },
          { id: 't3', eventName: 'Concert C', duration: 120, price: 50 },
        ],
      };
    },
    methods: {
      selectTicket(ticketId) {
        this.selectedTicket = ticketId;
      },
      buyTicket() {
        alert(`Buying ticket: ${this.selectedTicket}`);
        // Implement your logic to proceed to purchase
      }
    }
  };
  </script>
  

  