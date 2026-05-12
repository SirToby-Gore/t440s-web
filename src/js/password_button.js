// a tri way state for the password toggle function 
// using symbols to say that they can not be replicated
PasswordStates = {
	toggle: Symbol(),
	show: Symbol(),
	hide: Symbol(),
}

/*
 * a class to manage the state of password viability
 **/
class PasswordButton {
	// the button its self
	passwordButton
	
	// the default state of the password viability
	passwordVisible = false;

	// the password elements
	inputElements;

	/*
	 * @param id: string
	 **/
	constructor(id) {
		this.passwordButton = this.getPasswordButton(id);

		this.passwordButton.addEventListener('click', () => {
			this.togglePasswords();
		})

		this.inputElements = this.getInputElements();

		this.togglePasswords(PasswordStates.hide);
	}

	/*
	 * @param id: string
	 * @return HTMLElement
	 **/
	getPasswordButton(id) {
		const passwordButton = document.getElementById('toggle-password-button');

		if (!passwordButton) {
			throw new Error(`${id} Not an element`);
		}

		if (!(passwordButton instanceof HTMLButtonElement)) {
			throw new Error(`${id} Not a password element`);
		}

		return passwordButton;
	}

	/*
	 * @return Array<HTMLInputElement>
	 **/
	getInputElements() {
		const elements = document.getElementsByTagName('input');
		let passwordElements = [];

		for (let i = 0; i < elements.length; i++) {
			const element = elements[i];

			if (element.type == 'password') {
				passwordElements.push(element);
			}
		}

		return passwordElements;
	}
		
	/*
	 * @param state: PasswordStates
	 * toggles the viability of all the password elements
	 **/
	togglePasswords(state = PasswordStates.toggle) {
		// sets the state of the of password visibility
		if (state == PasswordStates.toggle) {
			this.passwordVisible = !this.passwordVisible
		} else if (state == PasswordStates.hide) {
			this.passwordVisible = false;
		} else if (state) {
			this.passwordVisible = true;
		}

		// loops over the password elements
		this.inputElements.forEach((element) => {
			this.togglePassword(element);
		});

		// sets the inner text for the button
		this.passwordButton.innerText = {
			true: 'Hide',
			false: 'Show'
		}[this.passwordVisible.toString()] + ' Password';
	}
	
	/*
	 * toggles the visibility of an input element
	 **/
	togglePassword(inputElement) {
		if (this.passwordVisible) {
			inputElement.type = 'text'
		} else {
			inputElement.type = 'password'
		}
	}
}

// creates an instance of a password button
const pswBtn = new PasswordButton('toggle-password-button');