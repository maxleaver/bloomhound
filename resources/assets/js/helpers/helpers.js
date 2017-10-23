module.exports = {
  calculateSum: function (items, field) {
    let sum = 0;

    if (typeof items !== 'undefined' && items.length > 0) {
      sum = items.reduce((total, item) => total + (item[field]), 0);
    }

    return Number(sum);
  },
};
