

export default {
    user: {
        auth: (...args) => import(/* webpackChunkName: "user-api" */ '@/store/api/user-auth').then(module => module.default(...args)),
        login: (...args) => import(/* webpackChunkName: "user-api" */ '@/store/api/login').then(module => module.default(...args))
    },
    workout: {
        create: (...args) => import(/* webpackChunkName: "workout-api" */ '@/store/api/workout/create').then(module => module.default(...args))
    },
    workoutExercise: {
        get: (...args) => import(/* webpackChunkName: "workout-exercise-api" */ '@/store/api/workout-exercise/get').then(module => module.default(...args)),
        create: (...args) => import(/* webpackChunkName: "workout-exercise-api" */ '@/store/api/workout-exercise/create.js').then(module => module.default(...args)),
        update: (...args) => import(/* webpackChunkName: "workout-exercise-api" */ '@/store/api/workout-exercise/update.js').then(module => module.default(...args))
    }
};
