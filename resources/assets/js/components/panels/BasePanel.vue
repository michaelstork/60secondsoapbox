<script>
	import SoapboxForm from '../forms/SoapboxForm.vue';
	import SoapboxFormField from '../forms/SoapboxFormField.vue';

	export default {
		created: function () {
			this.eventHub.$on('panelNavigation', this.onPanelNavigation);
		},
		props: ['panel', 'isActivePanel'],
		computed: {
			isValidPanel: function () {
				return (this.form && this.form.valid);
			}
		},
		watch: {
			'isValidPanel': function (valid) {
				this[valid ? 'setPanelValid' : 'setPanelInvalid'].call(this);
			}
		},
		methods: {
			onPanelNavigation: function () {
				if (this.isActivePanel && this.isValidPanel) this.savePanelData(); 
			},
			setPanelValid: function () {
				this.eventHub.$emit('panelValidityChange', this.panel.name, true);
			},
			setPanelInvalid: function () {
				this.eventHub.$emit('panelValidityChange', this.panel.name, false);
			},
			getFieldByName: function (name) {
				return this.form.fields.find(field => field.name === name);
			},
			onFormValidityChange: function (status) {
				this.form.valid = status;
			},
			savePanelData: function () {
				console.log('saving panel '+this.panel.name);
				const submission = JSON.parse(localStorage.getItem('soapboxSubmission')) || {};
				submission[this.panel.name] = this.composePanelData();
				localStorage.setItem('soapboxSubmission', JSON.stringify(submission));
			},
			composePanelData: function () {
				return this.form.fields.reduce((data, field) => {
					data[field.name] = field.value;
					return data;
				}, {});
			}
		},
		components: {
			'soapbox-form': SoapboxForm,
			'soapbox-form-field': SoapboxFormField
		}
	}
</script>