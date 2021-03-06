export default class Errors {
  constructor() {
    this.errors = {};
  }

  has(field) {
    return Object.prototype.hasOwnProperty.call(this.errors, field);
  }

  get(field) {
    if (this.errors[field]) {
      return this.errors[field][0];
    }

    return false;
  }

  record(errors) {
    this.errors = errors;
  }

  clear(field) {
    if (field) {
      delete this.errors[field];
      return;
    }

    this.errors = {};
  }

  any() {
    return Object.keys(this.errors).length > 0;
  }
}
