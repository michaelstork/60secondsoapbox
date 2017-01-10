
<template>
	<section class="nominees-panel content-panel">
		<div class="panel-content">
			<div class="panel-icon status-indicator-complete" style="background-image:url('images/soapbox.jpg');"></div>
			<h3 v-if="submissionComplete">Thanks for participating!</h3>
			<soapbox-form v-else :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange" v-on:formInput="onFormInput"></soapbox-form>
		</div>
		<nav>
			<button type="submit" :disabled="!panel.valid" v-on:click="doSubmission" class="round">
				<i class="mdi mdi-content-save"></i>
				<span>Submit</span>
			</button>
		</nav>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	import {URLS} from '../../config/index';
	import debounce from 'lodash/debounce';
	import some from 'lodash/some';
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

				if (!target.checkValidity()) {
					this.handleInvalidNominee(field);
					return;
				}

				if (field.async) {
					field.asyncPending = true;
					this.validateNominee(field);
				}
			},
			validateUnique: function (uniqueField) {
				let others = this.form.fields.filter(field => field.name !== uniqueField.name);
				return !some(others, {value: uniqueField.value});
			},
			validateNominee: debounce(function (field) {
				if (!this.validateUnique(field)) {
					this.handleInvalidNominee(field, 'Duplicate!');
				} else {
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
				}
			}, 200),
			handleValidNominee: function (field) {
				field.asyncValid = true;
				field.asyncError = null;
			},
			handleInvalidNominee: function (field, message) {
				field.asyncValid = false;
				field.asyncPending = false;
				if (message) field.asyncError = message;
			}
		}
	}
</script>