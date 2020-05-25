<template>
    <div class="app-modal-component">
        <div class="modal-content-wrapper">
            <div class="text-right">
                <AppButton class="btn-close" aria-label="close" @click="onClose">
                    <span class="fas fa-times"></span>
                </AppButton>
            </div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "AppModal",
    methods: {
        onClose() {
            this.$emit("close");
        }
    }
}
</script>

<style scoped>
    .app-modal-component {
        position: fixed;
        top: 0px;
        left: 0px;
        bottom: 0px;
        right: 0px;
        padding: 20px;
        background-color: #353535;
        z-index: 100;
    }

    .app-modal-component .modal-content-wrapper {
        position: relative;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        max-width: 720px;
    }

</style>