// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { md3 } from 'vuetify/blueprints'


// Vuetify
import { createVuetify } from 'vuetify'
import * as components from "vuetify/components";
import * as labsComponents from "vuetify/labs/components";


const light = {
  dark: false,
  colors: {
    background: '#FFFFFF',
    surface: '#FFFFFF',
    'surface-bright': '#FFFFFF',
    'surface-light': '#F5F5F5',
    'surface-variant': '#E0E0E0',
    'on-surface-variant': '#424242', // Contrast on lighter surfaces
    primary: '#046307', // A shade of green from the logo
    'primary-darken-1': '#034F05', // Slightly darkened green for contrast
    secondary: '#121212', // Keeping secondary color as white, assuming logo's background
    'secondary-darken-1': '#E0E0E0', // Slightly darkened secondary color for contrast
    error: '#B00020',
    info: '#2196F3',
    success: '#4CAF50',
    warning: '#FB8C00',
  },
};

const dark = {
  dark: true,
  colors: {
    background: '#121212',
    surface: '#1E1E1E',
    'surface-bright': '#2E2E2E',
    'surface-light': '#232323',
    'surface-variant': '#424242',
    'on-surface-variant': '#E0E0E0', // Light text on dark surfaces
    primary: '#04D15F', // A lighter green that stands out on dark backgrounds
    'primary-darken-1': '#039748', // A darker shade for contrast
    secondary: '#FFFFFF', // White color for secondary
    'secondary-darken-1': '#E0E0E0', // A gray for darker secondary elements
    error: '#CF6679',
    info: '#64B5F6',
    success: '#81C784',
    warning: '#FFB74D',
  },
};





export default createVuetify({
  // https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
  components: {
    ...components,
    ...labsComponents,
  },
  blueprint: md3,
  icons: {
    defaultSet: "mdi",
  },
  theme: {
    themes: {
      dark,
      light,
    },
  },
});
