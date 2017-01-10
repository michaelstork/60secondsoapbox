
<template>
	<section class="audio-panel content-panel">
		<transition name="fade" mode="out-in">			
			<submission-type-dialog
				v-if="!audio.submissionType"
				v-on:setSubmissionType="setSubmissionType">
			</submission-type-dialog>
			<div v-else>
				<keep-alive>
					<!-- SubmissionTypeRecord/SubmissionTypeUpload -->
					<component :is="subComponentId"
						:audio="audio"
						:audioEventHub="audioEventHub"
						:uploadAudioFile="uploadAudioFile">
						
						<soapbox-wave-surfer
							slot="nav"
							:audioEventHub="audioEventHub"
							:audio="audio">

							<!-- start over / continue buttons -->
							<button class="round reset-button"
								slot="restart"
								v-on:click="resetAudioPanel"
								tabIndex="-1">
								<i class="mdi mdi-refresh"></i>
								<span>Start Over</span>
							</button>
							<button class="save-button round"
								slot="continue"
								v-on:click="requestPanelNavigation"
								:disabled="!isValidPanel"
								tabIndex="-1">
								<i class="mdi mdi-keyboard-backspace mdi-flip-horizontal"></i>
								<span>Continue</span>
							</button>

						</soapbox-wave-surfer>			
					</component>
				</keep-alive>
			</div>
		</transition>
	</section>
</template>

<script>
	import Vue from 'vue';
	import SoapboxBasePanel from './BasePanel.vue';
	import WaveSurfer from '../audio/WaveSurfer.vue';
	import RecordRTCAdapter from '../../adapters/RecordRTC';
	import {URLS, AUDIO} from '../../config/index';

	import SubmissionTypeDialog from '../audio-submission/SubmissionTypeDialog.vue';
	import SubmissionTypeUpload from '../audio-submission/SubmissionTypeUpload.vue';
	import SubmissionTypeRecord from '../audio-submission/SubmissionTypeRecord.vue';

	export default {
		extends: SoapboxBasePanel,
		data: function () {

			const audioEventHub = new Vue();
			const audio = {
				adapter: new RecordRTCAdapter(),
				submissionType: null,
				file: null,
				url: null,
				valid: false
			};

			return {
				audioEventHub: audioEventHub,
				audio: audio
			};
		},
		computed: {
			isValidPanel: function () {
				return this.audio.valid;
			},
			subComponentId: function () {
				return this.audio.submissionType
					? 'submission-type-' + this.audio.submissionType
					: null;
			}
		},
		mounted: function () {
			this.audioEventHub.$on('recordingStatusChange', this.onRecordingStatusChange);
		},
		methods: {
			onRecordingStatusChange: function (status) {
				if (status === 'recording') {
					this.audio.url = null;
					this.audio.valid = false;
				}
			},
			resetAudioPanel: function () {
				if (!window.confirm('Are you sure you want to start over?')) return;
				this.audioEventHub.$emit('audioReset');
				this.setSubmissionType(null);
				this.audio.file = null;
				this.audio.url = null;
				this.audio.valid = false;
				this.deleteAudioFile();
			},
			setSubmissionType: function (method, file = null) {
				this.audio.submissionType = method;
				this.audio.file = file;
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
			deleteAudioFile: function () {
				return this.$http.post(
					URLS[this.env].audioDelete,
					{}
				).then(
					response => {
						console.log(response);
					},
					response => {
						console.log(response);
					}
				);
			},
			handleUploadSuccess: function (response) {
				this.audio.url = response.data.url;
				this.audio.valid = (response.data.duration >= AUDIO.minDuration);
				return response;
			},
			handleUploadFailure: function (response) {
				console.log(response);
				this.audio.url = null;
			},
			composePanelData: function () {
				return {
					filename: (this.audio.url
						? this.audio.url.split('.').pop()
						: null)
				};
			},
		},
		components: {
			'submission-type-dialog': SubmissionTypeDialog,
			'submission-type-upload': SubmissionTypeUpload,
			'submission-type-record': SubmissionTypeRecord,
			'soapbox-wave-surfer': WaveSurfer
		}
	}
</script>