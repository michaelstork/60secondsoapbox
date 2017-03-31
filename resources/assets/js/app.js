import Vue from 'vue';
import Resource from 'vue-resource';
import AuthPanel from './components/panels/AuthPanel.vue';
import InfoPanel from './components/panels/InfoPanel.vue';
import AudioPanel from './components/panels/AudioPanel.vue';
import CitationsPanel from './components/panels/CitationsPanel.vue';
import NomineesPanel from './components/panels/NomineesPanel.vue';
import {URLS} from './config/index';


Vue.use(Resource);

const eventHub = new Vue();
const appData = {};

Vue.mixin({
    data: function () {
        return {
            eventHub: eventHub
        }
    }
});

appData.layout = {
    panels: [
        { name: 'auth', title: 'Login', id: 'soapbox-auth-panel'},
        { name: 'info', title: 'Your Information', id: 'soapbox-info-panel'},
        { name: 'audio', title: 'Your Audio Submission', id: 'soapbox-audio-panel'},
        { name: 'citations', title: 'Title & Citations', id: 'soapbox-citations-panel' },
        { name: 'nominees', title: 'Almost Done!', id: 'soapbox-nominees-panel'}
    ],
    activePanelIndex: 0
};

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', window.Laravel.csrfToken);
    next();
});

Vue.http.interceptors.push((request, next) => {
    const token = localStorage.getItem('soapboxToken');
    if (token) request.headers.set('Authorization', 'Bearer ' + token);
    next();
});


localStorage.removeItem('soapboxSubmission');
localStorage.removeItem('soapboxToken');

new Vue({
    el: '.vue-mount',
    data: appData,
    computed: {
        activePanel: function () {
            return this.layout.panels[this.layout.activePanelIndex];
        },
        panelStyle: function () {
            return {
                transform: 'translateX(' + (this.layout.activePanelIndex * -100) + '%)'
            };
        }
    },
    created: function () {
        this.layout.panels.forEach((panel, p) => {
            this.$set(this.layout.panels[p], 'valid', false);
        });

        this.eventHub.$on('panelValidityChange', this.onPanelValidityChange);
        this.eventHub.$on('requestPanelNavigation', this.onRequestPanelNavigation);
        this.eventHub.$on('commitSavedData', this.commitSavedData);
    },
    methods: {
        onRequestPanelNavigation: function () {
            if (this.activePanel.valid) this.navigateForward();
        },
        onPanelValidityChange: function (name, status) {
            console.log('panelValidityChange: '+name+' -> '+status);
            this.getPanelByName(name).valid = status;
        },
        getPanelByName: function (name) {
            return this.layout.panels.find(panel => panel.name === name);
        },
        navigateForward: function () {
            this.eventHub.$emit('panelNavigation');
            this.layout.activePanelIndex += 1;
        },
        commitSavedData: function () {
            const data = JSON.parse(localStorage.getItem('soapboxSubmission'));
            
            this.$http.post(
                URLS.submission,
                data
            ).then(
                response => {
                    console.log(response);
                    this.eventHub.$emit('submissionComplete');
                },
                errorResponse => {
                    console.log(errorResponse);
                }
            );
        }
    },
    components: {
        'soapbox-auth-panel': AuthPanel,
        'soapbox-info-panel': InfoPanel,
        'soapbox-audio-panel': AudioPanel,
        'soapbox-citations-panel': CitationsPanel,
        'soapbox-nominees-panel': NomineesPanel
    }
});