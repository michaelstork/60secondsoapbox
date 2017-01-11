
const URLS = {};

URLS.cordova = {

};

URLS.web = {
	authenticate: '/authenticate',
	validateNominee: '/api/validate-nominee',
	audioUpload: '/api/user-audio',
	audioDelete: '/api/user-audio',
	submission: '/api/submission'
};

const AUDIO = {};

AUDIO.minDuration = 5000;
AUDIO.maxDuration = 75000;

export { URLS, AUDIO };