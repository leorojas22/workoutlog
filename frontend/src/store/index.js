import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

// Api Calls
import Api from '@/store/api';


const getCopy = (someObject) => {
    return JSON.parse(JSON.stringify(someObject));
};


// Mutations
const SET_USER              = "Set User";
const SET_AUTH_TOKEN        = "Set Auth Token";
const SET_ERROR_MESSAGE     = "Set Error Message";
const SET_WORKOUTS          = "Set Workouts";
const SET_WORKOUT_EXERCISES = "Set Workout Exercises";

// State
const state = {
    user: {
        id: 0,
        email: ""
    },
    authToken: "",
    errorMessage: "",
    workouts: [],
    workoutExercises: []
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
    }
};

// Actions
const actions = {
    setUser({ commit }, user) {
        commit(SET_USER, user);
        return Promise.resolve();
    },
    setAuthenticationValues({ commit }) {

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
            }

        })
        .catch(error => {
            commit(SET_ERROR_MESSAGE, "Unable to authenticate user!");
        });
    },
    login({ commit }, credentials) {
        // Clear any existing error
        commit(SET_ERROR_MESSAGE, "");

        return Api.user.login(credentials.email, credentials.password).then(response => {
            commit(SET_USER, {
                id: response.data.id,
                email: response.data.email
            });
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
    createWorkout({ dispatch }) {
        return Api.workout.create().then(response => {
            return dispatch("addWorkout", response.data);
        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        });
    },
    addWorkoutExercise({ commit, state, getters }, workoutExercise) {
        let existingWorkoutExercises = getCopy(state.workoutExercises);
        console.log(existingWorkoutExercises);

        let foundWorkoutExerciseIndex = existingWorkoutExercises.findIndex(existingWorkoutExercise => {
            return existingWorkoutExercise.id === workoutExercise.id;
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
    loadWorkoutExercise({ dispatch }, workoutExerciseId) {
        return Api.workoutExercise.get(workoutExerciseId).then(response => {
            return dispatch("addWorkoutExercise", response.data);
        })
        .catch(err => {
            return Promise.reject(err.response.data.message);
        });
    },

};

const getters = {
    getWorkoutExerciseById(state) {
        return (id) => {
            // Check to see if the workout exercise is stored already
            return state.workoutExercises.find(workoutExercise => {
                if(workoutExercise.id === parseInt(id))
                {
                    return workoutExercise;
                }
            });
        };
    }
};

export default new Vuex.Store({
    state,
    mutations,
    actions,
    getters
});
