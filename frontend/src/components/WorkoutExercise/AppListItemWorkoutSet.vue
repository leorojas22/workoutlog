<template>
    <AppListItem class="app-list-item-workout-set-component">
        <div class="set-details-wrapper">
            <div class="set-number">{{ setNumber }}.</div>
            <div class="set-stats">
                <div v-for="(statText, statTextIndex) in getFormattedWorkoutExerciseSets(workoutExerciseSet)" :key="'stat_text_' + statTextIndex">
                    {{ statText }}
                </div>
            </div>
        </div>
        <hr />
        <small>
            {{ workoutExerciseSet.notes }}
        </small>
    </AppListItem>
</template>

<script>
export default {
    name: "AppListItemWorkoutSet",
    props: {
        workoutExerciseSet: {
            required: true,
            type: Object
        },
        setNumber: {
            required: true,
            type: Number
        }
    },
    methods: {
        getFormattedWorkoutExerciseSets(workoutExerciseSet) {
            // Format set stats to show in the UI, returns an array of strings that can be displayed
            let formattedSets = [];
            workoutExerciseSet.stats.forEach(setStats => {
                let formattedSetArray = [];
                for(let statName in setStats)
                {
                    let setStat = setStats[statName];
                    formattedSetArray.push(statName + ": " + setStat);
                }

                formattedSets.push(formattedSetArray.join(" - "));
            });

            return formattedSets;
        }
    }
}
</script>

<style scoped>
    .app-list-item-workout-set-component .set-details-wrapper {
        display: flex;
        flex-direction: row;
    }

    .app-list-item-workout-set-component .set-details-wrapper .set-stats {
        padding-left: 10px;
    }
</style>