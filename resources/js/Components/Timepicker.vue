<template>
    <div class="input !tw-py-1.5 !tw-items-baseline">
      <date-picker :disabled-date="(date) => +date < +new Date()" v-model="time" valueType="format" type="time">
        <template v-slot:input>
          <p class="tw-font-indosat tw-text-sm tw-text-neutral-90" v-if="time">{{ time }}</p>
          <p class="tw-font-indosat tw-text-sm tw-text-neutral-50" v-else>HH:MM:SS</p>
        </template>
      </date-picker>
    </div>
  </template>
  
  <script>
    import DatePicker from 'vue2-datepicker';
    
    export default {
      components: { DatePicker },
      data() {
        return {
          time: null,
        };
      },
      computed: {
        formattedTime() {
          return this.$data.time ? this.moment(this.$data.time).format("DD/MM/YYYY") : null
        } 
      },
    };
  </script>