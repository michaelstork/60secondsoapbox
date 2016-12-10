
<template>
	<section class="info-panel content-panel">
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
		}
	}
</script>