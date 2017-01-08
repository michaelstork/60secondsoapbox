
<template>
	<section class="nominees-panel content-panel">
		<div class="panel-content">
			<div class="panel-icon">
				<i class="mdi mdi-account-multiple-plus"></i>
			</div>
			<h3 v-if="submissionComplete">Thanks for participating!</h3>
			<soapbox-form v-else :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange" v-on:formInput="onFormInput"></soapbox-form>
		</div>
		<nav>
			<button type="submit" :disabled="!panel.valid" v-on:click="doSubmission">
				<span>Submit</span>
			</button>
		</nav>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	import {URLS} from '../../config/index';
	import debounce from 'lodash/debounce';
	import {nomineesForm} from '../../forms/index';

	export default {
		extends: SoapboxBasePanel,
		data: function () {
			return {
				form: nomineesForm,
				submissionComplete: false
			};
		},
		computed: {
			isValidPanel: function () {
				return this.form.valid
					&& this.form.fields.filter(field => field.async)
						.every(field => field.asyncValid);
			}
		},
		mounted: function () {
			this.eventHub.$on('submissionComplete', () => {
				this.submissionComplete = true;
			});
		},
		methods: {
			doSubmission: function () {
				if (this.isActivePanel && this.isValidPanel) {
					this.savePanelData();
					this.eventHub.$emit('commitSavedData');
				}
			},
			onFormInput: function (target) {
				const field = this.getFieldByName(target.name);

				if (!field.async) return;

				if (target.checkValidity()) {
					field.asyncPending = true;
					this.validateNominee(field);
				} else {
					this.handleInvalidNominee(field);
				}
			},
			validateNominee: debounce(function (field) {
				return this.$http.post(
					URLS[this.env].validateNominee,
					{email: field.value}
				).then(
					response => {
						this.handleValidNominee(field, response.data.message);
					},
					response => {
						this.handleInvalidNominee(field, response.data.message);
					}
				).finally(() => {
					field.asyncPending = false;
				});
			}, 200),
			handleValidNominee: function (field) {
				field.asyncValid = true;
				field.asyncError = null;
			},
			handleInvalidNominee: function (field, message) {
				field.asyncValid = false;
				if (message) field.asyncError = message;
			}
		}
	}
</script>