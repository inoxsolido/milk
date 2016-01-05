numeral.fn.floor = function(){
    var val = this._value;
    this._value = Math.floor(val*100)/100;
    return this;
};


