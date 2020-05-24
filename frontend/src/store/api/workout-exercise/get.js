import axios from 'axios';

const getWorkoutExercise = (workoutExerciseId) => {
    return axios.get("/workout-exercise/" + workoutExerciseId);
};

export default getWorkoutExercise;
