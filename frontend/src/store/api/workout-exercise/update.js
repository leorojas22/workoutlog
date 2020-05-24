import axios from 'axios';

const updateWorkoutExercise = (id, exerciseId) => {
    return axios.patch("/workout-exercise/" + id, {
        exerciseId
    });
};

export default updateWorkoutExercise;
