import Vue from 'vue';
import Resource from 'vue-resource';
import Dashboard from './components/SoapboxDashboard.vue';

Vue.use(Resource);

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', window.Laravel.csrfToken);
    next();
});

const appData = {
	users: window.soapboxUsers.slice(0)
};

window.soapboxAdmin = new Vue({
	el: '.vue-mount',
	data: appData,
	methods: {

	},
	components: {
		'soapbox-dashboard': Dashboard
	}
});