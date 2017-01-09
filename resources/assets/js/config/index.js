
const URLS = {};

URLS.cordova = {

};

URLS.web = {
	authenticate: '/authenticate',
	validateNominee: '/api/validate-nominee',
	audioUpload: '/api/audio-upload',
	audioDelete: '/api/audio-delete',
	submission: '/api/submission'
};

const AUDIO = {};

AUDIO.minDuration = 5000;
AUDIO.maxDuration = 75000;

export { URLS, AUDIO };