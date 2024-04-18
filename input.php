<?
$servername = "localhost";
$username = "root";
$password = "pollo123";
$dbname = "temphumid";
$pass = "rc23";
$ID = $_POST['ID'];
$temperature = $_POST['temp'];
$umidita = $_POST['umi'];
$key = $_POST['chiave'];

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($key == $pass ) {
  echo "Autorizzato!". "<br>";
  $sql = "INSERT INTO DHT22 (ID, temperature, umidity)
  VALUES ('$ID', '$temperature', '$umidita')";
  }
  if ($conn->query($sql) === TRUE) {
    echo "Nuovo record creato!";
  } else {
    echo "Errore: " . $sql . "<br>" . $conn->error;
  }
}
else {
  echo "Accesso non consentito" . "<br>";
}

$conn->close();


?>


