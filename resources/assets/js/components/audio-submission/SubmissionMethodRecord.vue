
<template>
	<div class="submission-method-record" :class="statusClassList">
		<div class="panel-icon">
			<transition name="status-indicator">
				<div v-if="status === 'pending'" class="status-indicator status-indicator-pending" key="pending">
					<i class="mdi mdi-cloud-upload"></i>
				</div>
				<div v-else-if="status === 'complete'" class="status-indicator status-indicator-complete" key="complete">
					<i class="mdi mdi-check"></i>
				</div>
				<div v-else class="status-indicator status-indicator-error" key="error">
					<i class="mdi mdi-alert"></i>
				</div>
			</transition>
		</div>
		<div class="audio-controls">
			<button v-on:click="toggleRecording" class="record-button" :disabled="status === 'pending'">
				<div class="record">
					<i class="mdi mdi-record"></i>
					<span>{{ adapter.recordingStarted ? 'Resume Recording' : 'Start Recording' }}</span>
				</div>
				<div class="pause">
					<i class="mdi mdi-pause-circle"></i>
					<span>Pause Recording</span>
				</div>
			</button>
			<a v-on:click="restart" :disabled="!adapter.recordingStarted || status === 'pending'">
				<i class="mdi mdi-refresh"></i>
				<span>Start Over</span>
			</a>
			<!-- <div>
				<a v-on:click="restart" :disabled="!adapter.recordingStarted || status === 'pending'">
					<i class="mdi mdi-refresh"></i>
					<span>Start Over</span>
				</a>
				<a v-on:click="previewAudio" :disabled="!adapter.recordingStarted">
					<i class="mdi mdi-volume-high"></i>
					<span>Preview</span>
				</a>
			</div> -->
			<button v-on:click="previewAudio" :disabled="!adapter.recordingStarted" class="preview-audio round">
				<i class="mdi mdi-volume-high"></i>
				<span>Preview</span>
			</button>
			<!-- <a v-on:click="previewAudio" :disabled="!adapter.recordingStarted">
				<i class="mdi mdi-volume-high"></i>
				<span>Preview</span>
			</a> -->
		</div>
		<!-- <div :class="'status-'+status">
			<p class="status-message-pending form-header">Uploading {{ file.name }}...</p>
			<p class="status-message-complete form-header">Upload Complete!</p>
			<p class="status-message-error form-header">
				Oops! Something went wrong.
				<br>
				<a href="#" v-file-upload-link:audioUpload="retryUpload">Click here to try again.</a>
			</p>
		</div> -->
	</div>
</template>

<script>
	import RecordRTCAdapter from '../../adapters/RecordRTC';

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
		}
	}
</script>