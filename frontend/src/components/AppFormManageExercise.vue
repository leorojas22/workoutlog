<template>
    <form @submit="onSubmitSaveExercise">
        <div class="notification error" v-if="errorMessage">
            {{ errorMessage }}
        </div>
        <div class="form-group">
            <label for="exerciseName">
                Name
            </label>
            <input type="text" id="exerciseName" v-model="name" name="exerciseName">
        </div>
        <div class="form-group">
            <label for="statTemplate">
                Stat Template
            </label>
            <input type="text" id="statTemplate" v-model="statTemplate" name="statTemplate">
        </div>
        <div class="form-group">
            <label for="sumStat">
                Stat to SUM
            </label>
            <input type="text" id="sumStat" v-model="sumStat" name="sumStat">
        </div>
        <div class="form-group">
            <AppButton class="btn-main" type="submit" :isLoading="isSaving">
                Save
            </AppButton>
        </div>
    </form>
</template>

<script>
export default {
    name: "AppFormManageExercise",
    props: {
        exercise: {
            type: Object,
            required: false,
            default: () => (null)
        }
    },
    beforeMount() {
        if(!this.exercise)
        {
            this.id            = 0;
            this.name          = "";
            this.statTemplate  = "";
            this.sumStat       = "";
            return;
        }

        this.id            = this.exercise.id;
        this.name          = this.exercise.name;
        this.statTemplate  = this.exercise.statTemplate;
        this.sumStat       = this.exercise.sumStat;
    },
    data() {
        return {
            id: 0,
            name: "",
            statTemplate: "",
            sumStat: "",
            isSaving: false,
            errorMessage: ""
        };
    },
    methods: {
        onSubmitSaveExercise(e) {
            e.preventDefault();

            // Make sure the name isn't empty
            if(this.name.trim() === "")
            {
                this.errorMessage = "Name may not be blank.";
                return;
            }

            // Clear any existing errors
            this.errorMessage = "";

            // Set the button to show it's loading
            this.isSaving = true;

            let action = this.id ? "updateExercise" : "createExercise";

            // Make the api call
            this.$store.dispatch("exercise/" + action, {
                id: this.id,
                name: this.name,
                statTemplate: this.statTemplate,
                sumStat: this.sumStat
            }).then(exercise => {
                this.isSaving = false;
                this.$emit("savedExercise", exercise.id);
            })
            .catch(err => {
                this.errorMessage = typeof err === 'string' ? err : "An error occurred while saving the exercise.";
                this.isSaving = false;
            });
        }
    }
}
</script>