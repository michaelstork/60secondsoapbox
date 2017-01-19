import Vue from 'vue';
import SoapboxForm from '../components/forms/SoapboxForm.vue';
import SoapboxFormField from '../components/forms/SoapboxFormField.vue';

const appData = {
	loginForm: {
		"name": "login",
		"fields": [
			{
				"name": "email",
				"value": "",
				"label": "Your Email",
				"required": true,
				"type": "email"
			},
			{
				"name": "password",
				"type": "password",
				"value": "",
				"label": "Password",
				"required": true,
				"pattern": ".{8,}",
				"async": true,
				"hidden": true
			}
		]
	}
};

window.soapboxLogin = new Vue({
	el: '.vue-mount',
	data: appData,
	methods: {
	},
	components: {
		'soapbox-form': SoapboxForm,
		'soapbox-form-field': SoapboxFormField
	}
});