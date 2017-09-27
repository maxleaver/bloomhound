import Errors from './Errors';

export default class Form {
  constructor(data, resetOnSubmit = true) {
    this.originalData = data;
    this.errors = new Errors();
    this.resetOnSubmit = resetOnSubmit;

    // Convert data fields into properties of the form
    Object.keys(data).forEach((key) => {
      this[key] = data[key];
    });
  }

  reset() {
    if (this.resetOnSubmit) {
      Object.keys(this.originalData).forEach((key) => {
        this[key] = '';
      });

      this.errors.clear();
    }
  }

  data() {
    const data = {};

    Object.keys(this.originalData).forEach((key) => {
      data[key] = this[key];
    });

    return data;
  }

  empty() {
    const data = this.data();
    let isEmpty = true;

    Object.values(data).forEach((value) => {
      if (value) {
        isEmpty = false;
      }
    });

    return isEmpty;
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
    this.reset();
  }

  onFail(errors) {
    this.errors.record(errors);
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
