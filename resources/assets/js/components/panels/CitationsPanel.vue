
<template>
	<section class="citations-panel content-panel">
		<div class="panel-content">
			<div class="panel-icon">
				<i class="mdi mdi-book-open-variant"></i>
			</div>
			<soapbox-form :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange"></soapbox-form>
		</div>
		<nav>
			<button class="round"
				:disabled="!isValidPanel"
				v-on:click="requestPanelNavigation">
				<i class="mdi mdi-keyboard-backspace mdi-flip-horizontal"></i>
				<span>Continue</span>
			</button>
		</nav>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	import {citationsForm} from '../../forms/index';

	export default {
		extends: SoapboxBasePanel,
		data: function () {
			return {
				form: citationsForm
			};
		}
	}
</script>