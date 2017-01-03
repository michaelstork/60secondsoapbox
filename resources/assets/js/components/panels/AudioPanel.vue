
<template>
	<section class="audio-panel content-panel">
		<!-- <soapbox-status-indicator :adapter="adapter"></soapbox-status-indicator> -->

		<!-- <div v-show="!submissionMethod" class="submission-method">
			<p class="form-header">Choose how you'd like to submit your recording:</p>
			<p>If your device has a microphone and supports audio capture, you can record your submission here.<br>Alternatively, you may upload your own audio file in wav or mp3 format.</p>
			<div class="submission-method-dialog">
				<button v-on:click="setSubmissionMethod('record')" class="submission-method-record" :class="{disabled:!canRecordAudio}">
					<i class="mdi mdi-voice"></i>
					<span>Record my voice</span>
				</button>
				<span>Or</span>
				<button v-on:click="setSubmissionMethod('upload')" class="submission-method-upload">
					<i class="mdi mdi-cloud-upload"></i>
					<span>Upload audio file</span>
				</button>
			</div>
		</div> -->
		<transition name="fade" mode="out-in">
			<submission-method-dialog
				v-if="!submissionMethod"
				v-on:setSubmissionMethod="setSubmissionMethod"
				v-on:setSelectedFile="setSelectedFile">
			</submission-method-dialog>
			<component v-else :is="subComponentId" :uploadAudioFile="uploadAudioFile" :selectedFile="selectedFile"></component>
		</transition>

		<soapbox-wave-surfer :url="filename"></soapbox-wave-surfer>

		<!-- <a ref="fileUploadLink" v-file-upload-link:audioUpload="uploadAudioFile"></a> -->

<!-- 
		<div v-else-if="adapter.initialized">
			<h1>asdf</h1>
		</div>
		<div v-else-if="chosenFile">
			<p>Uploading {{ chosenFile.name }}...</p>
			
		</div> -->


		<!-- <div v-if="!adapter.supported" key="unsupported">
			<h1>unsupported</h1>
		</div>
		<div v-else-if="!adapter.initialized" key="uninitialized">
			<h1 v-on:click="initializeAdapter">uninit</h1>
		</div>
		<div v-else key="initialized">
			<h1>init</h1>
		</div> -->

		<!-- <transition name="audio-content" mode="out-in">
			<div v-if="!audioAdapter.initialized" class="request-audio" key="requestAudio">
				<template v-if="audioAdapter.status.supported">
					<button v-on:click="initializeAudio" :disabled="audioAdapter.status.complete" tabIndex="-1">
						<span>Get Started</span>
					</button>
					<small>Your device may request permission<br class="break-below-400"> to access the microphone.</small>
				</template>
				<p v-else v-html="instructions"></p>
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
				<a v-on:click="restart" :disabled="!audioAdapter.status.started" class="restart"><i class="mdi mdi-refresh"></i>Start Over</a>
			</div>
		</transition> -->
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	// import FileUploadLink from '../../directives/fileUploadLink';
	import SoapboxStatusIndicator from '../audio/SoapboxStatusIndicator.vue';
	import RecordRTCAdapter from '../../adapters/RecordRTC';
	import {URLS} from '../../config/index';

	import SoapboxWaveSurfer from '../audio/SoapboxWaveSurfer.vue';

	import SubmissionMethodDialog from '../audio-submission/SubmissionMethodDialog.vue';
	import SubmissionMethodUpload from '../audio-submission/SubmissionMethodUpload.vue';
	import SubmissionMethodRecord from '../audio-submission/SubmissionMethodRecord.vue';

	export default {
		extends: SoapboxBasePanel,
		data: function () {

			return {
				chosenFile: null,
				adapter: new RecordRTCAdapter,
				submissionMethod: null,
				selectedFile: null,
				filename: null
			};

			// const adapter = new RecordRTCAdapter;

			// return {
			// 	adapter: adapter
			// };
		},
		computed: {
			isValidPanel: function () {
				return true;
			},
			canRecordAudio: function () {
				return RecordRTCAdapter.isSupported();
			},
			subComponentId: function () {
				return 'submission-method-' +this.submissionMethod;
			}
		},
		// watch: {
		// 	submissionMethod: function (method) {
		// 		console.log(method);
		// 		if (method === 'upload') this.$refs.fileUploadLink.click();
		// 	}
		// },
		methods: {
			setSubmissionMethod: function (method) {
				console.log(method);
				this.submissionMethod = method;
			},
			initializeAdapter: function () {
				this.adapter.initialize();
			},
			setSelectedFile: function (file) {
				this.selectedFile = file;
				this.submissionMethod = 'upload';
			},
			chooseFile: function (file) {
				this.chosenFile = file;
				// this.uploadAudioFile(file);
			},
			uploadAudioFile: function (file) {
				// this.audioAdapter.status.pending = true;

				let formData = new FormData();
				formData.append('audioUpload', file);

				return this.$http.post(
					URLS[this.env].audioUpload,
					formData,
					{progress: pe => { console.log(pe); }}
				).then(response => {
					this.filename = response.data.filename;
				});
				// ).then(
				// 	// this.handleUploadSuccess,
				// 	// this.handleUploadFailure
				// ).catch(() => {
				// 	console.log('catch');
				// })
				// .finally(() => {
				// 	// this.audioAdapter.status.pending = false;
				// });
			},
			handleUploadSuccess: function (response) {
				// console.log(response);
				// console.log('success');
				// return response;
				// this.audioAdapter.status.complete = true;
				// this.filename = response.data.filename;
			},
			handleUploadFailure: function (response) {
				// console.log(response);
				// console.log('fail');
				// return response;
				// this.chosenFile = null;
				// this.audioAdapter.status.complete = false;
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
				this.setPanelValidity(false);
			}
		},
		// directives: {
		// 	fileUploadLink: FileUploadLink
		// },
		components: {
			'soapbox-status-indicator': SoapboxStatusIndicator,
			'submission-method-dialog': SubmissionMethodDialog,
			'submission-method-upload': SubmissionMethodUpload,
			'submission-method-record': SubmissionMethodRecord,
			'soapbox-wave-surfer': SoapboxWaveSurfer
		}
	}
</script>