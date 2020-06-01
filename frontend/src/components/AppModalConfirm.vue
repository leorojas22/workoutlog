<template>
    <AppModal
        class="app-modal-confirm-component"
        @close="onClose"
        :isVisible="isVisible"
    >
        <slot></slot>
        <div class="form-group text-center delete-actions">
            <AppButton
                class="btn-danger btn-inline btn-sm"
                @click="onClickYes"
                :isLoading="isLoading"
            >
                Yes
            </AppButton>
            <AppButton
                class="btn-default btn-inline btn-sm"
                @click="onClose"
            >
                No
            </AppButton>
        </div>
    </AppModal>
</template>

<script>
import AppModal from '@/components/AppModal';
export default {
    name: "AppModalConfirm",
    components: {
        AppModal
    },
    props: {
        isVisible: {
            type: Boolean,
            default: false
        },
        isLoading: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        onClickYes() {
            this.$emit("confirmed");
        },
        onClose() {
            if(this.isLoading)
            {
                return false;
            }

            this.$emit("close");
        }
    }
}
</script>

<style scoped>
    .app-modal-confirm-component .delete-actions >>> button {
        margin-right: 20px;
    }

    .app-modal-confirm-component .delete-actions >>> button:last-child {
        margin-right: 0px;
    }

    .app-modal-confirm-component .delete-actions >>> button {
        min-width: 100px;
    }

    .app-modal-confirm-component .delete-actions {
        margin-top: 20px;
    }
</style>