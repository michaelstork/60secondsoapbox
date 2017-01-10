
<template>
	<div class="submission-type-upload">
		<div class="panel-content">
			<status-indicator :status="status"></status-indicator>		
		</div>
		<slot name="nav"></slot>
	</div>
</template>

<script>
	import FileUploadLink from '../../directives/fileUploadLink';
	import StatusIndicator from '../audio/StatusIndicator.vue';

	export default {
		props: ['uploadAudioFile', 'audio'],
		data: function () {
			return {
				status: 'pending',
				file: this.audio.file
			};
		},
		mounted: function () {
			setTimeout(this.doUpload, 250);
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