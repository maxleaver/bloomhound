import Errors from './Errors';

export default class Form {
  constructor(data) {
    this.originalData = data;
    this.errors = new Errors();

    Object.keys(data).forEach((key) => {
      this[key] = data[key];
    });
  }

  reset() {
    Object.keys(this.originalData).forEach((key) => {
      this[key] = '';
    });

    this.errors.clear();
  }

  data() {
    const data = {};

    Object.keys(this.originalData).forEach((key) => {
      data[key] = this[key];
    });

    return data;
  }

  submit(requestType, url) {
    return new Promise((resolve, reject) => {
      window.axios[requestType](url, this.data())
        .then((response) => {
          console.log('Success: ', response.data);
          this.onSuccess(response.data);
          resolve(response.data);
        })
        .catch((error) => {
          console.log('Errors: ', error.response.data.errors);
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
