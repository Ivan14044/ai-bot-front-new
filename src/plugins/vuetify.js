import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import '@mdi/font/css/materialdesignicons.css';

const vuetify = createVuetify({
    icons: {
        defaultSet: 'mdi',
        sets: {
            mdi
        },
        aliases
    }
});

export default vuetify;
