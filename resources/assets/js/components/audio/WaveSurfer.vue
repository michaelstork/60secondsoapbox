<template>
	<div class="wavesurfer-container" :class="{pending:pending, active: url}">
		<div ref="container" class="wavesurfer"></div>
		<transition name="fade">
			<svg v-if="pending" class="async-pending-indicator" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
			</svg>
		</transition>
		<nav>
			<slot name="restart"></slot>
			<transition name="scale" mode="out-in">
				<button v-if="!url" v-on:click="requestAudioPreview" :disabled="previewDisabled" class="preview-audio-button round" tabIndex="-1" key="requestPreview">
					<i class="mdi mdi-volume-high"></i>
					<span>Preview</span>
				</button>
				<button v-else v-on:click="wavesurfer.playPause()" title="Play/Pause" class="round play-pause-button" :disabled="pending || !url" tabIndex="-1" key="playPause">
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
		props: ['url', 'audioEventHub', 'adapter'],
		data: function () {
			return {
				wavesurfer: null,
				zoom: 1,
				pending: false
			}
		},
		computed: {
			isPlaying: function () {
				return this.wavesurfer && this.wavesurfer.isPlaying();
			},
			previewDisabled: function () {
				return this.pending || (this.adapter.initialized && !this.adapter.recordingStarted);
			}
		},
		mounted: function () {
			this.wavesurfer = WaveSurfer.create({
				container: this.$refs.container,
				progressColor: '#00BCD4',
				cursorColor: '#DEDEDE',
				height: 120,
				normalize: true,
				autoCenter: true,
				scrollParent: true,
				hideScrollbar: true,
				renderer: 'MultiCanvas',
				maxCanvasWidth: 600
			});
			this.wavesurfer.on('ready', () => {
				this.pending = false;
			});
		},
		watch: {
			url: function (url) {
				if (!url) {
					if (this.isPlaying) this.wavesurfer.pause();
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