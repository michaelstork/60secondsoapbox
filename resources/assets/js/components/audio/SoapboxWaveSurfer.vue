<template>
	<div ref="container" class="wavesurfer-container"></div>
</template>

<script>
	import WaveSurfer from '../../vendor/wavesurfer';

	export default {
		props: ['url'],
		data: function () {
			return {waveSurfer: null}
		},
		mounted: function () {
			this.wavesurfer = WaveSurfer.create({
				container: this.$refs.container,
				progressColor: '#00BCD4'
			});
		},
		watch: {
			url: function (url) {
				if (!url) return;
				this.wavesurfer.load(url);
			}
		},
		methods: {
			play: function () {
				this.wavesurfer.play();
			},
			pause: function () {
				this.wavesurfer.pause();
			}
		}
	}
</script>

<style>
	.wavesurfer-container {
		width:100%;
	}
</style>