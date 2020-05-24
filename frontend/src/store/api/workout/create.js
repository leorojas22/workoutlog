import axios from 'axios';

const createWorkout = () => {
    return axios.post("/workout");
}

export default createWorkout;
