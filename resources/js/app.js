require("./bootstrap");

import Vue from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue";
import { InertiaProgress } from "@inertiajs/progress";
import vuetify from "./Plugins/vuetify";
import axios from "axios";
import moment from "moment";
import VModal from 'vue-js-modal'
import VSelect from "vue-select";

Vue.prototype.$axios = axios;
Vue.prototype.moment = moment;

Vue.use(VModal);
Vue.use(VSelect);
Vue.component("v-select", VSelect);

InertiaProgress.init({
    color: "#ED1D25",
});


createInertiaApp({
    resolve: (name) =>
        import (`./Pages/${name}`),
    setup({ el, App, props, plugin }) {
        Vue.use(plugin);

        new Vue({
            vuetify,
            render: (h) => h(App, props),
        }).$mount(el);
    },
});