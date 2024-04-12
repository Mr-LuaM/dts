import { createApp } from 'vue'
import { createPinia } from 'pinia'
import piniaPluginPersist from 'pinia-plugin-persist';

import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import axios from 'axios'


// axios.defaults.baseURL = "https://app.chalsim.online/"
axios.defaults.baseURL = "http://127.0.0.1:8000/api"

const app = createApp(App)


const pinia = createPinia();
pinia.use(piniaPluginPersist);

app.use(pinia)
app.use(router)
app.use(vuetify)
app.mount('#app')
