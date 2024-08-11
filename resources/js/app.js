// import './bootstrap';
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "bootstrap";

import { createApp } from 'vue';
import UserList from './components/UserList.vue';

const app = createApp({});

app.component('user-list', UserList);

app.mount("#app");