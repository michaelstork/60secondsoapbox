import RecordRTC from 'recordrtc';

export default class RecordRTCAdapter {

	constructor() {
		console.log('RecordRTCAdapter');
		this.initialized   = false;
		this.mediaRecorder = null;
		this.recordingStarted = false;
		// this.status        = null;

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
		// this.status = 'recording';
		this.recorder.startRecording();
		this.recordingStarted = true;
	}

	pause() {
		// this.status = 'paused';
		this.recorder.pauseRecording();
	}

	resume() {
		if (!this.recordingStarted) {
			this.start();
			return;
		}
		// if (this.status === null) {
			// this.start();
			// return;
		// }

		// this.status = 'recording';
		this.recorder.resumeRecording();
	}

	process(cb) {
		this.recorder.stopRecording(() => {
			cb(this.recorder.getBlob());
		});
	}

	restart() {
		// this.status = null;
		this.recordingStarted = false;
		this.process(() => {
			this.recorder.clearRecordedData();
			this.initialized = false;
		});
	}
}