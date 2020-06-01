import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

// Api Calls
import Api from '@/store/api';

import getCopy from '@/helpers/get-copy';

// Modules
import exerciseModule from '@/store/exercise';
const modules = {
    exercise: exerciseModule
}

// Mutations
const SET_USER              = "Set User";
const SET_AUTH_TOKEN        = "Set Auth Token";
const SET_ERROR_MESSAGE     = "Set Error Message";
const SET_WORKOUTS          = "Set Workouts";
const SET_WORKOUT_EXERCISES = "Set Workout Exercises";
const SET_WORKOUT_EXERCISE_HISTORY = "Set Workout Exercise History";

// State
const state = {
    user: {
        id: 0,
        email: ""
    },
    authToken: "",
    errorMessage: "",
    workouts: [],
    workoutExercises: [],
    workoutExerciseHistory: []
};


// Mutations
const mutations = {
    [SET_USER](state, user) {
        state.user = {
            id: typeof user.id !== 'undefined' ? user.id : 0,
            email: typeof user.email !== 'undefined' ? user.email : ""
        };
    },
    [SET_AUTH_TOKEN](state, authToken) {
        state.authToken = authToken;
    },
    [SET_ERROR_MESSAGE](state, errorMessage) {
        state.errorMessage = errorMessage;
    },
    [SET_WORKOUTS](state, workouts) {
        state.workouts = workouts;
    },
    [SET_WORKOUT_EXERCISES](state, workoutExercises) {
        state.workoutExercises = workoutExercises;
    },
    [SET_WORKOUT_EXERCISE_HISTORY](state, workoutExerciseHistory) {
        state.workoutExerciseHistory = workoutExerciseHistory;
    }
};

