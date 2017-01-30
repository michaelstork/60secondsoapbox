<template>
	<div class="wavesurfer-container" :class="{pending:pending, failed: failed, active: audio.url}">
		<div ref="container" class="wavesurfer"></div>
		<transition name="fade" mode="out-in">
			<p v-if="pending" class="loading-text">Loading Audio...</p>
			<p v-else-if="failed" class="error-text">
				<span>Playback Failed!</span>
				<a :href="audio.url" target="_blank">Click here to access the file directly</a>
			</p>
		</transition>
		<nav>
			<slot name="restart"></slot>
			<transition name="scale" mode="out-in">
				<button v-if="!audio.url" v-on:click="requestAudioPreview" :disabled="previewDisabled" class="preview-audio-button round" tabIndex="-1" key="requestPreview">
					<i class="mdi mdi-volume-high"></i>
					<span>Preview</span>
				</button>
				<button v-else v-on:click="wavesurfer.playPause()" title="Play/Pause" class="round play-pause-button" :disabled="failed || pending || !audio.url" tabIndex="-1" key="playPause">
					<i class="mdi mdi-play"
						:class="{
							'mdi-pause': isPlaying
						}">
					</i>
					<span>{{ isPlaying ? 'Pause' : 'Play' }}</span>
				</button>
			</transition>
			<slot name="continue"></slot>
		</nav>
	</div>
</template>

<script>
	import WaveSurfer from '../../vendor/wavesurfer';

	export default {
		props: ['audioEventHub', 'audio'],
		data: function () {
			return {
				wavesurfer: null,
				zoom: 1,
				pending: false,
				failed: false
			}
		},
		computed: {
			isPlaying: function () {
				return this.wavesurfer && this.wavesurfer.isPlaying();
			},
			previewDisabled: function () {
				return this.failed || this.pending || (this.audio.adapter.stream && !this.audio.adapter.recordingStarted);
			}
		},
		mounted: function () {
			this.wavesurfer = WaveSurfer.create({
				container: this.$refs.container,
				progressColor: '#00BCD4',
				cursorColor: '#DEDEDE',
				height: 60,
				normalize: true,
				autoCenter: true,
				scrollParent: true,
				hideScrollbar: true,
				renderer: 'MultiCanvas',
				maxCanvasWidth: 600
			});
			
			this.wavesurfer.on('ready', () => {
				this.pending = false;
				this.failed = false;
			});
			this.wavesurfer.on('error', error => {
				console.log(error);
				this.failed = true;
				this.pending = false;
			});
		},
		beforeDestroy: function () {
			this.wavesurfer.unAll();
			this.wavesurfer.destroy();
		},
		watch: {
			'audio.url': function (url) {
				this.failed = false;
				
				if (!url) {
					if (this.isPlaying) this.wavesurfer.pause();
					this.wavesurfer.empty();
					return;
				}

				this.pending = true;
				this.wavesurfer.load(url);
			},
		},
		methods: {
			setZoom: function (amount) {
				this.wavesurfer.zoom(50 * amount);
			},
			requestAudioPreview: function () {
				this.audioEventHub.$emit('requestAudioPreview');
			}
		}
	}
</script>