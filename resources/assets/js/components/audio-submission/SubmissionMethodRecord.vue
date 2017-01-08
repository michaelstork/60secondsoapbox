
<template>
	<div class="submission-method-record" :class="statusClassList">
		<div class="panel-content">
			<status-indicator :status="status">
				<div class="status-indicator status-indicator-recording">
					<i class="mdi mdi-microphone"></i>
					<audio-timer :status="status" :reset="!adapter.recordingStarted"></audio-timer>
				</div>
			</status-indicator>		
			<button v-on:click="toggleRecording" class="record-button rect" :disabled="status === 'pending'" tabIndex="-1">
				<div class="record-button-content">
					<i class="mdi mdi-record"></i>
					<span>{{ adapter.recordingStarted ? 'Resume Recording' : 'Start Recording' }}</span>
				</div>
				<div class="pause-button-content">
					<i class="mdi mdi-pause-circle"></i>
					<span>Pause Recording</span>
				</div>
			</button>
		</div>
		<slot></slot>
	</div>
</template>

<script>
	import StatusIndicator from '../audio/StatusIndicator.vue';
	import AudioTimer from '../audio/AudioTimer.vue';

	export default {
		props: ['uploadAudioFile', 'audioEventHub', 'adapter'],
		data: function () {
			return {
				status: 'paused'
			};
		},
		computed: {
			statusClassList: function () {
				return 'status-' + this.status;
			}
		},
		watch: {
			status: function (status) {
				this.audioEventHub.$emit('recordingStatusChange', status);
			}
		},
		created: function () {
			this.adapter.initialize();
		},
		mounted: function () {
			this.audioEventHub.$on('requestAudioPreview', this.onRequestAudioPreview);
		},
		methods: {
			toggleRecording: function () {
				if (this.status === 'recording') {
					this.status = 'paused';
					this.adapter.pause();
				} else {
					this.adapter.resume();
					this.status = 'recording';
				}
			},
			onRequestAudioPreview: function () {
				this.status = 'pending';
				this.adapter.process(blob => {
					this.uploadAudioFile(blob)
						.then(
							() => { this.status = 'paused'; },
							() => { this.status = 'error'; }
						);
				});
			}
		},
		components: {
			'status-indicator': StatusIndicator,
			'audio-timer': AudioTimer
		}
	}
</script>