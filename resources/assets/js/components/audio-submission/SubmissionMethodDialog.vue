
<template>
	<div class="submission-method">
		<p class="form-header">Choose how you'd like to submit your recording:</p>
		<p>If your device has a microphone and supports audio capture, you can record your submission here.<br>Alternatively, you may upload your own audio file in wav or mp3 format.</p>
		<div class="submission-method-dialog">
			<nav>
				<button v-on:click="setSubmissionMethod('record')" class="submission-method-record-button round" :disabled="!canRecordAudio" tabIndex="-1">
					<i class="mdi mdi-microphone" :class="{'mdi-microphone-off':!canRecordAudio}"></i>
					<span>Record my voice</span>
				</button>
				<i class="divider"></i>
				<button v-file-upload-link:audioUpload="setSelectedFile" class="submission-method-upload-button round" tabIndex="-1">
					<i class="mdi mdi-cloud-upload"></i>
					<span>Upload audio file</span>
				</button>
			</nav>
		</div>
	</div>
</template>

<script>
	import RecordRTCAdapter from '../../adapters/RecordRTC';
	import FileUploadLink from '../../directives/fileUploadLink';

	export default {
		props: ['selectedFile'],
		computed: {
			canRecordAudio: function () {
				return RecordRTCAdapter.isSupported();
			}
		},
		methods: {
			setSubmissionMethod: function (method) {
				this.$emit('setSubmissionMethod', method);
			},
			setSelectedFile: function (file) {
				this.$emit('setSelectedFile', file);
			}
		},
		directives: {
			fileUploadLink: FileUploadLink
		}
	}
</script>