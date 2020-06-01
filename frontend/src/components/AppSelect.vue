<template>
    <div class="app-select-component" :class="{ disabled }">
        <div class="selected-value-wrapper" v-if="!isOpen" @click="toggleOpenClose(true)" :aria-label="placeholder">
            <div class="selected-value-container is-placeholder" v-if="!selectedOption">
                {{ placeholder }}
                <span class="far fa-plus-square"></span>
            </div>
            <div class="selected-value-container" v-else>
                {{ selectedOption.text }}
                <span class="fas fa-pencil-alt"></span>
            </div>
        </div>
        <AppModal
            class="option-list-wrapper"
            v-if="isOpen"
            @close="toggleOpenClose(false)"
            :isVisible="isOpen"
        >
            <label class="text-center">
                {{ placeholder }}
            </label>
            <div class="form-group">
                <input type="search" name="search" class="input-sm" v-model="filterOptionsSearch" aria-label="Search Options" placeholder="Search Options">
            </div>
            <AppList :interactive="true">
                <AppListItem @click="onSelectOption(option.value)" v-for="(option, index) in filteredOptions" :key="index">
                    {{ option.text }}
                </AppListItem>
            </AppList>
            <p v-if="filteredOptions.length === 0" class="text-center">
                <em>No options found</em>
            </p>
        </AppModal>
    </div>
</template>

<script>
import AppList from '@/components/AppList';
import AppListItem from '@/components/AppListItem';
import AppModal from '@/components/AppModal';
export default {
    name: "AppSelect",
    components: {
        AppList,
        AppListItem,
        AppModal
    },
    props: {
        value: {
            type: [Number,String],
            default: ""
        },
        options: {
            type: Array,
            default: () => []
        },
        placeholder: {
            type: String,
            default: "Select"
        },
        alwaysShowOptionValue: {
            type: [Number,String],
            default: ""
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            isOpen: false,
            filterOptionsSearch: ""
        };
    },
    computed: {
        selectedOption() {
            let selectedOption = this.options.find(option => {
                return option.value === this.value;
            });

            return selectedOption;
        },
        filteredOptions() {
            return this.options.filter(option => {
                let optionText = option.text.toLowerCase();
                let filterText = this.filterOptionsSearch.toLowerCase();

                return optionText.indexOf(filterText) !== -1 || this.alwaysShowOptionValue === option.value;
            });
        }
    },
    methods: {
        onSelectOption(optionValue) {
            this.$emit("input", optionValue);
            this.$emit("searchValue", this.filterOptionsSearch);
            this.toggleOpenClose(false);
        },
        toggleOpenClose(status) {
            if(this.disabled)
            {
                // Don't open if component is disabled
                return false;
            }

            // Set the open status
            this.isOpen = status;

            // Reset the filter when you open/close
            this.filterOptionsSearch = "";
        }
    }
}
</script>

<style scoped>
    .app-select-component .selected-value-container {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: solid #8e8e8e 1px;
        padding: 10px;
        font-size: 1.5em;
        color: white;
        cursor: pointer;
        border-radius: 4px;
        transition: border-color .2s, color .2s, background-color .2s;
    }

    .app-select-component.disabled .selected-value-container {
        cursor: not-allowed;
        opacity: .8;
    }

    .app-select-component .selected-value-container.is-placeholder {
        color: #cfcfcf;
        border: dashed #444444 1px;
    }

    .app-select-component .selected-value-container:hover {
        border-color: #7d7d7d;
        color: #e4e4e4;
        background-color: rgba(255,255,255,.02);
    }

    .app-select-component .option-list-wrapper label {
        font-size: 1.25em;
        padding-bottom: 30px;
    }

</style>