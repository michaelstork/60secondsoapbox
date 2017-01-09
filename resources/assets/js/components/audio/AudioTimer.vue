<template>
	<span ref="timer" class="audio-timer">00:00</span>
</template>

<script>
	import moment from 'moment';

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
				let minutes = Math.floor(duration.as('minutes'));
				let seconds = Math.floor(duration.as('seconds')) - (minutes * 60);

				this.$refs.timer.innerText = padString(minutes) + ':' + padString(seconds);
			}
		}
	}
</script>