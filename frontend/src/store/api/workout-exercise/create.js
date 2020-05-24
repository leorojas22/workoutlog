import axios from 'axios';

const createWorkoutExercise = (workoutId, exerciseId) => {
    return axios.post("/workout/" + workoutId + "/exercise/" + exerciseId);
};

export default createWorkoutExercise;
