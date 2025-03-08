// import axios from 'axios';
// window.axios = axios;

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from "laravel-echo";
import SocketIO from "socket.io-client";

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname + ":6001",
});
