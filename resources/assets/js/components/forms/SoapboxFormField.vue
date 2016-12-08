
<template>
	<div class="input-container"
		:class="{
			'empty': !value,
			'async-valid': asyncValid,
			'async-error': asyncError
		}">
		<input
			ref="input"
			:type="type"
			:value="value"
			:name="name"
			:required="required"
			:pattern="pattern"
			v-on:input="updateValue($event.target.value)"
			spellcheck="false"
			autocomplete="off" />
		<label>{{ label }}</label>
		<i class="underline"></i>
		<template v-if="async">
			<i class="mdi mdi-check async-valid-indicator"></i>
			<i class="mdi mdi-exclamation async-invalid-indicator"></i>
			<span class="async-error-message">{{ asyncError }}</span>
		</template>
	</div>
</template>

<script>
	export default {
		props: {
			value: [String, Number],
			name: String,
			label: String,
			required: Boolean,
			pattern: String,
			valid: Boolean,
			type: {
				type: String,
				default: 'text'
			},
			async: Boolean,
			asyncValid: Boolean,
			asyncError: String
		},
		methods: {
			updateValue: function (val) {
				this.$emit('input', val);

				if (this.valid !== this.$refs.input.checkValidity()) {
					this.$emit('fieldValidityChange', this.name, this.$refs.input.checkValidity());
				}
			}
		}
	}
</script>