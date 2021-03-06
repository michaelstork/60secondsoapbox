import authFormDef from './def/auth.json';
import infoFormDef from './def/info.json';
import citationsFormDef from './def/citations.json';
import nomineesFormDef from './def/nominees.json';

class SoapboxForm {
	constructor (def) {
		this.data = {
			name: def.name,
			valid: false,
			fields: def.fields.map(field => {
				if (field.async) {
					field.asyncValid = false;
					field.asyncPending = false;
					field.asyncError = null;
				}
				
				return field;
			})
		};
	}
}

const authForm = new SoapboxForm(authFormDef).data;
const infoForm = new SoapboxForm(infoFormDef).data;
const citationsForm = new SoapboxForm(citationsFormDef).data;
const nomineesForm = new SoapboxForm(nomineesFormDef).data;

export {authForm, infoForm, citationsForm, nomineesForm};