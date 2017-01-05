
<template>
	<div class="panel-icon">
		<slot></slot>
		<transition name="status-indicator">
			<div v-if="status === 'pending'" class="status-indicator status-indicator-pending" key="pending">
				<i class="mdi mdi-cloud-upload"></i>
			</div>
			<div v-else-if="status === 'complete'" class="status-indicator status-indicator-complete" key="complete">
				<i class="mdi mdi-check"></i>
			</div>
			<div v-else-if="status === 'error'" class="status-indicator status-indicator-error" key="error">
				<i class="mdi mdi-alert"></i>
			</div>
		</transition>
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
	export default {
		props: ['status']
	}
</script>