
<template>
	<div class="input-container"
		:class="{
			'empty': !value,
			'async-valid': asyncValid,
			'async-pending': asyncPending,
			'async-error': asyncError,
			'textarea-container': type == 'textarea'
		}">
		
		<input v-if="type !== 'textarea'"
			ref="input"
			:type="type"
			:value="value"
			:name="name"
			:required="required"
			:pattern="pattern"
			v-on:input="updateValue($event.target.value)"
			spellcheck="false"
			autocomplete="off" />
		
		<textarea v-else
			ref="input"
			:value="value"
			:name="name"
			:required="required"
			:pattern="pattern"
			v-on:input="updateValue($event.target.value)"
			spellcheck="false"></textarea>
		
		<label>{{ label }}</label>
		<i class="underline"></i>
		
		<template v-if="async">
			<i class="mdi mdi-check async-valid-indicator"></i>
			<i class="mdi mdi-exclamation async-invalid-indicator"></i>
			<svg class="async-pending-indicator" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
			</svg>
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
			asyncPending: Boolean,
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