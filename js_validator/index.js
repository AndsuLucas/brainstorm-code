class Validator {

    errorMessages = new Map();

    validators = new Map();

    errors = new Map();

    registerValidator(name, fn) {
        this.validators.set(name, fn);
    }

    registerMessages(messagesByField) {
        this.errorMessages = new Map(Object.entries(messagesByField));
    }

    validate(data, rules) {
        for (let field in rules) {
            const fieldRules = rules[field];
            const value = data[field];
            this.executeValidateForEachRule(field, fieldRules, value);
        }

        return this.errors;
    }

    executeValidateForEachRule(field, fieldRules, value) {

        for (let rule of fieldRules) {
            const [ruleName, ...params] = rule.split(':');
            if (!this.validators.has(ruleName)) {
                continue;
            }

            const validatorCallback = this.validators.get(ruleName);
            const isValid = validatorCallback(value, ...params);
            if (isValid) {
                continue;
            }


            this.pushNewError(field, params, ruleName);
        }
    }

    pushNewError(field, params, ruleName) {
        const errorsData = this.errors.has(field) ?
            this.errors.get(field) : [];

        const messageData = this.errorMessages.get(field);

        const message = messageData && messageData[ruleName] ?
            messageData[ruleName] : `{field} é inválido.`;

        const newMessage = message.replace("{field}", field).replace("{param}", params.join(','));

        this.errors.set(field, [...errorsData, newMessage]);
    }
}

const customMessages = {
    password: {
        minLength: "A senha deve ter pelo menos {param} caracteres"
    }
};

const formValidator = new Validator();

formValidator.registerMessages(customMessages);
formValidator.registerValidator("required", value => value !== undefined && value !== "");
formValidator.registerValidator("minLength", (value, length) => value.length >= parseInt(length));
formValidator.registerValidator("maxLength", (value, length) => value.length <= parseInt(length));

const data = {
    name: "",
    password: "123"
};

const rules = {
    name: ["required"],
    email: ["required", "email"],
    password: ["required", "minLength:6"]
};


const errors = formValidator.validate(data, rules)

console.log(errors);