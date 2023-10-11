<template>
    <app-layout>
        <v-row
            :align="'center'"
            :justify="'center'"
            :style="{ display: alert ? '' : 'none' }"
        >
            <v-col md="4">
                <v-alert
                    :type="type"
                    :value="alert"
                    transition="fade-transition"
                >
                    {{ message }}
                </v-alert>
            </v-col>
        </v-row>

        <v-row class="pa-5">
            <v-col
                cols="12"
                md="4"
                v-for="(service, i) in servicesMenu"
                :key="i"
            >
                <v-card>
                    <v-list-item three-line>
                        <v-list-item-content>
                            <div class="text-overline mb-4">Service</div>
                            <v-list-item-title class="text-h5 mb-1">
                                {{ service.title }}
                            </v-list-item-title>
                            <v-list-item-subtitle></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-card-actions>
                        <v-btn outlined rounded text>
                            <Link :href="service.url">Detail</Link>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/App.vue";
import { Link } from "@inertiajs/inertia-vue";
import VModal from 'vue-js-modal'
import Vue from "vue";

Vue.use(VModal)

export default {
    components: {
        AppLayout,
        Link,
    },

    props: {
        type: String,
        message: String,
    },

    data() {
        return {
            alertMessage: '',
            alertType: '',
            alert: false,
        }
    },

    computed: {
        servicesMenu() {
            return this.$page.props.servicesMenu;
        },
    },

    mounted() {
        if (this.type) {
            this.showAlert(this.type, this.message)
        }
    },

    methods: {
        showAlert(type, msg) {
            this.alertType = type;
            this.alertMessage = msg;
            this.alert = true;

            setTimeout(() => {
                this.alert = false;
                this.alertType = "success";
                this.alertMessage = "";
            }, 10000);
        },
    }
};
</script>