// Actions
const actions = {
    setUser({ commit }, user) {
        commit(SET_USER, user);
        return Promise.resolve();
    },
    setAuthenticationValues({ commit, dispatch }) {

        // Clear any existing error
        commit(SET_ERROR_MESSAGE, "");

        return Api.user.auth().then(response => {
            // Save to store
            commit(SET_AUTH_TOKEN, response.data.token);

            // Update axios to send on every request
            axios.defaults.headers.common['X-AUTH-TOKEN'] = response.data.token;

            if(response.data.user)
            {
                commit(SET_USER, {
                    id: response.data.user.id,
                    email: response.data.user.email
                });

                // If logged in load exercises for user
                dispatch("exercise/loadExercises");
            }

        })
        .catch(error => {
            console.log(error);
            commit(SET_ERROR_MESSAGE, "Unable to authenticate user!");
        });
    },
    login({ commit, dispatch }, credentials) {
        // Clear any existing error
        commit(SET_ERROR_MESSAGE, "");

        return Api.user.login(credentials.email, credentials.password).then(response => {
            commit(SET_USER, {
                id: response.data.id,
                email: response.data.email
            });

            // Load exercises
            dispatch("exercise/loadExercises");
        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        });
    },
    addWorkout({ commit, state }, workout) {
        let existingWorkouts = getCopy(state.workouts);

        // Check to see if this workout is already in the existing workouts list
        let foundWorkout = existingWorkouts.find((existingWorkout) => {
            if(existingWorkout.id === workout.id)
            {
                return existingWorkout;
            }
        });


        if(foundWorkout === undefined)
        {
            // New workout - add to top of list
            existingWorkouts.unshift(workout);
        }
        else
        {
            // Update the existing workout
            foundWorkout = workout;
        }

        commit(SET_WORKOUTS, existingWorkouts);

        return Promise.resolve(workout);
    },
    sortWorkouts({ commit, state }) {

        let existingWorkouts = getCopy(state.workouts);

        existingWorkouts.sort((workoutA, workoutB) => {
            return workoutB.id - workoutA.id;
        });

        commit(SET_WORKOUTS, existingWorkouts);
        return Promise.resolve(existingWorkouts);
    },
    createWorkout({ dispatch }) {
        return Api.workout.create().then(response => {
            return dispatch("addWorkout", response.data);
        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        });
    },
    loadWorkoutById({ dispatch }, workoutId) {
        return Api.workout.get(workoutId).then(response => {
            return dispatch("addWorkout", response.data);
        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        });
    },
    getWorkoutCollection({ commit, state, dispatch }, pageNumber = 1) {
        return Api.workout.getCollection(pageNumber).then(response => {

            let addWorkoutPromises = [];

            // Add each workout to the state
            response.data.collection.forEach(workout => {
                addWorkoutPromises.push(dispatch("addWorkout", workout));
            });

            return Promise.all(addWorkoutPromises).then(() => {
                // Sort the workouts
                return dispatch("sortWorkouts");
            })
            .then(() => {
                return response.data;
            });

        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        });
    },
    addWorkoutExercise({ commit, state }, workoutExercise) {
        let existingWorkoutExercises = getCopy(state.workoutExercises);

        let foundWorkoutExerciseIndex = existingWorkoutExercises.findIndex(existingWorkoutExercise => {
            return parseInt(existingWorkoutExercise.id) === parseInt(workoutExercise.id);
        });

        if(foundWorkoutExerciseIndex === -1)
        {
            existingWorkoutExercises.unshift(workoutExercise);
        }
        else
        {
            existingWorkoutExercises[foundWorkoutExerciseIndex] = workoutExercise;
            console.log(existingWorkoutExercises);
        }

        // Clear workouts so it reloads
        commit(SET_WORKOUTS, []);

        commit(SET_WORKOUT_EXERCISES, existingWorkoutExercises);
        return Promise.resolve(workoutExercise);
    },
    createWorkoutExercise({ dispatch }, payload) {
        return Api.workoutExercise.create(payload.workoutId, payload.exerciseId).then(response => {
            return dispatch("addWorkoutExercise", response.data);
        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        })
    },
    updateWorkoutExercise({ dispatch }, workoutExercise) {
        return Api.workoutExercise.update(workoutExercise.id, workoutExercise.exerciseId).then(response => {
            return dispatch("addWorkoutExercise", response.data);
        })
        .catch(err => {
            console.error(err);
        });
    },
    loadWorkoutExerciseById({ dispatch }, workoutExerciseId) {
        return Api.workoutExercise.get(workoutExerciseId).then(response => {
            return dispatch("addWorkoutExercise", response.data);
        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        });
    },
    addWorkoutExerciseSet({ commit, state }, payload) {
        // Find the workout exercise for this set
        let existingWorkoutExercises = getCopy(state.workoutExercises);

        let foundWorkoutExerciseIndex = existingWorkoutExercises.findIndex(existingWorkoutExercise => {
            return parseInt(existingWorkoutExercise.id) === parseInt(payload.workoutExerciseId);
        });

        if(foundWorkoutExerciseIndex === -1)
        {
            // Should not happen. Nothing to add, can't find the exercise
            console.log(payload);
            throw new Error("Unable to find exercise for set.");
        }

        // Check to see if this set already exists in the workout
        let foundSetIndex = existingWorkoutExercises[foundWorkoutExerciseIndex].workoutExerciseSets.findIndex(existingSet => {
            return parseInt(existingSet.id) === parseInt(payload.workoutExerciseSet.id);
        });

        if(foundSetIndex === -1)
        {
            // Add to start of exercises
            existingWorkoutExercises[foundWorkoutExerciseIndex].workoutExerciseSets.push(payload.workoutExerciseSet);
        }
        else
        {
            // Update existing
            existingWorkoutExercises[foundWorkoutExerciseIndex].workoutExerciseSets[foundSetIndex] = payload.workoutExerciseSet;
        }

        commit(SET_WORKOUT_EXERCISES, existingWorkoutExercises);
        return Promise.resolve(payload.workoutExerciseSet);
    },
    saveWorkoutExerciseSet({ dispatch, getters }, payload) {
        return Api.workoutExerciseSet.save(payload.workoutExerciseId, {
            statNotes: payload.statNotes,
            notes: payload.notes,
            id: payload.id
        }).then(response => {
            // Add workout exercise set to the workout exercise
            return dispatch("addWorkoutExerciseSet", {
                workoutExerciseId: payload.workoutExerciseId,
                workoutExerciseSet: response.data
            });
        })
        .catch(err => {
            console.log(err);
            return Promise.reject(err.response.data.message);
        });
    },
    setWorkoutExerciseHistory({ commit }, workoutExerciseHistory) {
        commit(SET_WORKOUT_EXERCISE_HISTORY, workoutExerciseHistory);
        return Promise.resolve();
    },
    loadWorkoutExerciseHistoryById({ dispatch, state }, workoutExerciseId) {
        return Api.workoutExercise.getHistory(workoutExerciseId).then(response => {
            // Check to see if we already have this workout exercise stored in the state
            let existingWorkoutExerciseHistory = getCopy(state.workoutExerciseHistory);

            let foundHistoryIndex = existingWorkoutExerciseHistory.findIndex(history => {
                return parseInt(history.id) === parseInt(workoutExerciseId);
            });

            if(foundHistoryIndex === -1)
            {
                // New history, add to state

                existingWorkoutExerciseHistory.push(response.data.collection);
                return dispatch("setWorkoutExerciseHistory", existingWorkoutExerciseHistory).then(() => {
                    return response.data.collection;
                });
            }

            // History for this is already in the state, replace it with the updated one
            existingWorkoutExerciseHistory[foundHistoryIndex] = response.data.collection;

            return dispatch("setWorkoutExerciseHistory", existingWorkoutExerciseHistory).then(() => {
                return response.data.collection;
            })
        })
        .catch(err => {
            console.log(err);
            return Promise.reject(err.response.data.message);
        });
    },
    deleteWorkout({ commit, state }, workoutId) {
        return Api.workout.delete(workoutId).then(() => {
            // Remove the workout from the list
            let existingWorkouts = getCopy(state.workouts);

            // Find the workout
            let index = existingWorkouts.findIndex(workout => {
                return workout.id === parseInt(workoutId);
            });

            if(index !== -1)
            {
                // Remove it from the list
                existingWorkouts.splice(index, 1);
            }

            commit(SET_WORKOUTS, existingWorkouts);
            return Promise.resolve(existingWorkouts);
        });
    },
    deleteWorkoutExercise({ commit, dispatch, state }, workoutExerciseId) {
        return Api.workoutExercise.delete(workoutExerciseId).then(() => {
            // Delete the exercise from the store
            let existingWorkoutExercises = getCopy(state.workoutExercises);

            let index = existingWorkoutExercises.findIndex(workoutExercise => {
                return parseInt(workoutExercise.id) === parseInt(workoutExerciseId);
            });

            if(index !== undefined)
            {
                // Remove from the existing workouts list
                existingWorkoutExercises.splice(index, 1);
            }

            // Clear out workouts to reset workouts store
            commit(SET_WORKOUTS, []);

            // Update the existing workout exercises
            commit(SET_WORKOUT_EXERCISES, existingWorkoutExercises);

            return Promise.resolve(existingWorkoutExercises);
        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        })
    },
    logout({ dispatch }) {
        return Api.user.logout().then(() => {
            return dispatch("setUser", {
                id: 0,
                email: ""
            });
        })
        .catch(err => {
            console.error(err);
            return Promise.reject("Unable to logout at this time.");
        });
    }
};

const getters = {
    getWorkoutById(state) {
        return (id) => {
            return state.workouts.find(workout => {
                return workout.id === parseInt(id);
            });
        };
    },
    getWorkoutExerciseById(state) {
        return (id) => {
            // Check to see if the workout exercise is stored already
            return state.workoutExercises.find(workoutExercise => {
                return workoutExercise.id === parseInt(id);
            });
        };
    },
    getWorkoutExerciseHistoryById(state) {
        return (id) => {
            return state.workoutExerciseHistory.find(history => {
                return history.id === parseInt(id);
            });
        };
    }
};

export default new Vuex.Store({
    state,
    mutations,
    actions,
    getters,
    modules
});
