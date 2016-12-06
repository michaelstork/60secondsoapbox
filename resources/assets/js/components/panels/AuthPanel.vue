
<template>
	<section class="auth-panel content-panel" v-on:dblclick="fillForm">
		<h3 tabIndex="-1">You've been invited to record an episode of <br class="mobile-break"><a href="https://www.aliem.com/category/clinical/60-second-soapbox/" target="_blank" tabIndex="-1">60 Second Soapbox!</a></h3>
		<p class="intro">Any topic is on the table – clinical, academic, economic, or whatever else may interest an EM-centric audience. We carefully remix your audio to add an extra splash of drama and excitement. Even more exciting, you'll get to challenge 3 of your peers to stand on a soapbox of their own!</p>
		<p>If you'd like to participate, enter your invitation code below!</p>
		<soapbox-form :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange" v-on:formInput="onFormInput"></soapbox-form>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	import {URLS} from '../../config/index';
	import debounce from 'lodash/debounce';
	import {authForm} from '../../forms/index';

	export default {
		extends: SoapboxBasePanel,
		data: function () {
			return {
				form: authForm
			};
		},
		computed: {
			isValidPanel: function () {
				return this.form.valid
					&& this.form.fields.filter(field => field.async)
						.every(field => field.asyncValid);
			}
		},
		methods: {
			onFormInput: function (target) {
				const field = this.getFieldByName(target.name);

				if (!field.async) return;

				if (target.checkValidity()) {
					this.validateCode(field);
				} else {
					this.handleInvalidCode(field);
				}
			},
			validateCode: debounce(function () {
				const [emailField, codeField] = this.form.fields;

				if (!emailField.value || !codeField.value) return;

				return this.$http.post(
					URLS[this.env].authenticate,
					{
						email: emailField.value,
						password: codeField.value
					}
				).then(
					() => { this.handleValidCode(codeField); },
					() => { this.handleInvalidCode(codeField); }
				);
			}, 200),
			handleValidCode: function () {
				this.getFieldByName('password').asyncValid = true;
				this.getFieldByName('email').asyncValid = true;
				// if (this.isValidPanel) this.setPanelValid();
			},
			handleInvalidCode: function () {
				this.getFieldByName('password').asyncValid = false;
				this.getFieldByName('email').asyncValid = false;
				// if (this.panel.valid) this.setPanelInvalid();
			},
			fillForm: function () {
				this.getFieldByName('password').value = '12345678';
				this.getFieldByName('email').value = 'michael@mstork.com';
				this.onFormValidityChange(true);
				this.validateCode();
			}
		}
	}
</script>