import Vue from 'vue';
import Router from 'vue-router'

import store from '@/store';

Vue.use(Router);


// Pages
import Login from '@/pages/Login';
import Dashboard from '@/pages/Dashboard';
import Workout from '@/pages/Workout';
import WorkoutExercise from '@/pages/Workout/WorkoutExercise';
import WorkoutExerciseSet from '@/pages/Workout/WorkoutExerciseSet';
import ManageExercises from '@/pages/Exercise/ManageExercises';

const authenticatedRoutes = [
    'Dashboard',
    'Workout',
    'WorkoutExercise',
    'WorkoutExerciseSet',
    'ManageExercises'
];

const router = new Router({
    routes: [
        {
            path: '/',
            name: 'Dashboard',
            component: Dashboard
        },
        {
            path: '/login',
            name: 'Login',
            component: Login
        },
        {
            path: '/workout/:id',
            name: 'Workout',
            component: Workout
        },
        {
            path: '/workout/:workoutId/exercise/:workoutExerciseId?',
            name: 'WorkoutExercise',
            component: WorkoutExercise
        }, {
            path: '/workout/:workoutId/exercise/:workoutExerciseId/set/:workoutExerciseSetId?',
            name: 'WorkoutExerciseSet',
            component: WorkoutExerciseSet
        },
        {
            path: '/exercises',
            name: 'ManageExercises',
            component: ManageExercises
        }

    ]
});

router.beforeEach((to, from, next) => {

    // Check to see if there is an auth token
    let checkAuthTokenPromise = Promise.resolve();
    if(!store.state.authToken)
    {
        checkAuthTokenPromise = store.dispatch("setAuthenticationValues");
    }

    checkAuthTokenPromise.then(() => {
        // Make sure user is authenticated if trying to access any authenticated routes
        if(authenticatedRoutes.indexOf(to.name) !== -1 && !store.state.user.id)
        {
            // Must be authenticated for this route - don't continue
            return next({ name: 'Login' });
        }

        next();
    });
});


export default router;