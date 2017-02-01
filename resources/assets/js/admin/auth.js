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
				"hidden": true
			}
		]
	},
	emailForm: {
		"name": "email",
		"fields": [
			{
				"name": "email",
				"value": "",
				"label": "Your Email",
				"required": true,
				"type": "email"
			}
		]
	},
	resetForm: {
		"name": "reset",
		"fields": [
			{
				"name": "email",
				"value": "",
				"label": "Email",
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
				"hidden": true
			},
			{
				"name": "password_confirmation",
				"type": "password",
				"value": "",
				"label": "Confirm Password",
				"required": true,
				"pattern": ".{8,}",
				"hidden": true
			}
		]
	}
};

window.soapboxAuth = new Vue({
	el: '.vue-mount',
	data: appData,
	components: {
		'soapbox-form': SoapboxForm,
		'soapbox-form-field': SoapboxFormField
	}
});