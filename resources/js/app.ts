
import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import { createPinia } from 'pinia';
import Layout from '@/Pages/Setlan/Components/Layouts.vue';
import LayoutUnit from '@/Pages/Setlan/Components/LayoutsPilihUnit.vue';

import Tooltip from 'primevue/tooltip';

import VueSweetalert2 from 'vue-sweetalert2';
import ElementPlus from 'element-plus';

import '../css/app.css';
import 'primevue/resources/themes/aura-light-green/theme.css'
import './bootstrap';
import 'element-plus/dist/index.css';
import 'sweetalert2/dist/sweetalert2.min.css';


const appName = import.meta.env.VITE_APP_NAME || 'Setlan';
const pinia = createPinia();

createInertiaApp({
    title: (title:string) => `${title} - ${appName}`,
    resolve: async (name: string) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: false })
        let page = await pages[`./Pages/${name}.vue`]() as DefineComponent
        const authLayout = name.startsWith('Auth') ? undefined : Layout;
        page.default.layout = name.startsWith('Setlan/ChooseUnit')  ? LayoutUnit : authLayout;
        return page
      },
    setup({ el, App, props, plugin }) {
       const app =createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueSweetalert2)
            .use(ElementPlus)
            .use(pinia)
            .directive('tooltip', Tooltip)
        app.use(ZiggyVue)
        app.mount(el);
        },
    progress: {
        color: '#92a7c5',
    },
});
