import axios from 'axios';

const getWorkout = (workoutId) => {
    return axios.get("/workout/" + workoutId);
}

export default getWorkout;
