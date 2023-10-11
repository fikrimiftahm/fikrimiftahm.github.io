import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

Vue.use(Vuetify)

const opts = {
    theme: {
        dark: false,
        themes: {
            light: {
                primary: '#ED1D25',
                secondary: '#FFCBO5',
                accent: '#32BCAD',
                error: '#B71C1C',
                background: '#4D4D4F'
            },
            dark: {
                primary: '#ED1D25',
                secondary: '#FFCBO5',
                accent: '#32BCAD',
                error: '#B71C1C',
                background: '#4D4D4F'
            },
        },
        options: {
            customProperties: true
        },
    },
    icons: {
        iconfont: 'mdi',
    },
}

export default new Vuetify(opts)