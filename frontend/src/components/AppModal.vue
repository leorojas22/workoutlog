<template>
    <div class="app-modal-component" v-if="isVisible">
        <div class="modal-content-wrapper">
            <div class="text-right" v-if="showCloseButton">
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
    props: {
        showCloseButton: {
            type: Boolean,
            default: true
        },
        isVisible: {
            type: Boolean,
            default: false
        }
    },
    watch: {
        isVisible: function(isVisible) {
            if(isVisible && !document.body.classList.contains("modal-open"))
            {
                document.body.classList.add("modal-open");
                return;
            }

            if(!isVisible && document.body.classList.contains("modal-open"))
            {
                document.body.classList.remove("modal-open");
                return;
            }
        }
    },
    updated() {
        this.toggleBodyClass();
    },
    beforeDestroy() {
        this.toggleBodyClass(false);
    },
    beforeMount() {
        this.toggleBodyClass();
    },
    methods: {
        onClose() {
            this.$emit("close");
        },
        toggleBodyClass(isVisibleOverride = null) {

            let isVisible = isVisibleOverride !== null ? isVisibleOverride : this.isVisible;

            if(isVisible && !document.body.classList.contains("modal-open"))
            {
                document.body.classList.add("modal-open");
                return;
            }

            if(!isVisible && document.body.classList.contains("modal-open"))
            {
                document.body.classList.remove("modal-open");
                return;
            }
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