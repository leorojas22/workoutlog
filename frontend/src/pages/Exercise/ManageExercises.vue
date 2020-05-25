<template>
    <section class="manage-exercises-page container">
        <p class="text-center">
            <strong>Manage Exercises</strong>
        </p>
        <hr />
        <div class="form-group">

        </div>
        <AppList>
            <AppListItem
                v-for="(exercise, index) in exercises"
                :key="index"
                class="exercise-item"
            >
                <div class="exercise-name" :title="exercise.name">
                    {{ exercise.name }}
                </div>
                <div class="actions">
                    <AppButton
                        class="btn-main btn-xs btn-inline"
                        @click="onClickEditExercise(exercise)"
                    >
                        <i class="fas fa-pencil-alt" aria-label="Edit Exercise" />
                    </AppButton>
                    <AppButton
                        class="btn-danger btn-xs btn-inline"
                        @click="onClickDeleteExercise(exercise)"
                    >
                        <i class="fas fa-trash-alt" aria-label="Edit Exercise" />
                    </AppButton>
                </div>
            </AppListItem>
        </AppList>

        <AppModal v-if="editingExercise !== null" @close="onCloseEditExercise">
            <p class="text-center">
                <strong>Edit Exercise</strong>
            </p>
            <hr />
            <AppFormManageExercise
                @savedExercise="onSaveExercise"
                :exercise="editingExercise"
            />
        </AppModal>

        <AppModal v-if="deletingExercise !== null" @close="onCloseDeleteExercise">
            <p class="text-center">
                <strong>Delete Exercise</strong>
            </p>
            <hr />
            <div class="form-group h3 text-center">
                Are you sure you want to delete <strong class="text-underline">{{ this.deletingExercise.name }}</strong>?
            </div>
            <div class="form-group text-center delete-actions">
                <AppButton
                    class="btn-danger btn-inline btn-sm"
                    @click="onClickConfirmDelete"
                    :isLoading="isDeleting"
                >
                    Yes
                </AppButton>
                <AppButton
                    class="btn-default btn-inline btn-sm"
                    @close="onCloseDeleteExercise"
                >
                    No
                </AppButton>
            </div>
        </AppModal>

    </section>
</template>

<script>
import AppModal from '@/components/AppModal';
import AppFormManageExercise from '@/components/AppFormManageExercise';
export default {
    name: "ManageExercises",
    components: {
        AppModal,
        AppFormManageExercise
    },
    data() {
        return {
            editingExercise: null,
            deletingExercise: null,
            isDeleting: false
        };
    },
    computed: {
        exercises() {
            return this.$store.state.exercise.exercises;
        }
    },
    methods: {
        onSaveExercise(exerciseId) {
            this.editingExercise = null;
        },
        onCloseEditExercise() {
            this.editingExercise = null;
        },
        onCloseDeleteExercise() {
            this.deletingExercise = null;
        },
        onClickEditExercise(exercise) {
            this.editingExercise = exercise;
        },
        onClickDeleteExercise(exercise) {
            this.deletingExercise = exercise;
        },
        onClickConfirmDelete() {
            this.isDeleting = true;
            this.$store.dispatch("exercise/deleteExercise", this.deletingExercise.id).then(() => {
                this.deletingExercise = null;
                this.isDeleting = false;
            })
            .catch(err => {
                console.error(err);
                this.isDeleting = false;
            });
        }
    }
}
</script>

<style scoped>
    .manage-exercises-page .exercise-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .manage-exercises-page .exercise-name {
        max-width: 500px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .manage-exercises-page .delete-actions >>> button,
    .manage-exercises-page .exercise-item .actions >>> button {
        margin-right: 20px;
    }

    .manage-exercises-page .delete-actions >>> button:last-child,
    .manage-exercises-page .exercise-item .actions >>> button:last-child {
        margin-right: 0px;
    }

    .manage-exercises-page .delete-actions >>> button {
        min-width: 100px;
    }

    .manage-exercises-page .delete-actions {
        margin-top: 20px;
    }

    @media (max-width: 414px) {
        .manage-exercises-page .exercise-name {
            max-width: 140px;
        }
    }
</style>