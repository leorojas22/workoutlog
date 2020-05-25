import axios from 'axios';

axios.defaults.baseURL = "http://api.leoworkout.com";
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Content-Type'] = 'application/json';

