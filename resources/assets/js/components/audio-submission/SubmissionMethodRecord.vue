
<template>
	<div class="submission-method-record" :class="statusClassList">
		<status-indicator :status="status">
			<div class="status-indicator status-indicator-recording">
				<i class="mdi mdi-microphone"></i>
				<audio-timer :status="status" :reset="!adapter.recordingStarted"></audio-timer>
			</div>
		</status-indicator>		
		<div class="audio-controls">
			<button v-on:click="toggleRecording" class="record-button" :disabled="status === 'pending'">
				<div class="record-button-content">
					<i class="mdi mdi-record"></i>
					<span>{{ adapter.recordingStarted ? 'Resume Recording' : 'Start Recording' }}</span>
				</div>
				<div class="pause-button-content">
					<i class="mdi mdi-pause-circle"></i>
					<span>Pause Recording</span>
				</div>
			</button>
			<!-- <a v-on:click="restart" :disabled="!adapter.recordingStarted || status === 'pending'">
				<i class="mdi mdi-refresh"></i>
				<span>Start Over</span>
			</a> -->
			
			<button v-on:click="previewAudio" :disabled="!adapter.recordingStarted" class="preview-audio round">
				<i class="mdi mdi-volume-high"></i>
				<span>Preview</span>
			</button>
			<button v-on:click="restart" :disabled="!adapter.recordingStarted || status === 'pending'" class="restart-audio round">
				<i class="mdi mdi-refresh"></i>
				<span>Start Over</span>
			</button>

		</div>
	</div>
</template>

<script>
	import RecordRTCAdapter from '../../adapters/RecordRTC';
	import StatusIndicator from '../audio/SoapboxStatusIndicator.vue';
	import AudioTimer from '../audio/AudioTimer.vue';

	export default {
		props: ['uploadAudioFile'],
		data: function () {
			return {
				status: 'paused',
				adapter: new RecordRTCAdapter
			};
		},
		computed: {
			statusClassList: function () {
				return 'status-' + this.status;
			}
		},
		created: function () {
			this.adapter.initialize();
		},
		methods: {
			toggleRecording: function () {
				if (this.status === 'recording') {
					this.status = 'paused';
					this.adapter.pause();
				} else {
					this.$emit('setAudioPreviewStatus', false);
					this.adapter.resume();
					this.status = 'recording';
				}
			},
			previewAudio: function () {
				this.status = 'pending';
				// this.$emit('setAudioPreviewStatus', true);
				this.adapter.process(blob => {
					this.uploadAudioFile(blob)
						.then(
							() => { this.status = 'paused'; },
							() => { this.status = 'error'; }
						);
				});
			},
			restart: function () {
				if (!window.confirm('Are you sure you want to start over?')) return;
				this.$emit('setAudioPreviewStatus', false);
				this.adapter.restart();
				this.status = 'paused';
			}
		},
		components: {
			'status-indicator': StatusIndicator,
			'audio-timer': AudioTimer
		}
	}
</script>