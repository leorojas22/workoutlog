import axios from 'axios';

const getWorkoutExerciseHistory = (workoutExerciseId) => {
    return axios.get("/workout-exercise/" + workoutExerciseId + "/history");
};

export default getWorkoutExerciseHistory;
