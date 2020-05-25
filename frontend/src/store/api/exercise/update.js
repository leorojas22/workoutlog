import axios from 'axios';

const updateExercise = (payload) => {
    return axios.patch("/exercise/" + payload.id, {
        name         : payload.name,
        statTemplate : payload.statTemplate,
        sumStat      : payload.sumStat
    });
};

export default updateExercise;
