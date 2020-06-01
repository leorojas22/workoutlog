import axios from 'axios';

const deleteWorkoutExercise = (workoutExerciseId) => {
    return axios.delete("/workout-exercise/" + workoutExerciseId);
};

export default deleteWorkoutExercise;
