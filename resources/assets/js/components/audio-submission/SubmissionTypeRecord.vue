
<template>
	<div class="submission-type-record" :class="statusClassList">
		<div class="panel-content">
			<status-indicator :status="status">
				<div class="status-indicator status-indicator-recording">
					<i class="mdi mdi-microphone"></i>
					<audio-timer
						:status="status"
						:reset="!audio.adapter.recordingStarted"
						:update="updateRecordedDuration"
						:audioEventHub="audioEventHub">
					</audio-timer>
				</div>
			</status-indicator>
			<p v-if="message" v-html="message" class="message"></p>
			<button v-on:click="toggleRecording" class="record-button rect" :disabled="status === 'pending'" tabIndex="-1">
				<div class="record-button-content">
					<i class="mdi mdi-record"></i>
					<span>{{ audio.adapter.recordingStarted ? 'Resume Recording' : 'Start Recording' }}</span>
				</div>
				<div class="pause-button-content">
					<i class="mdi mdi-pause-circle"></i>
					<span>Pause Recording</span>
				</div>
			</button>
		</div>
		<slot name="nav"></slot>
	</div>
</template>

<script>
	import StatusIndicator from '../audio/StatusIndicator.vue';
	import AudioTimer from '../audio/AudioTimer.vue';
	import { AUDIO } from '../../config';

	const defaultMessage = `
		Use the button below to record <span>${AUDIO.minDuration / 1000} - ${AUDIO.maxDuration / 1000} seconds</span> of audio.
		<br>
		When you're finished, click continue to proceed.
	`;

	export default {
		props: ['uploadAudioFile', 'audioEventHub', 'audio'],
		data: function () {
			return {
				status: 'paused',
				recordedDuration: 0,
				message: defaultMessage
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
			this.audio.adapter.initialize();
		},
		activated: function () {
			this.subscribeAudioEvents();
			this.message = defaultMessage;
		},
		deactivated: function () {
			this.unsubscribeAudioEvents();
		},
		methods: {
			subscribeAudioEvents: function () {
				this.audioEventHub.$on('requestAudioPreview', this.onRequestAudioPreview);
				this.audioEventHub.$on('audioReset', this.onAudioReset);
				this.audioEventHub.$on('maxDurationReached', this.toggleRecording);
			},
			unsubscribeAudioEvents: function () {
				this.audioEventHub.$off('requestAudioPreview', this.onRequestAudioPreview);
				this.audioEventHub.$off('audioReset', this.onAudioReset);
				this.audioEventHub.$off('maxDurationReached', this.toggleRecording);
			},
			updateRecordedDuration: function (milliseconds) {
				this.recordedDuration = milliseconds;
				if (this.recordedDuration >= AUDIO.minDuration
					&& this.status !== 'pending') {
					this.onRequestAudioPreview();
				}
			},
			toggleRecording: function () {
				if (this.status === 'recording') {
					this.status = 'paused';
					this.audio.adapter.pause();
				} else {
					this.audio.adapter.resume();
					this.status = 'recording';
				}
			},
			onRequestAudioPreview: function () {
				this.status = 'pending';
				this.audio.adapter.process(blob => {
					this.uploadAudioFile(blob)
						.then(() => {
							if (this.audio.valid) {
								this.status = 'complete';
								this.message = 'That\'s good, thanks! Click continue to proceed.';
							} else {
								this.status = 'paused';
							}
						}, error => {
							this.status = 'error';
							this.message = 'Uh oh, something went wrong';
							console.log(error);
						});
				});
			},
			onAudioReset: function () {
				this.audio.adapter.pause();
				this.status = 'paused';

				if (this.audio.adapter.recordingStarted) {
					this.audio.adapter.restart();
				}
			}
		},
		components: {
			'status-indicator': StatusIndicator,
			'audio-timer': AudioTimer
		}
	}
</script>