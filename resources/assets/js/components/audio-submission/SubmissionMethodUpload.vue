
<template>
	<div class="submission-method-upload">
		<div class="panel-content">
			<status-indicator :status="status"></status-indicator>		
			<div :class="'status-'+status">
				<h3 class="status-message-pending">Uploading {{ file.name }}...</h3>
				<h3 class="status-message-complete">
					Upload Complete!
					<br>
					Click continue below, or <a href="#" v-file-upload-link:audioUpload="retryUpload">upload a different file.</a>
				</h3>
				<h3 class="status-message-error">
					Oops! Something went wrong.
					<br>
					<a href="#" v-file-upload-link:audioUpload="retryUpload">Click here to try again.</a>
				</h3>
			</div>
		</div>
		<slot name="nav"></slot>
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