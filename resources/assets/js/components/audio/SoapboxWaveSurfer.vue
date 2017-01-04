<template>
	<div class="wavesurfer-container" :class="{pending:pending, active: active}">
		<div ref="container" class="wavesurfer"></div>
		<svg class="async-pending-indicator" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
			<circle fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
		</svg>
		<!-- <div class="wavesurfer-zoom-controls">
			<i v-on:click="setZoom(zoom += 0.15)" class="mdi mdi-magnify-plus"></i>
			<i v-on:click="setZoom(zoom -= 0.15)" class="mdi mdi-magnify-minus"></i>
		</div> -->
		<div v-if="wavesurfer" class="wavesurfer-controls">
			<button v-on:click="wavesurfer.skipBackward()" title="Skip Forward" class="round skip-back" :disabled="pending">
				<i class="mdi mdi-rewind"></i>
			</button>
			<button v-on:click="wavesurfer.playPause()" title="Play/Pause" class="round play-pause" :disabled="pending">
				<i class="mdi mdi-play"
					:class="{
						'mdi-pause': isPlaying
					}">
				</i>
			</button>
			<button v-on:click="wavesurfer.skipForward()" title="Skip Backward" class="round skip-forward" :disabled="pending">
				<i class="mdi mdi-fast-forward"></i>
			</button>
		</div>
	</div>
</template>

<script>
	import WaveSurfer from '../../vendor/wavesurfer';
	// import SoapboxTimer from './SoapboxTimer.vue';

	export default {
		props: ['url', 'active'],
		data: function () {
			return {
				wavesurfer: null,
				zoom: 1,
				pending: true
			}
		},
		computed: {
			isPlaying: function () {
				return this.wavesurfer && this.wavesurfer.isPlaying();
			}
		},
		mounted: function () {
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
			this.wavesurfer.on('ready', () => {
				// this.playbackReady = true;
				// this.active = true;
				// setTimeout(() => {
					this.pending = false;
				// }, 1000);
			});
		},
		watch: {
			url: function (url) {
				if (!url) return;
				this.pending = true;
				this.wavesurfer.load(url);
			},
			active: function (active) {
				if (!active) {
					this.wavesurfer.empty();
				}
			}
		},
		methods: {
			setZoom: function (amount) {
				this.wavesurfer.zoom(50 * amount);
			},
			// play: function () {
			// 	this.wavesurfer.play();
			// },
			// pause: function () {
			// 	this.wavesurfer.pause();
			// },
			// skipBackward: function () {
			// 	this.wavesurfer.skipBackward();
			// },
			// skipForward: function () {
			// 	this.wavesurfer.skipForward();
			// },
			// toggle: function () {
			// 	this.wavesurfer.playPause();
			// }
		},
		// components: {
		// 	'soapbox-timer': SoapboxTimer
		// }
	}
</script>