
<template>
	<div class="submission-type-upload">
		<div class="panel-content">
			<status-indicator :status="status"></status-indicator>
			<transition name="fade">
				<p v-if="message" v-html="message" class="message"></p>
			</transition>
			<transition name="scale">
				<button v-if="status === 'error'" v-file-upload-link:audio="retry" class="round">
					<i class="mdi mdi-redo mdi-flip-horizontal"></i>
					<span>Try Again</span>
				</button>
			</transition>
		</div>
		<slot name="nav"></slot>
	</div>
</template>

<script>
	import FileUploadLink from '../../directives/fileUploadLink';
	import StatusIndicator from '../audio/StatusIndicator.vue';
	import {AUDIO} from '../../config';

	export default {
		props: ['uploadAudioFile', 'audio'],
		data: function () {
			return {
				status: 'pending',
				message: null,
				file: this.audio.file
			};
		},
		mounted: function () {
			setTimeout(this.doUpload, 250);
		},
		methods: {
			doUpload: function () {
				this.status = 'pending';
				this.message = 'Uploading...';
				return this.uploadAudioFile(this.file)
					.then(data => {
						if (this.audio.valid) {
							this.status = 'complete';
							this.message = 'Upload Complete!';
						} else {
							this.status = 'error';
							this.message = `
								This file appears to be <span class="error">${data.duration / 1000}</span> seconds long.
								<br>
								We need something between <span>${AUDIO.minDuration / 1000}</span> and <span>${AUDIO.maxDuration / 1000}</span> seconds.
							`;
						}
					}, error => {
						this.status = 'error';
						this.message = error;
					});
			},
			retry: function (file) {
				this.file = file;
				this.message = null;
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