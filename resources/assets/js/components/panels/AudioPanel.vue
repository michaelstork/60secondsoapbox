
<template>
	<section class="audio-panel content-panel" v-on:dblclick="fillForm">
		<div class="soapbox-recorder">
			<soapbox-status-indicator :adapter="audioAdapter"></soapbox-status-indicator>
			<h3 v-if="!audioAdapter.status.supported" class="audio-not-supported">Your {{ env == 'cordova' ? 'device' : 'browser' }} does not support recording audio.</h3>
			<transition name="audio-content" mode="out-in">
				<div v-if="!audioAdapter.initialized" class="request-audio" key="requestAudio">
					<template v-if="audioAdapter.status.supported">
						<button v-on:click="initializeAudio" :disabled="audioAdapter.status.complete" tabIndex="-1">
							<span>Get Started</span>
						</button>
						<small>Your device may request permission<br class="break-below-400"> to access the microphone.</small>
					</template>
					<div class="audio-upload">
						<span>Already have an audio file?</span>
						<a tabIndex="-1" v-file-upload-link:audioUpload="uploadAudioFile" href="#">Click here to upload it.</a>
					</div>
				</div>
				<div v-else class="audio-controls" key="audioControls">
					<p v-html="instructions"></p>
					<button v-on:click="toggle" :disabled="audioAdapter.status.complete" :class="{'active': audioAdapter.status.recording}" class="record-button">
						<span>{{ audioAdapter.status.recording ? 'Pause' : 'Record' }}</span>
					</button>
					<button v-on:click="stop" :disabled="(!audioAdapter.status.started || audioAdapter.status.complete)" class="stop-button">
						<span>Stop</span>
					</button>
					<a v-on:click="restart" :disabled="!audioAdapter.status.started"><i class="mdi mdi-refresh"></i>Start Over</a>
				</div>
			</transition>
		</div>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	import FileUploadLink from '../../directives/fileUploadLink';
	import SoapboxStatusIndicator from '../audio/SoapboxStatusIndicator.vue';
	import RecordRTCAdapter from '../../adapters/RecordRTC';
	import {URLS} from '../../config/index';

	export default {
		extends: SoapboxBasePanel,
		data: function () {
			const adapter = new RecordRTCAdapter;

			return {
				blob: null,
				filename: null,
				audioAdapter: adapter
			};
		},
		computed: {
			isValidPanel: function () {
				return this.audioAdapter.status.complete
					&& this.filename.length;
			},
			instructions: function () {
				if (this.audioAdapter.status.complete) {
					return 'Upload Complete!';
				} else if (this.audioAdapter.status.pending) {
					return 'Uploading your submission...';
				} else {
					let action = this.env === 'cordova' ? 'press' : 'click';
					return `
						Use the button below to record your 60 seconds of audio.
						<br class="break-above-400">
						When you're done, ${action} 'Stop' to upload your submission.
					`;
				}
			}
		},
		methods: {
			initializeAudio: function () {
				this.audioAdapter.initialize();
			},
			uploadAudioFile: function (data) {
				this.audioAdapter.status.pending = true;

				let formData = new FormData();
				formData.append('audioUpload', data);

				return this.$http.post(
					URLS[this.env].audioUpload,
					formData,
					{progress: pe => { console.log(pe); }}
				).then(
					this.handleUploadSuccess,
					this.handleUploadFailure
				).finally(() => {
					this.audioAdapter.status.pending = false;
				});
			},
			handleUploadSuccess: function (response) {
				console.log(response);
				this.audioAdapter.status.complete = true;
				this.filename = response.data.filename;
			},
			handleUploadFailure: function (response) {
				console.log(response);
				this.audioAdapter.status.complete = false;
			},
			composePanelData: function () {
				return {filename: this.filename};
			},
			toggle: function () {
				let method = this.audioAdapter.status.recording
					? 'pause'
					: this.audioAdapter.status.started
						? 'resume'
						: 'start';

				this.audioAdapter[method].call(this.audioAdapter);
			},
			stop: function () {
				this.audioAdapter.stop(this.uploadAudioFile.bind(this));
			},
			restart: function () {
				if (!window.confirm('Are you sure you want to start over?')) return;
				this.audioAdapter.restart();
				this.setPanelInvalid();
			},
			fillForm: function () {
				this.handleUploadSuccess({data:{filename: 'filename'+Math.floor(Math.random(5) * 1000)+'.wav'}});
			}
		},
		directives: {
			fileUploadLink: FileUploadLink
		},
		components: {
			'soapbox-status-indicator': SoapboxStatusIndicator
		}
	}
</script>