
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
				:uploadAudioFile="uploadAudioFile"
				:selectedFile="selectedFile"
				:audioSubmissionValid="audioSubmissionValid"
				:requestPanelNavigation="requestPanelNavigation"
				v-on:setAudioPreviewStatus="setAudioPreviewStatus"
				v-on:resetPanel="resetPanel">
			</component>
		</transition>
		<soapbox-wave-surfer
			:url="audioUrl"
			:active="audioPreviewActive"
			:audioSubmissionValid="audioSubmissionValid"
			:requestPanelNavigation="requestPanelNavigation"
			v-on:resetPanel="resetPanel"
			v-on:setAudioPreviewStatus="setAudioPreviewStatus">	
		</soapbox-wave-surfer>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
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
				adapter: new RecordRTCAdapter,
				submissionMethod: null,
				selectedFile: null,
				audioUrl: null,
				audioPreviewActive: false,
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
		methods: {
			resetPanel: function () {
				if (!window.confirm('Are you sure you want to start over?')) return;
				this.submissionMethod = null;
				this.audioSubmissionValid = false;
				this.audioUrl = null;
				this.audioPreviewActive = null;
				this.selectedFile = null;
			},
			setSubmissionMethod: function (method) {
				this.submissionMethod = method;
			},
			setSelectedFile: function (file) {
				this.selectedFile = file;
				this.submissionMethod = 'upload';
			},
			setAudioPreviewStatus: function (status) {
				this.audioPreviewActive = status;
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
				this.setAudioPreviewStatus(true);
				return response;
			},
			handleUploadFailure: function (response) {
				console.log(response);
			},
			composePanelData: function () {
				return {audioUrl: this.audioUrl};
			},
		},
		components: {
			'submission-method-dialog': SubmissionMethodDialog,
			'submission-method-upload': SubmissionMethodUpload,
			'submission-method-record': SubmissionMethodRecord,
			'soapbox-wave-surfer': SoapboxWaveSurfer
		}
	}
</script>