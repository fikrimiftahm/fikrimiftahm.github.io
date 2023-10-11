<template>
    <div
      ref="dropzone"
      class="tw-relative tw-w-full tw-h-32 tw-border-2 tw-border-dashed tw-border-neutral-80 tw-rounded-lg"
      @dragover.prevent
      @dragenter.prevent
      @drop="handleDrop"
    >
      <input
        ref="inputFile"
        type="file"
        class="tw-hidden"
        @change="handleFileInput"
      />
      <div class="tw-absolute tw-inset-0 tw-flex tw-items-center tw-justify-center tw-flex-col">
        <svg v-if="!file" width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1.5 3.5946V25.6487C1.5 27.0816 2.66164 28.2432 4.09459 28.2432H18.3649C19.7978 28.2432 20.9595 27.0816 20.9595 25.6487V7.26391C20.9595 6.57578 20.6861 5.91583 20.1995 5.42925L16.5302 1.75994C16.0436 1.27336 15.3837 1 14.6956 1H4.09459C2.66164 1 1.5 2.16164 1.5 3.5946Z" fill="white" stroke="black" stroke-opacity="0.96" stroke-width="1.2973"/>
          <path d="M4.74316 16.5674H9.93235" stroke="black" stroke-opacity="0.96" stroke-width="1.2973" stroke-linecap="round"/>
          <path d="M4.74316 20.4595H9.93235" stroke="black" stroke-opacity="0.96" stroke-width="1.2973" stroke-linecap="round"/>
          <path d="M4.74316 24.3516H9.93235" stroke="black" stroke-opacity="0.96" stroke-width="1.2973" stroke-linecap="round"/>
          <path d="M12.5269 16.5674H17.716" stroke="black" stroke-opacity="0.96" stroke-width="1.2973" stroke-linecap="round"/>
          <path d="M12.5269 20.4595H17.716" stroke="black" stroke-opacity="0.96" stroke-width="1.2973" stroke-linecap="round"/>
          <path d="M12.5269 24.3516H17.716" stroke="black" stroke-opacity="0.96" stroke-width="1.2973" stroke-linecap="round"/>
          <rect x="13.8242" y="19.1621" width="11.6757" height="11.6757" rx="5.83784" fill="#EC008C"/>
          <path d="M19.3654 25.297H17.7163V24.7165H19.3654V23.0542H19.9459V24.7165H21.6082V25.297H19.9459V26.9461H19.3654V25.297Z" fill="white"/>
        </svg>
        <p class="tw-text-neutral-80 tw-font-indosat tw-text-xs tw-break-all tw-line-clamp-2 tw-px-8" v-else>{{ file.name }}</p>
        <div class="tw-text-gray-400 tw-text-center">
          <button
            class="tw-ml-2 tw-text-blue-500 hover:tw-text-blue-600 focus:tw-outline-none"
            @click="$refs.inputFile.click()"
          >
            Browse
          </button>
          <span>your file to upload</span>
        </div>
        <p class="tw-font-indosat tw-text-xs tw-text-neutral-50">text, xls, xlsx, csv format file</p>
        <p class="tw-font-indosat-medium tw-text-xs tw-text-neutral-80">Max 8MB</p>
      </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        file: null,
      };
    },
    methods: {
      handleDrop(e) {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        this.file = file;
        this.$emit("file-updated", file);
      },
      handleFileInput(e) {
        const file = e.target.files[0];
        this.file = file;
        this.$emit("file-updated", file);
      },
    },
  };
</script>