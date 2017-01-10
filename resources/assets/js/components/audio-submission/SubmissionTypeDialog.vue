
<template>
	<div class="submission-type-dialog">
		<div class="panel-icon">
			<i class="mdi mdi-voice"></i>
		</div>
		<div class="panel-content">
			<h3>Choose how you'd like to submit your recording:</h3>
			<p>If your device has a microphone and supports audio capture, you can record your submission here. Alternatively, you may upload your own audio file in wav or mp3 format.</p>
		</div>
		<nav>
			<button v-on:click="setSubmissionType('record')" class="submission-type-record-button round" :disabled="!canRecordAudio" tabIndex="-1">
				<i class="mdi mdi-microphone" :class="{'mdi-microphone-off':!canRecordAudio}"></i>
				<span>Record my voice</span>
			</button>
			<i class="divider"></i>
			<button v-file-upload-link:audioUpload="selectFile" class="submission-type-upload-button round" tabIndex="-1">
				<i class="mdi mdi-cloud-upload"></i>
				<span>Upload audio file</span>
			</button>
		</nav>
	</div>
</template>

<script>
	import FileUploadLink from '../../directives/fileUploadLink';
	import RecordRTCAdapter from '../../adapters/RecordRTC';

	export default {
		data: function () {
			return {file: null};
		},
		computed: {
			canRecordAudio: function () {
				return RecordRTCAdapter.isSupported();
			}
		},
		methods: {
			setSubmissionType: function (type) {
				this.$emit('setSubmissionType', type, this.file);
			},
			selectFile: function (file) {
				this.file = file;
				this.setSubmissionType('upload');
			}
		},
		directives: {
			fileUploadLink: FileUploadLink
		}
	}
</script>