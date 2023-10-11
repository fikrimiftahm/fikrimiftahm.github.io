<template lang="">
  <v-select class="input !tw-p-0" :options="options" :value="value" :disabled="disabled" @input="handleInput" :clearable="false" :placeholder="placeholder"></v-select>
</template>
<script>
    import "vue-select/dist/vue-select.css";

    export default {
        props: {
            options: {
                type: Array,
                required: true,
            },
            disabled:{
                type: Boolean,
                required: false
            },
            value: {
                required: true
            },
            placeholder: {
                type: String,
                required: false
            }
        },
        methods: {
            handleInput(value) {
                this.$emit('change', value);
            },
        },
    }
</script>
<style lang="css">
    .vs__dropdown-toggle{
        padding: 0 8px;
        border: none;
        background: none;
        height: 100%;
        border-radius: 8px;
    }

    .vs__selected-options input, .vs__selected{
        font-family: "Indosat Regular";
    }

    .vs__selected-options input::placeholder{
        color: rgba(0, 0, 0, 0.24);
    }

    .vs__dropdown-option--highlight{
        background: #EC008C;
    }
</style>