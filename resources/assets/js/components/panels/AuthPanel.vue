
<template>
	<section class="auth-panel content-panel">
		<div class="panel-content">
			<div class="panel-icon"></div>
			<h3 tabIndex="-1">You've been nominated to record an episode of <a href="https://www.aliem.com/category/clinical/60-second-soapbox/" target="_blank" tabIndex="-1">60 Second Soapbox!</a></h3>
			<slot name="auth-content"></slot>
			<soapbox-form :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange" v-on:formInput="onFormInput"></soapbox-form>
		</div>
		<nav>
			<button class="round"
				:disabled="!isValidPanel"
				v-on:click="requestPanelNavigation">
				<i class="mdi mdi-keyboard-backspace mdi-flip-horizontal"></i>
				<span>Continue</span>
			</button>
		</nav>
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
					&& this.getFieldByName('password').asyncValid;
			}
		},
		methods: {
			onFormInput: function () {
				if (this.form.valid) {
					this.getFieldByName('password').asyncPending = true;
					this.validateCode();
				} else {
					this.handleInvalidCode();
				}
			},
			validateCode: debounce(function () {
				const [emailField, codeField] = this.form.fields;

				return this.$http.post(
					URLS.authenticate,
					{
						email: emailField.value,
						password: codeField.value
					}
				).then(
					response => { this.handleValidCode(response.data.token); },
					response => { this.handleInvalidCode(response.data.message || null); }
				).finally(() => {
					codeField.asyncPending = false;
				});

			}, 200),
			handleValidCode: function (token) {
				this.getFieldByName('password').asyncValid = true;
				localStorage.setItem('soapboxToken', token);
			},
			handleInvalidCode: function () {
				this.getFieldByName('password').asyncValid = false;
				localStorage.removeItem('soapboxToken');
			}
		}
	}
</script>