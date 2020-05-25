
const SET_EXERCISES = "Set Exercises";
const SET_IS_LOADING_EXERCISES = "Set Is Loading Exercises";

import Api from '@/store/api';

import getCopy from '@/helpers/get-copy';

const state = {
    exercises: [],
    isLoadingExercises: false
};


const mutations = {
    [SET_EXERCISES](state, exercises) {
        state.exercises = exercises;
    },
    [SET_IS_LOADING_EXERCISES](state, isLoadingExercises) {
        state.isLoadingExercises = isLoadingExercises;
    }
};

const actions = {
    setExercises({ commit }, exercises) {
        commit(SET_EXERCISES, exercises);
        return Promise.resolve();
    },
    loadExercises({ dispatch, commit }) {
        commit(SET_IS_LOADING_EXERCISES, true);
        return Api.exercise.getCollection().then(response => {
            commit(SET_IS_LOADING_EXERCISES, false);
            return dispatch("setExercises", response.data.collection);
        })
        .catch(err => {
            commit(SET_IS_LOADING_EXERCISES, false);
            return Promise.reject(err.response.data.message);
        });
    },
    addExercise({ commit, dispatch, state }, exercise) {
        let existingExercises = getCopy(state.exercises);

        // Check to see if the exercise is already saved
        let index = existingExercises.findIndex(existingExercise => {
            return parseInt(existingExercise.id) === parseInt(exercise.id);
        });

        if(index === -1)
        {
            // Add to list
            existingExercises.push(exercise);
        }
        else
        {
            // Update existing
            existingExercises[index] = exercise;
        }

        // Update the exercises state
        commit(SET_EXERCISES, existingExercises);

        // Sort and then return the added exercise
        return dispatch("sortExercises").then(() => {
            return exercise;
        });
    },
    sortExercises({ dispatch }) {
        let existingExercises = getCopy(state.exercises);

        existingExercises.sort((exerciseA, exerciseB) => {
            const nameA = exerciseA.name.toLowerCase();
            const nameB = exerciseB.name.toLowerCase();

            if(nameA > nameB)
            {
                return 1;
            }

            if(nameB > nameA)
            {
                return -1;
            }

            return 0;
        });

        return dispatch("setExercises", existingExercises);
    },
    createExercise({ dispatch, commit }, exercise) {
        commit(SET_IS_LOADING_EXERCISES, true);
        return Api.exercise.create({
            name         : exercise.name,
            statTemplate : exercise.statTemplate,
            sumStat      : exercise.sumStat
        })
        .then(response => {
            // Add the exercise to the exisitng list
            commit(SET_IS_LOADING_EXERCISES, false);
            return dispatch("addExercise", response.data);
        })
        .catch(err => {
            console.log(err);
            commit(SET_IS_LOADING_EXERCISES, false);
            return Promise.reject(err.response.data.message);
        });
    },
    updateExercise({ dispatch, commit }, exercise) {
        commit(SET_IS_LOADING_EXERCISES, true);
        return Api.exercise.update({
            id           : exercise.id,
            name         : exercise.name,
            statTemplate : exercise.statTemplate,
            sumStat      : exercise.sumStat
        })
        .then(response => {
            // Add the exercise to the exisitng list
            commit(SET_IS_LOADING_EXERCISES, false);
            return dispatch("addExercise", response.data);
        })
        .catch(err => {
            console.log(err);
            commit(SET_IS_LOADING_EXERCISES, false);
            return Promise.reject(err.response.data.message);
        });
    },
    deleteExercise({ dispatch, commit, state }, exerciseId) {
        commit(SET_IS_LOADING_EXERCISES, true);
        return Api.exercise.delete(exerciseId).then(() => {
            // Delete the exercise from the exisitng list
            commit(SET_IS_LOADING_EXERCISES, false);

            // Find the exercise
            let existingExercises = getCopy(state.exercises);

            // Check to see if the exercise is already saved
            let index = existingExercises.findIndex(existingExercise => {
                return parseInt(existingExercise.id) === parseInt(exerciseId);
            });

            if(index !== -1)
            {
                // Remove the element
                existingExercises.splice(index, 1);
            }

            return dispatch("setExercises", existingExercises);
        })
        .catch(err => {
            console.log(err);
            commit(SET_IS_LOADING_EXERCISES, false);
            return Promise.reject(err.response.data.message);
        });
    }
};


export default {
    namespaced: true,
    state,
    mutations,
    actions
};
