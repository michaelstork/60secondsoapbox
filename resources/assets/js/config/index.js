
const URLS = {};

URLS.cordova = {

};

URLS.web = {
	authenticate: '/authenticate',
	validateNominee: '/api/validate-nominee',
	audioUpload: '/api/user-audio',
	audioDelete: '/api/user-audio',
	submission: '/api/submission',
	userInfo: '/user-info',
	deleteUser: '/delete-user',
	resendInvitation: '/resend-invitation'
};

const AUDIO = {};

AUDIO.minDuration = 45000;
AUDIO.maxDuration = 65000;

export { URLS, AUDIO };