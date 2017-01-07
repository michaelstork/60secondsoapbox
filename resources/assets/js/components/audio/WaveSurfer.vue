<template>
	<div class="wavesurfer-container" :class="{pending:pending, active: active}">
		<div ref="container" class="wavesurfer"></div>
		<svg class="async-pending-indicator" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
			<circle fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
		</svg>
		<nav>
			<button v-on:click="reset" class="round reset-button" tabIndex="-1">
				<i class="mdi mdi-refresh"></i>
				<span>Start Over</span>
			</button>
			<button v-on:click="wavesurfer.playPause()" title="Play/Pause" class="round play-pause" :disabled="pending" tabIndex="-1">
				<i class="mdi mdi-play"
					:class="{
						'mdi-pause': isPlaying
					}">
				</i>
				<span>{{ isPlaying ? 'Pause' : ' Play ' }}</span>
			</button>
			<button v-on:click="requestPanelNavigation" :disabled="!audioSubmissionValid" class="save-button round" tabIndex="-1">
				<i class="mdi mdi-content-save"></i>
				<span>Save</span>
			</button>
		</nav>
	</div>
</template>

<script>
	import WaveSurfer from '../../vendor/wavesurfer';

	export default {
		props: ['url', 'active', 'audioSubmissionValid', 'requestPanelNavigation'],
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
			this.wavesurfer.on('ready', () => {
				this.pending = false;
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
			reset: function () {
				this.$emit('resetPanel');
			}
		}
	}
</script>