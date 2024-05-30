#include <WiFiNINA.h>
#include <utility/wifi_drv.h>
#include <DHT.h>
#define DHTPIN 8   //Pin a cui è connesso il sensore
#define DHTTYPE DHT22   //Tipo di sensore che stiamo utilizzando (DHT22)
DHT dht(DHTPIN, DHTTYPE); //Inizializza oggetto chiamato "dht", parametri: pin a cui è connesso il sensore, tipo di dht 11/22
//Variabili


float hum;  //Variabile in cui verrà inserita la % di umidità
float temp; //Variabile in cui verrà inserita la temperatura
String url = "/input.php";

//parametri
const char ssid[] = "Liceo_WIFI";  // Nome del WiFi (SSD)
const char pass[] = "";   // Password del WIFI, in caso sia senza password lasciare vuoto -> "";
const char* host = "51.77.202.143"; //Indirizzo IP pubblico (in questo caso dinamico) del server online
const int httpPort = 80; //post dell'HTTP, in genere si usa la porta 80
String HTTP_METHOD = "POST";  //POST

//Connessione Access Point
WiFiClient client;
int status = WL_IDLE_STATUS;

void setup() {
  Serial.begin(9600);
  dht.begin();

  //Verifica Hardware Wifi
  if (WiFi.status() == WL_NO_MODULE) {
    Serial.println("\n[HARDWARE]->Comunicazione con il modulo fallita!");
    //Stop
    while (true)
      ;
  }
  //Verifico aggiornamenti del firmware di arduino
  String fv = WiFi.firmwareVersion();
  if (fv < WIFI_FIRMWARE_LATEST_VERSION) {
    Serial.println("\n[HARDWARE/FIRMWARE]->Aggiorna il Firmware...");
  }

  //Tentativo di connessione all'Access Point
  while (status != WL_CONNECTED) {
    Serial.print("[WIFI]->Tentativo di connessione a:");
    Serial.print(ssid);
    //Connessione
    status = WiFi.begin(ssid, pass);
        Serial.println("[WIFI]->Connessione in corso...");
    delay(10000);
  }

  //Stampa indirizzo IP Locale
  Serial.print("[WIFI]->Indirizzo IP Locale: ");
  Serial.println(WiFi.localIP());
  }


void loop() {
  delay(2000);  //Ritardo di 2 secondi.
  //Leggi i dati e salvali nelle variabili hum e temp
  hum = dht.readHumidity();
  temp= dht.readTemperature();
  //Stampa umidità e temperatura tramite monitor seriale
  Serial.print("[HARDWARE]->Umidità: ");
  Serial.print(hum);
  Serial.print(" %, Temp: ");
  Serial.print(temp);
  Serial.println(" Celsius");
  //verifica stringa payload, in caso di errata lettura nan non verrà caricato per via di uno spazio -> " nan";
  Serial.print("[PAYLOAD]->Pacchetto dati da inviare -->");
  String payload = "temp=" + String(temp) + "&umi=" + String(hum) + "&chiave=rc23";
  Serial.println(payload);

  //Connessione al server
  if (!client.connect(host, httpPort)) {
    Serial.println("[ERRORE]->Connessione al server fallita, controlla l'host e la porta...");
    return;
  }
  //POST
  Serial.println("[CLIENT]->POST in uscita...");
  client.println("POST " + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" +
               "Content-Type: application/x-www-form-urlencoded\r\n" +
               "Content-Length: " + payload.length() + "\r\n" +
               "Connection: close\r\n\r\n" +
               payload);
  Serial.println("[CLIENT]->POST inviata...");
   while (client.connected()) {
      if (client.available()) {
        //Leggi i dati dal server
        char c = client.read();
        Serial.print(c);
      }
    }
    Serial.println("\n[CLIENT]->Disconnessione dal server...");
    //Disconnessione client, server in attesa di una nuova connessione (in loop)
    client.stop();
    unsigned long previousMillis = 0;
    unsigned long interval = 30000;
    void loop() {
  unsigned long currentMillis = millis();

  //Riconnessione in caso di disconnessione
  if ((WiFi.status() != WL_CONNECTED) && (currentMillis - previousMillis >=interval)) {
      Serial.print(millis());
      Serial.println("Reconnecting to WiFi...");
      WiFi.disconnect();
      WiFi.reconnect();
      previousMillis = currentMillis;
   }
      
  }
    delay(5000);

}
