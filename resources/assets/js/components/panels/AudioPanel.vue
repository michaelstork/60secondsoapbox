
<template>
	<section class="audio-panel content-panel">
		<transition name="fade" mode="out-in">
			<submission-method-dialog
				v-if="!submissionMethod"
				v-on:setSubmissionMethod="setSubmissionMethod"
				v-on:setSelectedFile="setSelectedFile">
			</submission-method-dialog>
			<component v-else
				:is="subComponentId"
				:audioEventHub="audioEventHub"
				:adapter="adapter"
				:uploadAudioFile="uploadAudioFile"
				:selectedFile="selectedFile">
				<soapbox-wave-surfer
					:audioEventHub="audioEventHub"
					:adapter="adapter"
					:url="audioUrl"
					:audioSubmissionValid="audioSubmissionValid"
					:requestPanelNavigation="requestPanelNavigation">
				</soapbox-wave-surfer>
			</component>
		</transition>
	</section>
</template>

<script>
	import Vue from 'vue';
	import SoapboxBasePanel from './BasePanel.vue';
	import WaveSurfer from '../audio/WaveSurfer.vue';
	import RecordRTCAdapter from '../../adapters/RecordRTC';
	import {URLS} from '../../config/index';

	import SubmissionMethodDialog from '../audio-submission/SubmissionMethodDialog.vue';
	import SubmissionMethodUpload from '../audio-submission/SubmissionMethodUpload.vue';
	import SubmissionMethodRecord from '../audio-submission/SubmissionMethodRecord.vue';

	export default {
		extends: SoapboxBasePanel,
		data: function () {

			const audioEventHub = new Vue();

			return {
				audioEventHub: audioEventHub,
				adapter: new RecordRTCAdapter,
				submissionMethod: null,
				selectedFile: null,
				audioUrl: null,
				audioSubmissionValid: false
			};
		},
		computed: {
			isValidPanel: function () {
				return this.audioSubmissionValid;
			},
			subComponentId: function () {
				return this.submissionMethod
					? 'submission-method-' +this.submissionMethod
					: null;
			}
		},
		mounted: function () {
			this.audioEventHub.$on('requestAudioReset', this.resetAudioPanel);
			this.audioEventHub.$on('recordingStatusChange', this.onRecordingStatusChange);
		},
		methods: {
			onRecordingStatusChange: function (status) {
				if (status === 'recording') this.audioUrl = null;
			},
			resetAudioPanel: function () {
				if (!window.confirm('Are you sure you want to start over?')) return;
				this.submissionMethod = null;
				this.audioSubmissionValid = false;
				this.audioUrl = null;
				this.audioPreviewActive = null;
				this.selectedFile = null;
				if (this.adapter.initialized) this.adapter.restart();
			},
			setSubmissionMethod: function (method) {
				this.submissionMethod = method;
			},
			setSelectedFile: function (file) {
				this.selectedFile = file;
				this.submissionMethod = 'upload';
			},
			uploadAudioFile: function (file) {
				let formData = new FormData();
				formData.append('audioUpload', file);

				return this.$http.post(
					URLS[this.env].audioUpload,
					formData,
					{progress: pe => { console.log(pe); }}
				).then(
					this.handleUploadSuccess,
					this.handleUploadFailure
				);
			},
			handleUploadSuccess: function (response) {
				this.audioUrl = response.data.audioUrl;
				this.audioSubmissionValid = true;
				return response;
			},
			handleUploadFailure: function (response) {
				console.log(response);
				this.audioUrl = null;
			},
			composePanelData: function () {
				return {audioUrl: this.audioUrl};
			},
		},
		components: {
			'submission-method-dialog': SubmissionMethodDialog,
			'submission-method-upload': SubmissionMethodUpload,
			'submission-method-record': SubmissionMethodRecord,
			'soapbox-wave-surfer': WaveSurfer
		}
	}
</script>