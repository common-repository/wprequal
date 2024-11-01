// https://codepen.io/joeymack47/pen/fHwvd
// https://tobiasahlin.com/blog/chartjs-charts-to-get-you-started/#5-polar-area-chart
jQuery(document).ready(function($){

	var thisAmortize = $('.wprequal-amortize');
	var month = 1;

	if (thisAmortize.length) {
		buildData();

		thisAmortize.find('.input-range').on( 'change', function() {
			buildData();
		});

		thisAmortize.find('.calc-slider.months').on( 'input change', function() {
			month = $(this).val();
			updateData();
		});

		var ctx = document.getElementById('amortize-graph').getContext('2d');

		var options = {
			responsive: true,
			legend: {
				display: true,
				position: 'bottom',
				labels: {
					fontColor: '#333',
					fontSize: 16
				}
			},
			animation: {
				animateRotate: true
			}
		};

		// For a pie chart
		var myPieChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				datasets: [{
					data: getData(),
					backgroundColor: Amortize.backgroundColor,
					borderColor: Amortize.borderColor,
					borderWidth: 1
				}],

				// These labels appear in the legend and in the tooltips when hovering different arcs
				labels: Amortize.labels
			},
			options: options
		});

	}

	function buildData() {

		Amortize.price = getInt(thisAmortize, '.purchase-price');
		Amortize.down = getInt(thisAmortize, '.down-payment');
		Amortize.rate = ( getFloat(thisAmortize, '.interest-rate') / 100.0 );
		Amortize.term = ( getInt(thisAmortize, '.loan-term') * 12 );
		Amortize.mRate = (Amortize.rate / 12); // Monthly Rate
		Amortize.payment = getMonthlyPayment(Amortize.price, Amortize.mRate, Amortize.term, 0);
		Amortize.months = getMonths();

		thisAmortize.find('.calc-slider.months').prop('max', Amortize.term);

		setAmortize();
	}

	function getMonths() {

		var months = new Array();
		var balance = Amortize.price;
		var payment = Amortize.payment;

		for ( var count = 0; count <= Amortize.term; ++count) {

			var iPayment = balance * Amortize.mRate;

			if ( balance < payment ) {
				var pPayment = balance;
				balance = 0;
			} else {
				var pPayment = (payment - +iPayment);
				balance = (balance - +pPayment);
			}

			months[count] = new Object();
			months[count].iPayment = iPayment.toFixed(2);
			months[count].pPayment = pPayment.toFixed(2);
			months[count].balance  = balance.toFixed(2);

		}

		return months;

	}

	function iPayment() {
		return Amortize.months[month].iPayment;
	}

	function pPayment() {
		return Amortize.months[month].pPayment;
	}

	function balance() {
		return Amortize.months[month].balance;
	}

	function getData() {
		return [pPayment(), iPayment()];
	}

	function updateData(){
		var data = getData();
		myPieChart.data.datasets[0].data = data;
		myPieChart.update();

		setAmortize();
	}

	function setAmortize() {
		var results = thisAmortize.find('.results');
		results.find('.month span').html(month);
		results.find('.principal span').html(numFormat(pPayment()));
		results.find('.interest span').html(numFormat(iPayment()));
		results.find('.balance span').html(numFormat(balance()));
	}

});