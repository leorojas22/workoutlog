

export default {
    user: {
        auth: (...args) => import(/* webpackChunkName: "user-api" */ '@/store/api/user-auth').then(module => module.default(...args)),
        login: (...args) => import(/* webpackChunkName: "user-api" */ '@/store/api/login').then(module => module.default(...args))
    },
    workout: {
        create: (...args) => import(/* webpackChunkName: "workout-api" */ '@/store/api/workout/create').then(module => module.default(...args)),
        get: (...args) => import(/* webpackChunkName: "workout-api" */ '@/store/api/workout/get').then(module => module.default(...args)),
        getCollection: (...args) => import(/* webpackChunkName: "workout-api" */ '@/store/api/workout/get-collection').then(module => module.default(...args))
    },
    workoutExercise: {
        get: (...args) => import(/* webpackChunkName: "workout-exercise-api" */ '@/store/api/workout-exercise/get').then(module => module.default(...args)),
        create: (...args) => import(/* webpackChunkName: "workout-exercise-api" */ '@/store/api/workout-exercise/create.js').then(module => module.default(...args)),
        update: (...args) => import(/* webpackChunkName: "workout-exercise-api" */ '@/store/api/workout-exercise/update.js').then(module => module.default(...args))
    },
    workoutExerciseSet: {
        save: (...args) => import(/* webpackChunkName: "workout-exercise-set-api" */ '@/store/api/workout-exercise-set/save.js').then(module => module.default(...args)),
    },
    exercise: {
        create: (...args) => import(/* webpackChunkName: "exercise-api" */ '@/store/api/exercise/create.js').then(module => module.default(...args)),
        update: (...args) => import(/* webpackChunkName: "exercise-api" */ '@/store/api/exercise/update.js').then(module => module.default(...args)),
        getCollection: (...args) => import(/* webpackChunkName: "exercise-api" */ '@/store/api/exercise/get-collection.js').then(module => module.default(...args)),
        delete: (...args) => import(/* webpackChunkName: "exercise-api" */ '@/store/api/exercise/delete.js').then(module => module.default(...args)),
    }
};
