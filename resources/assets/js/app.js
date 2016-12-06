import Vue from 'vue';
import Resource from 'vue-resource';

import AuthPanel from './components/panels/AuthPanel.vue';
import InfoPanel from './components/panels/InfoPanel.vue';
import AudioPanel from './components/panels/AudioPanel.vue';
import NomineesPanel from './components/panels/NomineesPanel.vue';

import {URLS} from './config/index';

Vue.config.devtools = true

Vue.use(Resource);

const appData = {};

appData.layout = {
    panels: [
        { name: 'auth', title: 'Login', id: 'soapbox-auth-panel'},
        { name: 'info', title: 'Your Information', id: 'soapbox-info-panel'},
        { name: 'audio', title: 'Your Audio Submission', id: 'soapbox-audio-panel'},
        { name: 'nominees', title: 'Almost Done!', id: 'soapbox-nominees-panel'}
    ],
    activePanelIndex: 0
};

const eventHub = new Vue();

Vue.mixin({
    data: function () {
        return {
            env: window.cordova ? 'cordova' : 'web',
            eventHub: eventHub
        }
    }
})

if (window.cordova) {
    document.addEventListener('deviceready', function () {
        init();
    }, false);
} else {
    Vue.http.interceptors.push((request, next) => {
        request.headers.set('X-CSRF-TOKEN', window.Laravel.csrfToken);
        next();
    });
    init();
}

function init () {
    localStorage.removeItem('soapboxSubmission');

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
            },
            canNavigateBack: function () {
                return (this.layout.activePanelIndex > 1);
            },
            canNavigateForward: function () {
                return (this.activePanel.valid);
            }
        },
        created: function () {
            this.layout.panels.forEach((panel, p) => {
                this.$set(this.layout.panels[p], 'valid', false);
            });

            this.eventHub.$on('panelValidityChange', this.onPanelValidityChange);
            this.eventHub.$on('commitSavedData', this.commitSavedData);
        },
        methods: {
            onPanelValidityChange: function (name, status) {
                console.log('panelValidityChange: '+name+' -> '+status);
                this.getPanelByName(name).valid = status;
            },
            getPanelByName: function (name) {
                return this.layout.panels.find(panel => panel.name === name);
            },
            navigateBack: function () {
                this.eventHub.$emit('panelNavigation');
                this.layout.activePanelIndex -= 1;
            },
            navigateForward: function () {
                this.eventHub.$emit('panelNavigation');
                this.layout.activePanelIndex += 1;
            },
            commitSavedData: function () {
                const data = JSON.parse(localStorage.getItem('soapboxSubmission'));
                
                this.$http.post(
                    URLS[this.env].submission,
                    data
                ).then(
                    response => {
                        console.log(response);
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
            'soapbox-nominees-panel': NomineesPanel
        }
    });    
}
