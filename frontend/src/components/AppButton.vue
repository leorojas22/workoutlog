<template>
    <button :disabled="isLoading" @click="onClick">
        <template v-if="isLoading">
            <i class="fa fa-spinner fa-spin" aria-label="Loading" />
        </template>
        <template v-else>
            <slot></slot>
        </template>
    </button>
</template>

<script>
export default {
    name: "AppButton",
    props: {
        isLoading: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        onClick(e) {
            this.$emit("click", e);
        }
    }
}
</script>


<style scoped>
    button {
        display: block;
        font-family: 'Lato', arial, sans-serif;
        padding: 20px 15px;
        width: 100%;
        border: 0px;
        outline: none;
        border-radius: 4px;
        font-size: 1.75em;
        font-weight: bold;
        color: white;
        cursor: pointer;
        transition: background-color .2s;
    }

    button:disabled {
        cursor: not-allowed;
        opacity: .75;
    }

    button.btn-close {
        display: inline-block;
        width: auto;
        color: #cfcfcf;
        padding: 0px;
        background-color: transparent;
        transition: color .2s;
    }

    button.btn-close:hover {
        color: white;
    }

    button.btn-sm {
        padding: 10px 15px;
        font-size: 1.25em;
    }

    button.btn-xs {
        padding: 10px 15px;
        font-size: 1em;
    }

    button.btn-inline {
        display: inline-block;
        width: auto;
    }

    button.btn-main {
        background-color: #00a2ff;
        box-shadow: 0px 0px 15px 0px rgba(0, 162, 255, .5);
    }

    button.btn-main:hover {
        background-color: #008cdd;
    }

    button.btn-default {
        background-color: #cfcfcf;
        color: #1d1d1d;
        box-shadow: 0px 0px 15px 0px rgba(207, 207, 207, .25);
    }

    button.btn-default:hover {
        background-color: #bebdbd;
    }

    button.btn-danger {
        background-color: #FF0000;
        box-shadow: 0px 0px 15px 0px rgba(255, 0, 0, .5);
    }

    button.btn-danger:hover {
        background-color: #d60000;
    }
</style>