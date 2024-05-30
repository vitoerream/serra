<!DOCTYPE html> 
<html> 
<head> 
	<title> 
		Dashboard Serra G.Peano
	</title>
    <link rel="stylesheet" href="style.css">
	<script src= 
"https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"> 
	</script> 
	<script src= 
"https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.1.2/chart.umd.js"> 
	</script> 
</head> 

<body>
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "";		
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		$sql = "SELECT * FROM DHT22 ORDER BY ID DESC LIMIT 30;";
		$result = $conn->query($sql) or die ('Query Error: ' . mysqli_error());
		$time=Array();
		$temp=Array();
		$umi=Array();
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			//$time[]=$row["ID"];
			//$temp[]=$row["temp"];
			//$umi[]=$row["umi"];
			array_unshift( $time, $row["ID"] );
			array_unshift( $temp, $row["temperature"]);
			array_unshift( $umi, $row["umidity"]);
		}
		$json_time = json_encode($time);
		$json_temp = json_encode($temp);
		$json_umi = json_encode($umi);
		$conn->close();
	?>
	<h1> 
		Serra G.Peano
	</h1> 
	<h3> 
		
	</h3> 
	



	<body>  
        <div style="background-color: white; width: 100%; height: 650px;">  
  
        <div style="background-color: white; width: 50%; height: 550px; float:left;">  
		<div class="canvas">
	<canvas id="stackedArea"
			width="200"
			height="50"> 
	</canvas> 
    <canvas id="stackedArea1"
			width="200"
			height="50"> 
	</canvas> 
	</div>
        </div>  
        <div style="background-color: white; width:50%; height: 550px; float:left;">  
		
		<p id="demo"></p>
		<p></p>
		<div class="container">
      <div class="display-date">
        <span id="day">day</span>,
        <span id="daynum">00</span>
        <span id="month">month</span>
        <span id="year">0000</span>
      </div>
      <div class="display-time"></div>
    </div>
	<img src="ca.jpg" alt="Serra" style="width:500px;height:500px;" class="center">

    </div> 




	<script> 
	    document.getElementById("demo").innerHTML = "";
		var time=<?php echo($json_time); ?>;
		var temp=<?php echo($json_temp); ?>;
		var umi=<?php echo($json_umi); ?>;
		var ctx = 
				document.getElementById('stackedArea').getContext('2d');
        var ctx1 = 
				document.getElementById('stackedArea1').getContext('2d'); 
		var myChart = new Chart(ctx, { 
			type: 'line', 
			data: { 
				labels: time,
				datasets: [ 
					{ 
						label: 'Temperatura C°', 
						data: temp,
						backgroundColor:  'rgba(243, 24, 18, 0.5)', 
						borderColor: 'rgba(243, 24, 18, 1)', 
						borderWidth: 2, 
						fill: true, 
					}, 
				
				] 
			}, 
			options: { 
				scales: { 
					y: { 
						beginAtZero: true, 
						//stacked: true, 
						title: { 
							display: true, 
							text: 'Temperatura' 
						} 
					}, 
					x: { 
						stacked: true 
					} 
				}, 
				layout: { 
					padding: { 
						left: 20, 
						right: 20, 
						top: 20, 
						bottom: 20 
					} 
				}, 
				plugins: { 
					legend: { 
						position: 'top', 
					}, 
				} 
			} 
		});
        var myChart = new Chart(ctx1, { 
			type: 'line', 
			data: { 
				labels: time,
				datasets: [ 
					{ 
						label: 'Umidità %', 
						data: umi,
						backgroundColor:  'rgba(37, 109, 123, 0.5)', 
						borderColor: 'rgba(37, 109, 123, 1)', 
						borderWidth: 2, 
						fill: true, 
					}, 
				
				] 
			}, 
			options: { 
				scales: { 
					y: { 
						beginAtZero: true, 
						//stacked: true, 
						title: { 
							display: true, 
							text: 'Umidità' 
						} 
					}, 
					x: { 
						stacked: true 
					} 
				}, 
				layout: { 
					padding: { 
						left: 20, 
						right: 20, 
						top: 20, 
						bottom: 20 
					} 
				}, 
				plugins: { 
					legend: { 
						position: 'top', 
					}, 
				} 
			} 
		});
	setTimeout(function(){
   		window.location.reload(1);
		}, 10000);


const displayTime = document.querySelector(".display-time");
// Time
function showTime() {
  let time = new Date();
  displayTime.innerText = time.toLocaleTimeString("en-US", { hour12: false });
  setTimeout(showTime, 1000);
}

showTime();

// Date
function updateDate() {
  let today = new Date();

  // return number
  let dayName = today.getDay(),
    dayNum = today.getDate(),
    month = today.getMonth(),
    year = today.getFullYear();

  const months = [
    "Gennaio",
    "Febbraio",
    "Marzo",
    "Aprile",
    "Maggio",
    "Giugno",
    "Luglio",
    "Agosto",
    "Settembre",
    "Ottobre",
    "Novembre",
    "Dicembre",
  ];
  const dayWeek = [
    "Domenica",
    "Lunedì",
    "Martedì",
    "Mercoledì",
    "Giovedì",
    "Venerdì",
    "Sabato",
  ];
  // value -> ID of the html element
  const IDCollection = ["day", "daynum", "month", "year"];
  // return value array with number as a index
  const val = [dayWeek[dayName], dayNum, months[month], year];
  for (let i = 0; i < IDCollection.length; i++) {
    document.getElementById(IDCollection[i]).firstChild.nodeValue = val[i];
  }
}

updateDate();

	</script> 
</body> 

</html>
