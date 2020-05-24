import axios from 'axios';

const getUserAuth = () => {
    return axios.get("/user/auth");
};

export default getUserAuth;
