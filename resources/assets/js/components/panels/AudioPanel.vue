
<template>
	<section class="audio-panel content-panel">
		<transition name="fade" mode="out-in">
			<submission-method-dialog
				v-if="!submissionMethod"
				v-on:setSubmissionMethod="setSubmissionMethod"
				v-on:setSelectedFile="setSelectedFile">
			</submission-method-dialog>
			<component v-else :is="subComponentId" :uploadAudioFile="uploadAudioFile" :selectedFile="selectedFile" v-on:setAudioPreviewStatus="setAudioPreviewStatus"></component>
		</transition>
		<soapbox-wave-surfer :url="audioUrl" :active="audioPreviewActive"></soapbox-wave-surfer>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	// import FileUploadLink from '../../directives/fileUploadLink';
	import SoapboxStatusIndicator from '../audio/SoapboxStatusIndicator.vue';
	import SoapboxWaveSurfer from '../audio/SoapboxWaveSurfer.vue';
	import RecordRTCAdapter from '../../adapters/RecordRTC';
	import {URLS} from '../../config/index';

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
				audioUrl: null,
				audioPreviewActive: false
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
			// canRecordAudio: function () {
			// 	return RecordRTCAdapter.isSupported();
			// },
			subComponentId: function () {
				return 'submission-method-' +this.submissionMethod;
			}
		},
		methods: {
			setSubmissionMethod: function (method) {
				console.log(method);
				this.submissionMethod = method;
			},
			// initializeAdapter: function () {
			// 	this.adapter.initialize();
			// },
			setSelectedFile: function (file) {
				this.selectedFile = file;
				this.submissionMethod = 'upload';
			},
			setAudioPreviewStatus: function (status) {
				this.audioPreviewActive = status;
			},
			// clearAudioPreview: function () {
			// 	this.audioUrl = null;
			// },
			// activateAudioPreview: function () {
			// 	this.audioPreviewActive = true;
			// },
			// deactivateAudioPreview: function () {
			// 	this.audioPreviewActive = false;
			// }
			// chooseFile: function (file) {
			// 	this.chosenFile = file;
			// 	// this.uploadAudioFile(file);
			// },
			uploadAudioFile: function (file) {
				// this.audioAdapter.status.pending = true;

				let formData = new FormData();
				formData.append('audioUpload', file);

				return this.$http.post(
					URLS[this.env].audioUpload,
					formData,
					{progress: pe => { console.log(pe); }}
				).then(response => {
					this.audioUrl = response.data.audioUrl;
					this.setAudioPreviewStatus(true);
					return response;
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
				return {audioUrl: this.audioUrl};
			},
			// toggle: function () {
			// 	let method = this.audioAdapter.status.recording
			// 		? 'pause'
			// 		: this.audioAdapter.status.started
			// 			? 'resume'
			// 			: 'start';

			// 	this.audioAdapter[method].call(this.audioAdapter);
			// },
			// stop: function () {
			// 	this.audioAdapter.stop(this.uploadAudioFile.bind(this));
			// },
			// restart: function () {
			// 	if (!window.confirm('Are you sure you want to start over?')) return;
			// 	this.audioAdapter.restart();
			// 	this.setPanelValidity(false);
			// }
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