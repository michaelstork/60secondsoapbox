
<template>
	<div class="panel-icon">
		<div v-if="!adapter.supported" class="status-indicator status-indicator-unsupported">
			<i class="mdi mdi-volume-off"></i>
		</div>
		<div v-else-if="adapter.status === 'processing'" class="status-indicator status-indicator-processing">
			
		</div>
		<div v-else-if="adapter.status === 'complete'" class="status-indicator status-indicator-complete">
			
		</div>
		<div v-else class="status-indicator status-indicator-supported">
			
		</div>
<!-- 		<div class="status-indicator status-indicator-recording-status">
			<i class="mdi mdi-microphone"></i>
			<soapbox-audio-timer :status="adapter.status"></soapbox-audio-timer>
		</div>
		<div v-if="!adapter.status.supported" class="status-indicator status-indicator-unsupported">
			<i class="mdi mdi-alert"></i>
		</div>
		<transition name="audio-indicator" mode="in-out">
			<div v-if="adapter.status.pending" class="status-indicator status-indicator-pending" key="pending">
				<i class="mdi mdi-cloud-upload"></i>
			</div>
			<div v-else-if="adapter.status.complete" class="status-indicator status-indicator-complete" key="complete">
				<i class="mdi mdi-check"></i>
			</div>
		</transition> -->
	</div>
</template>

<script>
	import SoapboxAudioTimer from './SoapboxAudioTimer.vue';

	export default {
		props: ['adapter'],
		computed: {

		},
		components: {
			'soapbox-audio-timer': SoapboxAudioTimer
		}
	}
</script>