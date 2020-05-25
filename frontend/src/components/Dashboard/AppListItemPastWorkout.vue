<template>
    <li class="app-past-workout-component" @click="onClick">
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
        workout: {
            type: Object,
            required: true
        }
    },
    computed: {
        workoutMoment() {
            return moment(new Date(this.workout.createdAt));
        },
        workoutDay() {
            return this.workoutMoment.format("ddd MMMM Do");
        },
        workoutTime() {
            return this.workoutMoment.format("h:mm A");
        }
    },
    methods: {
        onClick() {
            this.$emit("click", this.workout.id);
        }
    }
}
</script>

<style scoped>
    .app-past-workout-component .workout-day {
        font-size: 1.5em;
    }
</style>
