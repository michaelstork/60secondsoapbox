<template>
	<div class="wavesurfer-container">
		<div ref="container" class="wavesurfer"></div>
		<!-- <div class="wavesurfer-zoom-controls">
			<i v-on:click="setZoom(zoom += 0.15)" class="mdi mdi-magnify-plus"></i>
			<i v-on:click="setZoom(zoom -= 0.15)" class="mdi mdi-magnify-minus"></i>
		</div> -->
		<div class="wavesurfer-controls">
			<i v-on:click="skipBackward" class="mdi mdi-rewind skip-back" title="Skip Forward"></i>
			<i v-on:click="toggle" class="mdi mdi-play play-pause"
				:class="{
					'mdi-pause': isPlaying
				}"
				title="Play/Pause">
			</i>
			<i v-on:click="skipForward" class="mdi mdi-fast-forward skip-forward" title="Skip Backward"></i>
		</div>
	</div>
</template>

<script>
	import WaveSurfer from '../../vendor/wavesurfer';
	import SoapboxTimer from './SoapboxTimer.vue';

	export default {
		props: ['url'],
		data: function () {
			return {
				wavesurfer: null,
				zoom: 1
			}
		},
		computed: {
			isPlaying: function () {
				return this.wavesurfer && this.wavesurfer.isPlaying();
			}
		},
		mounted: function () {
			console.log(this.url);
			this.wavesurfer = WaveSurfer.create({
				container: this.$refs.container,
				progressColor: '#00BCD4',
				cursorColor: '#DEDEDE',
				height: 80,
				normalize: true,
				autoCenter: true,
				scrollParent: true,
				hideScrollbar: true
			});
			// this.wavesurfer.on('play', () => {
			// 	this.$emit('wavesurferStatusChange', this.isPlaying);
			// });
			// this.wavesurfer.on('pause', () => {
			// 	this.$emit('wavesurferStatusChange', this.isPlaying);
			// });
			// this.wavesurfer.on('ready', () => {
			// 	this.playbackReady = true;
			// });
		},
		watch: {
			url: function (url) {
				if (!url) return;
				this.wavesurfer.load(url);
			}
		},
		methods: {
			setZoom: function (amount) {
				this.wavesurfer.zoom(50 * amount);
			},
			play: function () {
				this.wavesurfer.play();
			},
			pause: function () {
				this.wavesurfer.pause();
			},
			skipBackward: function () {
				this.wavesurfer.skipBackward();
			},
			skipForward: function () {
				this.wavesurfer.skipForward();
			},
			toggle: function () {
				this.wavesurfer.playPause();
			}
		},
		components: {
			'soapbox-timer': SoapboxTimer
		}
	}
</script>