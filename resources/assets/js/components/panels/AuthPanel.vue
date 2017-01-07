
<template>
	<section class="auth-panel content-panel" v-on:dblclick="fillForm">
		<h3 tabIndex="-1">You've been nominated to record an episode of <a href="https://www.aliem.com/category/clinical/60-second-soapbox/" target="_blank" tabIndex="-1">60 Second Soapbox!</a></h3>
		<p class="intro">Any topic is on the table – clinical, academic, economic, or whatever else may interest an EM-centric audience. We carefully remix your audio to add an extra splash of drama and excitement. Even more exciting, you'll get to challenge 3 of your peers to stand on a soapbox of their own!</p>
		<p>If you'd like to participate, enter your invitation code below!</p>
		<soapbox-form :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange" v-on:formInput="onFormInput"></soapbox-form>
		<nav v-on:touchstart="fillForm">
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
					URLS[this.env].authenticate,
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
			},


			fillForm: function () {
				this.getFieldByName('password').value = 'hockey11';
				this.getFieldByName('email').value = 'michael@mstork.info';
				this.onFormValidityChange(true);
				this.validateCode();
			}
		}
	}
</script>