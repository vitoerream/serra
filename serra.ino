#include <WiFiS3.h>

const char ssid[] = "";  // change your network SSID (name)
const char pass[] = "";   // change your network password (use for WPA, or use as key for WEP)

WiFiClient client;
int status = WL_IDLE_STATUS;

int HTTP_PORT = 80;
String HTTP_METHOD = "POST";  // or POST
char server[] = "";

void setup() {
  Serial.begin(9600);

  // check for the WiFi module:
  if (WiFi.status() == WL_NO_MODULE) {
    Serial.println("Communication with WiFi module failed!");
    // don't continue
    while (true)
      ;
  }

  String fv = WiFi.firmwareVersion();
  if (fv < WIFI_FIRMWARE_LATEST_VERSION) {
    Serial.println("Please upgrade the firmware");
  }

  // attempt to connect to WiFi network:
  while (status != WL_CONNECTED) {
    Serial.print("Attempting to connect to SSID: ");
    Serial.println(ssid);
    // Connect to WPA/WPA2 network. Change this line if using open or WEP network:
    status = WiFi.begin(ssid, pass);

    // wait 10 seconds for connection:
    delay(10000);
  }

  // print your board's IP address:
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());

  if (client.connect(server, 80)) {
  		client.println("POST /serra1.html HTTP/1.1");
  		client.print("Host: ");
  		client.println(server);
  		client.println("Content-Type: application/x-www-form-urlencoded");
  		client.print("temp=");
  		client.print("242");//dht22_0.readTemperature();
  		client.print("&umi=");
  		client.print("473");//dht22_0.readHumidity();
  		client.println("&chiave=rc23");
  }

    while (client.connected()) {
      if (client.available()) {
        // read an incoming byte from the server and print it to serial monitor:
        char c = client.read();
        Serial.print(c);
      }
    }

    // the server's disconnected, stop the client:
    client.stop();
}
void loop() {
}
