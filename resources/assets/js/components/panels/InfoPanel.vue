
<template>
	<section class="info-panel content-panel">
		<div class="panel-content">
			<div class="panel-icon" :style="iconStyle">
				<i v-if="!photoUrl" class="mdi mdi-account"></i>
			</div>
			<h3 v-html="message ? message : 'First, we\'ll need a photo and some info:'"></h3>
			<soapbox-form :form="form" :disabled="!isActivePanel" v-on:formValidityChange="onFormValidityChange">
				<div slot="form-top" class="info-panel-photo-container" :class="{complete: photoUrl}">
					<button v-file-upload-link:photo="selectFile" :disabled="photoUrl" class="rect" tabIndex="-1">
						<span>Upload Photo</span>
					</button>
				</div>
			</soapbox-form>
		</div>
		<nav>
			<button class="round"
				:disabled="!isValidPanel"
				v-on:click="requestPanelNavigation">
				<i class="mdi mdi-keyboard-backspace mdi-flip-horizontal"></i>
				<span>Continue</span>
			</button>
		</nav>
	</section>
</template>

<script>
	import SoapboxBasePanel from './BasePanel.vue';
	import FileUploadLink from '../../directives/fileUploadLink';
	import {infoForm} from '../../forms/index';
	import {URLS} from '../../config/index';

	export default {
		extends: SoapboxBasePanel,
		data: function () {
			return {
				form: infoForm,
				photo: null,
				photoUrl: null,
				message: null
			};
		},
		computed: {
			iconStyle: function () {
				return this.photoUrl
					? 'background-image:url('+ this.photoUrl +');background-size:cover;'
					: null;
			}
		},
		watch: {
			photo: function (file) {
				this.uploadPhoto(file);
			}
		},
		methods: {
			selectFile: function (file) {
				this.photo = file;
			},
			uploadPhoto: function (file) {
				let formData = new FormData();
				formData.append('photo', file);
				
				return this.$http.post(
					URLS.photoUpload,
					formData,
					{progress: pe => { console.log(pe); }}
				).then(
					this.handleUploadSuccess,
					this.handleUploadFailure
				);
			},
			handleUploadSuccess: function (response) {
				this.photoUrl = response.data.url;
				return response.data;
			},
			handleUploadFailure: function (response) {
				this.photoUrl = null;
				this.message = response.data.message;
			},
		},
		directives: {
			fileUploadLink: FileUploadLink
		}
	}
</script>