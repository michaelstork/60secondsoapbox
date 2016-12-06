import authFormDef from './def/auth.json';
import infoFormDef from './def/info.json';
import nomineesFormDef from './def/nominees.json';

class SoapboxForm {
	constructor (def) {
		this.data = {
			name: def.name,
			valid: false,
			fields: def.fields.map(field => {
				if (field.async) field.asyncValid = false;
				return field;
			})
		};
	}
}

const authForm = new SoapboxForm(authFormDef).data;
const infoForm = new SoapboxForm(infoFormDef).data;
const nomineesForm = new SoapboxForm(nomineesFormDef).data;

export {authForm, infoForm, nomineesForm};