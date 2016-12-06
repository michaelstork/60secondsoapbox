
<template>
	<div class="panel-icon audio-status" :class="adapterStatusClasses">
		<div class="status-indicator status-indicator-recording-status">
			<i class="mdi mdi-microphone"></i>
			<soapbox-audio-timer :status="adapter.status"></soapbox-audio-timer>
		</div>
		<transition name="audio-indicator">
			<div v-if="adapter.status.pending" class="status-indicator status-indicator-pending" key="pending">
				<i class="mdi mdi-poop"></i>
			</div>
			<div v-if="adapter.status.complete" class="status-indicator status-indicator-complete" key="complete">
				<i class="mdi mdi-check"></i>
			</div>
		</transition>
		<div v-if="!adapter.status.supported" class="status-indicator status-indicator-unsupported">
			<i class="mdi mdi-alert"></i>
		</div>
	</div>
</template>

<script>
	import SoapboxAudioTimer from './SoapboxAudioTimer.vue';

	export default {
		props: ['adapter'],
		computed: {
			adapterStatusClasses: function () {
				return {
					'status-unsupported': !this.adapter.status.supported,
					'status-recording'  : this.adapter.status.recording,
					'status-pending'    : this.adapter.status.pending,
					'status-complete'   : this.adapter.status.complete
				};
			}
		},
		components: {
			'soapbox-audio-timer': SoapboxAudioTimer
		}
	}
</script>