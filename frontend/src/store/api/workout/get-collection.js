import axios from 'axios';

const getWorkoutCollection = (pageNumber = 1) => {
    return axios.get("/workout?page=" + pageNumber);
}

export default getWorkoutCollection;
