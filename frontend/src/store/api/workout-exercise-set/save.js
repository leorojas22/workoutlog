import axios from 'axios';

const saveWorkoutExerciseSet = (workoutExerciseId, payload) => {

    const setId = parseInt(payload.id);
    const requestMethod = setId ? "patch" : "post";

    return axios[requestMethod]("/workout-exercise/" + workoutExerciseId + "/set" + (setId ? "/" + setId : ""), {
        statNotes: payload.statNotes,
        notes: payload.notes
    });
}

export default saveWorkoutExerciseSet;
