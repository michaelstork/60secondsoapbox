
<template>
	<div class="submission-method-record" :class="statusClassList">
		<status-indicator :status="status">
			<div class="status-indicator status-indicator-recording">
				<i class="mdi mdi-microphone"></i>
				<audio-timer :status="status" :reset="!adapter.recordingStarted"></audio-timer>
			</div>
		</status-indicator>		
		<div class="audio-controls">
			<button v-on:click="toggleRecording" class="record-button" :disabled="status === 'pending'" tabIndex="-1">
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
		<nav>
			<button v-on:click="restart" :disabled="audioControlsDisabled" class="restart-audio-button round" tabIndex="-1">
				<i class="mdi mdi-refresh"></i>
				<span>Start Over</span>
			</button>
			<button v-on:click="previewAudio" :disabled="audioControlsDisabled" class="preview-audio-button round" tabIndex="-1">
				<i class="mdi mdi-volume-high"></i>
				<span>Preview</span>
			</button>
			<button v-on:click="requestPanelNavigation" :disabled="audioControlsDisabled || !audioSubmissionValid" class="save-audio-button round" tabIndex="-1">
				<i class="mdi mdi-content-save"></i>
				<span>Save</span>
			</button>
		</nav>
	</div>
</template>

<script>
	import RecordRTCAdapter from '../../adapters/RecordRTC';
	import StatusIndicator from '../audio/StatusIndicator.vue';
	import AudioTimer from '../audio/AudioTimer.vue';

	export default {
		props: ['uploadAudioFile', 'requestPanelNavigation', 'audioSubmissionValid'],
		data: function () {
			return {
				status: 'paused',
				adapter: new RecordRTCAdapter
			};
		},
		computed: {
			statusClassList: function () {
				return 'status-' + this.status;
			},
			audioControlsDisabled: function () {
				return (!this.adapter.recordingStarted || this.status === 'pending');
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
				this.adapter.process(blob => {
					this.uploadAudioFile(blob)
						.then(
							() => { this.status = 'paused'; },
							() => { this.status = 'error'; }
						);
				});
			},
			restart: function () {
				this.$emit('resetPanel');
			}
		},
		components: {
			'status-indicator': StatusIndicator,
			'audio-timer': AudioTimer
		}
	}
</script>