
<template>
	<div class="submission-method-upload">
		<status-indicator :status="status"></status-indicator>		
		<div :class="'status-'+status">
			<p class="status-message-pending form-header">Uploading {{ file.name }}...</p>
			<p class="status-message-complete form-header">
				Upload Complete!
				<br>
				Click continue below, or <a href="#" v-file-upload-link:audioUpload="retryUpload">upload a different file.</a>
			</p>
			<p class="status-message-error form-header">
				Oops! Something went wrong.
				<br>
				<a href="#" v-file-upload-link:audioUpload="retryUpload">Click here to try again.</a>
			</p>
		</div>
		<slot></slot>
	</div>
</template>

<script>
	import FileUploadLink from '../../directives/fileUploadLink';
	import StatusIndicator from '../audio/StatusIndicator.vue';

	export default {
		props: ['uploadAudioFile', 'selectedFile'],
		data: function () {
			return {
				status: 'pending',
				file: this.selectedFile
			};
		},
		mounted: function () {
			setTimeout(() => {
				this.doUpload();
			}, 1000);
		},
		methods: {
			doUpload: function () {
				this.status = 'pending';
				return this.uploadAudioFile(this.file)
					.then(
						() => { this.status = 'complete'; },
						() => { this.status = 'error'; }
					);
			},
			retryUpload: function (file) {
				// this.$emit('setAudioPreviewStatus', false);
				this.file = file;
				this.doUpload();
			}
		},
		directives: {
			fileUploadLink: FileUploadLink
		},
		components: {
			'status-indicator': StatusIndicator
		}
	}
</script>