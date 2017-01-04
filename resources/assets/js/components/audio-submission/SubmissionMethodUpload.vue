
<template>
	<div class="submission-method-upload">
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
	</div>
</template>

<script>
	import FileUploadLink from '../../directives/fileUploadLink';

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
				return this.uploadAudioFile(this.file)
					.then(
						() => { this.status = 'complete'; },
						() => { this.status = 'error'; }
					);
			},
			retryUpload: function (file) {
				this.status = 'pending';
				this.$emit('setAudioPreviewStatus', false);
				this.file = file;
				this.doUpload();
			}
		},
		directives: {
			fileUploadLink: FileUploadLink
		}
	}
</script>