import axios from 'axios';

const login = (email, password) => {
    return axios.post("/user/login", {
        email,
        password
    });
};

export default login;
