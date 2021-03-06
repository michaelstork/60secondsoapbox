
<template>
	<form ref="form" v-on:input="onReceivedInput" v-on:submit="onSubmit" method="POST">
		<fieldset :disabled="disabled">
			<div class="form-input-container">
				<slot name="form-top"></slot>
				<template v-for="field in form.fields">
					<h3 v-if="field.heading">{{ field.heading }}</h3>
					<soapbox-form-field
						v-model="field.value"
						:label="field.label"
						:name="field.name"
						:required="field.required"
						:type="field.type"
						:pattern="field.pattern"
						:valid="fieldValidityStatuses[field.name]"
						:async="field.async"
						:async-valid="field.asyncValid"
						:async-pending="field.asyncPending"
						:async-error="field.asyncError"
						v-on:fieldValidityChange="onFieldValidityChange">
					</soapbox-form-field>
				</template>
				<slot name="form-bottom"></slot>
			</div>
		</fieldset>
	</form>
</template>

<script>
	import every from 'lodash/every';

	function initValidityStatuses(fields) {
		return fields.reduce((data, field) => {
			data[field.name] = false;
			return data;
		}, {});
	}

	export default {
		props: ['form', 'disabled'],
		data: function () {
			return {
				fieldValidityStatuses: initValidityStatuses(this.form.fields)
			};
		},
		computed: {
			isValidForm: function () {
				return every(this.fieldValidityStatuses);
			}
		},
		methods: {
			onReceivedInput: function (e) {
				this.$emit('formInput', e.target);
			},
			onFieldValidityChange: function (name, status) {
				this.fieldValidityStatuses[name] = status;
				if (this.form.valid !== this.isValidForm) {
					this.$emit('formValidityChange', this.isValidForm);
				}
			},
			onSubmit: function (e) {
				if (this.form.preventSubmit) e.preventDefault();
			}
		},
		components: {
			'soapbox-form-field': require('./SoapboxFormField.vue')
		}
	}
</script>