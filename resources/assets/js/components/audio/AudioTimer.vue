<template>
	<div>
		<svg class="radial-progress" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
			<circle ref="progress" stroke-dashoffset="314" cx="50" cy="50" r="50" />
		</svg>
		<span ref="timer" class="audio-timer">00:00</span>
	</div>
</template>

<script>
	import moment from 'moment';
	import {AUDIO} from '../../config';

	const radialProgressDashArray = 314;

	function padString (str) {
		return ('00' + str).substr(-2, 2);
	}

	export default {
		props: ['status', 'reset', 'update'],
		data: function () {
			return {
				interval: null,
				timeStarted: null,
				total: 0
			};
		},
		beforeDestroy: function () {
			clearInterval(this.interval);
		},
		watch: {
			status: function (status, previousStatus) {
				if (status === 'recording') {
					this.startTimer();
					return;
				}

				if (previousStatus === 'recording') this.stopTimer();
			},
			reset: function (reset) {
				if (reset) this.resetTimer();
			}
		},
		methods: {
			stopTimer: function () {
				this.total += this.getMillisecondsSince(this.timeStarted);
				this.update(this.total);
				clearInterval(this.interval);
			},
			startTimer: function () {
				this.timeStarted = moment();
				this.interval = setInterval(() => {
					this.renderTimer(
						this.getMillisecondsSince(this.timeStarted).add(this.total)
					);
				}, 200);
			},
			resetTimer: function () {
				clearInterval(this.interval);
				this.total = 0;
				this.renderTimer(moment.duration(0));
			},
			getMillisecondsSince: function (since) {
				return moment.duration(moment().diff(since));
			},
			renderTimer: function (duration) {
				const minutes = Math.floor(duration.as('minutes'));
				const seconds = Math.floor(duration.as('seconds')) - (minutes * 60);
				const progress = 1 - (duration.as('milliseconds') / AUDIO.minDuration);

				this.$refs.timer.innerText = padString(minutes) + ':' + padString(seconds);
				this.$refs.progress.style.strokeDashoffset = radialProgressDashArray * Math.max(0, progress);
			}
		}
	}
</script>