import 'flowbite';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router/router';
import { initFlowbite } from 'flowbite';

const app = createApp(App);
app.use(initFlowbite)
 // Use the app instance to install the plugin
app.use(router);

app.mount('#app');

document.documentElement.classList.add('dark'); // Add this line to enable dark mode by default
