
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
		<transition name="fade" mode="out-in">
			<p v-if="status === 'pending'" class="form-header">Uploading {{ selectedFile.name }}...</p>
			<p v-else-if="status === 'complete'" class="form-header">Upload Complete!</p>
			<p v-else-if="status === 'error'" class="form-header">Oops! Something went wrong</p>
		</transition>
	</div>
</template>

<script>
	import FileUploadLink from '../../directives/fileUploadLink';

	export default {
		props: ['uploadAudioFile', 'selectedFile'],
		data: function () {
			return {
				status: 'pending'
			};
		},
		mounted: function () {
			this.uploadAudioFile(this.selectedFile)
				.then(
					() => {
						setTimeout(() => {
							this.status = 'complete';
						}, 3000);
					},
					() => {
						setTimeout(() => {
							this.status = 'error';
						}, 3000);
					}
				);
		},
		directives: {
			fileUploadLink: FileUploadLink
		}
	}
</script>