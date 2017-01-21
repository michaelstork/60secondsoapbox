import RecordRTC from 'recordrtc';

export default class RecordRTCAdapter {

	constructor() {
		this.recorder = null;
		this.recordingStarted = false;
		this.recorderReset = true;
		this.stream = null;
	}

	static isSupported() {
		return (navigator.mediaDevices !== undefined
			&& navigator.mediaDevices.getUserMedia !== undefined);
	}

	initialize() {
		navigator.mediaDevices.getUserMedia({audio:true})
			.then(stream => {
				this.stream = stream;
				this.initializeRecorder();
			}, error => {
				console.log('error');
				console.log(error);
			})
			.catch(error => {
				console.log(error);
			}); 
	}

	initializeRecorder() {
		this.recorder = new RecordRTC(this.stream, {type: 'audio'});
		this.recorderReset = true;
	}

	start() {
		this.recorder.startRecording();
		this.recordingStarted = true;
		this.recorderReset = false;
	}

	pause() {
		this.recorder.pauseRecording();
	}

	resume() {
		if (!this.recordingStarted || this.recorderReset) {
			this.start();
			return; 
		}

		this.recorder.resumeRecording();
	}

	process(cb) {
		this.recorder.resumeRecording();
		this.recorder.stopRecording(() => {
			cb(this.recorder.getBlob());
			this.recorder.clearRecordedData();
			this.initializeRecorder();
		});
	}

	restart() {
		this.recordingStarted = false;
		this.pause();
		this.recorder.clearRecordedData();
		this.initializeRecorder();
	}
}