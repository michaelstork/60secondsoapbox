import RecordRTC from 'recordrtc';

const STATUS_PAUSED = 'paused';
const STATUS_RECORDING = 'recording';
const STATUS_PROCESSING = 'processing';

export default class RecordRTCAdapter {

	constructor() {
		console.log('RecordRTCAdapter');
		this.initialized   = false;
		this.mediaRecorder = null;
		this.status        = null;

		// this.supported = this.isSupported();
		// this.supported = this.isSupported();
	}

	static isSupported() {
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
			});
	}

	start() {
		this.status = STATUS_RECORDING;
		this.recorder.startRecording();
		console.log('record...');
	}

	pause() {
		this.status = STATUS_PAUSED;
		this.recorder.pauseRecording();
		console.log('pause');
	}

	resume() {
		this.status = STATUS_RECORDING;
		this.recorder.resumeRecording();
		console.log('resume');
	}

	process(cb) {
		this.status = STATUS_PROCESSING;
		console.log('stop');
		this.recorder.stopRecording(() => {
			let blob = this.recorder.getBlob();
			cb(blob);
		});
	}

	restart() {
		this.status = null;
		this.process(() => {
			this.recorder.clearRecordedData();
		});
		console.log('restarting...');
	}
}