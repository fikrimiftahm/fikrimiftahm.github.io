<template>
    <button @click="toggleDropdown" class="tw-flex tw-gap-2 tw-items-center tw-py-2 tw-px-4 tw-border-2 tw-rounded-full tw-border-primary-main !tw-border-solid tw-relative">
      <v-icon class="!tw-text-primary-main w-3 h-3">mdi-chevron-down</v-icon>
      <p class="tw-font-indosat-medium tw-text-sm tw-text-primary-main">{{ isObject(value) ? value.label : value }}</p>
      <div v-if="isOpen" class="tw-absolute tw-top-0 tw-mt-14 tw-min-w-[160px] tw-right-0 tw-shadow-md tw-bg-white tw-font-indosat tw-text-neutral-80 tw-text-sm tw-rounded-lg tw-overflow-auto tw-max-h-40">
        <button v-for="option in options" @click="selectOption(option)" :key="isObject(option) ? option.value : option" class="hover:tw-bg-neutral-30 tw-px-4 tw-py-2 tw-w-full">
            <p v-if="isObject(option)">{{ option.label }}</p>
            <p v-else>{{ option }}</p>
        </button>
      </div>
    </button>
  </template>
  
<script>
  import { VIcon } from 'vuetify/lib';
  
  export default {
    props: {
      value: {
        required: false,
      },
      options: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
        isOpen: false,
      };
    },
    computed: {
    },
    methods: {
        isObject(option){
            return typeof option !== 'string' && typeof option !== 'number'
        },
        toggleDropdown() {
          this.isOpen = !this.isOpen;
          if (this.isOpen) {
            document.addEventListener('click', this.closeDropdown);
          } else {
            document.removeEventListener('click', this.closeDropdown);
          }
        },
        closeDropdown(event) {
          if (!this.$el.contains(event.target)) {
            this.isOpen = false;
            document.removeEventListener('click', this.closeDropdown);
          }
        },
        selectOption(option) {
          this.selectedOption = option.label;
          this.isOpen = false;
          document.removeEventListener('click', this.closeDropdown);
          this.$emit('change', option);
        },
    },
  };
</script>