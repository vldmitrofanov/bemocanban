require('./bootstrap');
import Vue from 'vue'
import draggable from 'vuedraggable'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(IconsPlugin)
Vue.use(BootstrapVue)
Vue.component('draggable', draggable);
Vue.component('validation-provider', ValidationProvider);
Vue.component('validation-observer', ValidationObserver);
import * as rules from "vee-validate/dist/rules";
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});
Vue.component('bemo-kanban', require('./components/BemoKanban.vue').default);
Vue.component('bemo-kanban-column', require('./components/BemoKanbanColumn.vue').default);
Vue.component('bemo-kanban-card', require('./components/BemoKanbanCard.vue').default);
Vue.component('bemo-kanban-card-edit', require('./components/BemoKanbanCardEdit.vue').default);
const app = new Vue({
    el: '#app',
});