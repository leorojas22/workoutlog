import axios from 'axios';

const createWorkoutExerciseSet = (payload) => {
    return axios.post("/workout-exercise/" + payload.workoutExerciseId + "/set");
}

export default createWorkoutExerciseSet;
