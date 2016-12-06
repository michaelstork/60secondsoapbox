
<template>
	<section class="info-panel content-panel"  v-on:dblclick="fillForm">
		<soapbox-form :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange"></soapbox-form>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	import {infoForm} from '../../forms/index';

	export default {
		extends: SoapboxBasePanel,
		data: function () {
			return {
				form: infoForm
			};
		},
		methods: {
			fillForm: function () {
				this.getFieldByName('name').value = 'NAME';
				this.getFieldByName('title').value = 'TITLE';
				this.getFieldByName('institution').value = 'INSTITUTION';
				this.onFormValidityChange(true);
			}
		}
	}
</script>