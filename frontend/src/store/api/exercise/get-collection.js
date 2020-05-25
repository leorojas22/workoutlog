import axios from 'axios';

const getExerciseCollection = () => {
    return axios.get("/exercise");
}

export default getExerciseCollection;
