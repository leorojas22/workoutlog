<template>
    <li class="app-past-workout-component">
        <div class="workout-day">
            {{ workoutDay }}
        </div>
        <div class="workout-time">
            {{ workoutTime }}
        </div>
    </li>
</template>

<script>
import moment from 'moment';

export default {
    name: "AppListItemPastWorkout",
    props: {
        workoutId: {
            type: Number,
            required: true
        },
        date: {
            type: Date,
            required: true
        }
    },
    computed: {
        workoutMoment() {
            return moment(this.date);
        },
        workoutDay() {
            return this.workoutMoment.format("ddd MMMM Mo");
        },
        workoutTime() {
            return this.workoutMoment.format("h:mm A");
        }
    }
}
</script>

<style scoped>
    .app-past-workout-component .workout-day {
        font-size: 1.5em;
    }
</style>
