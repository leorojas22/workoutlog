import axios from 'axios';

const deleteExercise = (exerciseId) => {
    return axios.delete("/exercise/" + exerciseId);
}

export default deleteExercise;
