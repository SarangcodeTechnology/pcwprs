import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import colors from 'vuetify/lib/util/colors'

Vue.use(Vuetify)

const opts = {
    theme: {
        themes: {
            light: {
                primary: "#4CAF50",
                secondary: "#FF9800",
                accent: "#00C853",
                error: colors.red,
                success: colors.green
            },
            dark: {
                primary: "#2E7D32",
                secondary: "#EF6C00",
                accent: "#00C853",
                error: colors.red,
                success: colors.green
            },
        },
    },
}

export default new Vuetify(opts)
