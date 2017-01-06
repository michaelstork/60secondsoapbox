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
			isValidPanel: function (status) {
				this.setPanelValidity(status);
			}
		},
		methods: {
			requestPanelNavigation: function () {
				this.eventHub.$emit('requestPanelNavigation');
			},
			onPanelNavigation: function () {
				if (this.isActivePanel && this.isValidPanel) this.savePanelData();
			},
			setPanelValidity: function (status) {
				this.eventHub.$emit('panelValidityChange', this.panel.name, status);
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
					if (!field.hidden) data[field.name] = field.value;
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