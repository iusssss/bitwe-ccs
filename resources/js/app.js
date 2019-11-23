require('./bootstrap');

window.Vue = require('vue');
// Vue.component('home', require('./Home.vue').default);
// Vue.component('chart', require('./Chart.vue').default);
// Vue.component('manage', require('./Manage.vue').default);
Vue.component('Test', require('./Test.vue').default);
Vue.component('worker', require('./Worker.vue').default);
Vue.component('ServerErrors', require('./components/ServerErrors.vue').default);
Vue.component('ButtonLoading', require('./components/ButtonLoading.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

import 'vuejs-noty/dist/vuejs-noty.css';
import router from './router';
import VueNoty from 'vuejs-noty';
import Index from './components/layouts/Index';
import {store} from './store/store';
import VueTimeago from 'vue-timeago';

router.beforeEach((to, from, next) => {
	if (!store.getters.loggedIn) {
		setRouterSecurity(to, from, next);
		return;
	}
	store.dispatch('retrievePrivilege')
	.then(response => {
		setRouterSecurity(to, from, next);
	});
})
function setRouterSecurity(to, from, next) {
	if (to.matched.some(record => record.meta.requiresNone)) {
		next()
	} else if (to.matched.some(record => record.meta.requiresSystemAdmin)) {
		if (store.getters.isSystemAdmin) {
			next()
		} else {
			next({
				path: '/',
			})
		}
	} else if (to.matched.some(record => record.meta.requiresAdmin)) {
		if (store.getters.isAdmin) {
			next()
		} else {
			next({
				path: '/',
			})
		}
	} else if (to.matched.some(record => record.meta.requiresAuth)) {
		if (store.getters.loggedIn) {
			next()
		} else {
			next({
				path: '/',
			})
		}
	} else if (to.matched.some(record => record.meta.requiresVisitor)) {
		if (!store.getters.loggedIn) {
			next()
		} else {
			next({
				path: '/home',
			})
		}
	}
}
Vue.use(VueNoty);
Vue.use(VueTimeago, {
  name: 'Timeago',
  locale: 'en',
  locales: {
	'zh-CN': require('date-fns/locale/zh_cn'),
	ja: require('date-fns/locale/ja')
  }
})

// Vue.config.devtools = false
// Vue.config.debug = false
// Vue.config.silent = true

const app = new Vue({
    el: '#app',
    router,
    store,
    components: { Index },
    template: '<Index />',
    beforeCreate() {
    },
    created() {
        Echo.channel('ticket-channel')
        .listen('TicketCreated', (ticket) => {
        	ticket.ticket.updates = [{status:'', resolvedBy:''}]
            this.$store.commit('addTicket', ticket.ticket);
        })
        .listen('TicketUpdateCreated', (ticketUpdate) => {
            this.$store.commit('addTicketUpdate', ticketUpdate.ticketUpdate);
        })
        
        if (this.$store.getters.loggedIn) {
			this.$store.dispatch('retrieveCompanies');
		}
    },
    methods: {
    }
    // data: {
    // 	rootUrl: "{{ Config::get('TWILIO_ACCOUNT_SID') }}",
    // }
});
