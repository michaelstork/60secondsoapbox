
<template>
	<section class="nominees-panel content-panel" v-on:dblclick="fillForm">
		<soapbox-form :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange" v-on:formInput="onFormInput"></soapbox-form>
		<button type="submit" :disabled="!panel.valid" v-on:click="doSubmission">
			<span>Submit</span>
		</button> 
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
				form: nomineesForm
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
					() => { this.handleValidNominee(field); },
					() => { this.handleInvalidNominee(field); }
				);
			}, 200),
			handleValidNominee: function (field) {
				field.asyncValid = true;
				// if (this.isValidPanel) this.setPanelValid();
			},
			handleInvalidNominee: function (field) {
				field.asyncValid = false;
				// if (this.panel.valid) this.setPanelInvalid();
			},
			fillForm: function () {
				this.getFieldByName('title').value = 'TITLE';
				let n1 = this.getFieldByName('nominee1');
				n1.value = 'michael@mstork.com1';
				this.handleValidNominee(n1);
				let n2 = this.getFieldByName('nominee2');
				n2.value = 'michael@mstork.com2';
				this.handleValidNominee(n2);
				let n3 = this.getFieldByName('nominee3');
				n3.value = 'michael@mstork.com3';
				this.handleValidNominee(n3);
				this.onFormValidityChange(true);
			}
		}
	}
</script>