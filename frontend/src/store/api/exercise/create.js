import axios from 'axios';

const createExercise = (payload) => {
    return axios.post("/exercise", {
        name         : payload.name,
        statTemplate : payload.statTemplate,
        sumStat      : payload.sumStat
    });
}

export default createExercise;
