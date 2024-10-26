import 'flowbite';
import { createApp } from 'vue';
import App from './App.vue';
import store from './store/index';
import router from './router/router';
import { initFlowbite } from 'flowbite';

const app = createApp(App);

initFlowbite();

app.use(store);
 // Use the app instance to install the plugin
app.use(router);

app.mount('#app');

document.documentElement.classList.add('dark'); // Add this line to enable dark mode by default
