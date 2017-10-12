import Form from './Form';

export default class FormContainer {
  constructor(fields, defaultRows, resetOnSuccess = true) {
    this.fields = fields;
    this.errors = {};
    this.forms = [];
    this.defaultRows = defaultRows;
    this.resetOnSuccess = resetOnSuccess;

    this.makeRows();
  }

  makeRows() {
    for (let i = 0; i < this.defaultRows; i++) {
      this.forms.push(new Form(this.fields));
    }
  }

  addRow(index) {
    if (index === (this.forms.length - 1)) {
      this.forms.push(new Form(this.fields));
    }
  }

  reset() {
    this.forms = [];
    this.makeRows();
  }

  data() {
    const nonEmptyForms = this.forms.filter(form => !form.empty());

    return nonEmptyForms.map(form => form.data());
  }

  isEmpty() {
    return this.data().length <= 0;
  }

  submit(requestType, url) {
    return new Promise((resolve, reject) => {
      window.axios[requestType](url, this.data())
        .then((response) => {
          this.onSuccess(response.data);
          resolve(response.data);
        })
        .catch((error) => {
          this.onFail(error.response.data.errors);
          reject(error.response.data);
        });
    });
  }

  onSuccess() {
    if (this.resetOnSuccess) {
      this.reset();
    }
  }

  onFail(errors) {
    // TODO: This should be smarter - Test different types of errors
    if (typeof errors === 'undefined') {
      return;
    }

    this.formatErrors(errors);

    Object.keys(this.forms).forEach((key) => {
      if (Object.prototype.hasOwnProperty.call(this.errors, key)) {
        this.forms[key].errors.record(this.errors[key]);
      }
    });
  }

  formatErrors(errors) {
    this.errors = {};

    Object.keys(errors).forEach((key) => {
      const keySplit = key.split('.');
      const index = keySplit[0];
      const field = keySplit[1];

      if (!Object.prototype.hasOwnProperty.call(this.errors, index)) {
        this.errors[index] = {};
      }

      this.errors[index][field] = errors[key];
    });
  }

  post(url) {
    return this.submit('post', url);
  }

  delete(url) {
    return this.submit('delete', url);
  }

  patch(url) {
    return this.submit('patch', url);
  }
}
