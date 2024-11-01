jQuery(document).ready(function($){

	var nonce;

	// Set up the inputs
	$('.calc-value').each(function(){
		var calc = $(this).closest('.wprequal-calc');
		var value = $(this).siblings('.calc-slider').val();
		var numCommas = numFormat(value);
		$(this).html(numCommas);
		getPayment(calc);
	});

	$( '.input-range' ).on( 'input change', function(){
		var calc = $(this).closest('.wprequal-calc');
		var value = $(this).val();
		var numCommas = numFormat(value);
		$(this).siblings('.calc-value').html(numCommas);
		getPayment(calc);
	});

	$('.get-quote .button').on('click', function(e) {
		e.preventDefault();
		var form = $(this).closest('.wprequal-calc');
		form.find('.get-quote-contact').slideDown(700);
	});

	$('.get-quote-close').on('click', function() {
		var form = $(this).closest('.wprequal-calc');
		form.find('.get-quote-contact').slideUp(700);
	});

});

function getPayment(calc) {

	var loan = getInt(calc, '.purchase-price');
	var tax = getInt(calc, '.property-tax');
	var ins = getInt(calc, '.insurance');
	var ti = parseInt(tax + ins);
	var down = getInt(calc, '.down-payment');
	var rate = getFloat(calc, '.interest-rate');
	var term = getInt(calc, '.loan-term') / wprequalCalc.loanTermType;
	var amount = (loan - down);
	var amount = parseInt(amount);
	var payment = getMonthlyPayment(amount, (rate /100) / 12, term * 12, ti);

	if(payment) {
		calc.find('.calc-payment-amount').html( numFormat(payment) );
	}
}

function getInt(c, x){
	var y = c.find(x).val();
	y = y || '0';
	return parseInt(y.replace(/\,/g,''));
}

function getFloat(c, x){
	var y = c.find(x).val();
	y = y || '0';
	return parseFloat(y.replace(/[\,+\%]/g,''));
}

function getMonthlyPayment(PR, IN, PE, TI) {

	var PAY = (PR * IN) / (1 - Math.pow(1 + IN, -PE));
	var PAY = PAY + (TI / 12);
	var PAY = parseInt(PAY);

	return PAY;
}

function numFormat(x) {
	if(typeof x !== 'undefined'){
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}
	return x;
}

// rounds a number to two decimal places
function round(x) {
	return Math.round(x*100)/100;
}