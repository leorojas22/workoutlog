import axios from 'axios';

const logout = () => {
    return axios.get("/logout");
};

export default logout;
