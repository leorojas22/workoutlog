import axios from 'axios';

const deleteWorkout = (workoutId) => {
    return axios.delete("/workout/" + workoutId);
};

export default deleteWorkout;
