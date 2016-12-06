import RecordRTC from 'recordrtc';

export default class RecordRTCAdapter {

	constructor() {
		console.log('RecordRTCAdapter');
		this.initialized   = false;
		this.mediaRecorder = null;
		this.chunks        = [];
		this.status        = {
			supported: this.isSupported(),
			recording: false,
			started: false,
			pending: false,
			complete: false
		};
	}

	isSupported() {
		return (navigator.mediaDevices !== undefined
			&& navigator.mediaDevices.getUserMedia !== undefined);
	}

	initialize() {
		navigator.mediaDevices.getUserMedia({audio:true})
			.then(stream => {
				let options = {
					mimeType: 'audio/x-wav', 
					bitsPerSecond: 128000,
					buffersize: 16384
				};
				this.recorder = RecordRTC(stream, options);
				this.initialized = true;
				console.log('initialized recordRTC');
			})
			.catch(error => {
				console.log(error);
				this.supported = false;
			});
	}

	start() {
		this.status.recording = this.status.started = true;
		this.recorder.startRecording();
		console.log('record...');
	}

	pause() {
		this.status.recording = false;
		this.recorder.pauseRecording();
		console.log('pause');
	}

	resume() {
		this.status.recording = true;
		this.recorder.resumeRecording();
		console.log('resume');
	}

	stop(upload) {
		this.status.recording = false;
		console.log('stop');
		this.recorder.stopRecording(audioUrl => {
			let blob = this.recorder.getBlob();
			upload(blob);
		});
	}

	restart() {
		this.status.recording = this.status.complete = this.status.pending = this.status.started = false;
		this.stop(() => {
			this.recorder.clearRecordedData();
		});
		console.log('restarting...');
	}
}