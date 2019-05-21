import subprocess
import Adafruit_DHT
import RPi.GPIO as GPIO
import os
os.environ['PYTHON_EGG_CACHE'] = '__pycache__' 
class thermostat:
	Target_Temperature = 65
	Mode = "OFF"
	__HEAT_Pin = 2
	__COOL_Pin = 3
	__Sensor_Pin=4
	__ConfigFile="/home/pi/Apps/Thermostat/thermostat.conf"


	def __init__(self, **kwargs):
		GPIO.setmode(GPIO.BCM)
		GPIO.setwarnings(False)
		conf_file = open(self.__ConfigFile,"r")
		for line in conf_file:
			if line: 
				kv = line.split("=")
				if kv[0] == "TargetTemperature":
					self.Target_Temperature = int(kv[1].strip())
					continue
				if kv[0] == "Mode":
					self.Mode = kv[1].strip()
					continue
		
		GPIO.setup([self.__HEAT_Pin,self.__COOL_Pin],GPIO.OUT)
		#GPIO.output([self.__HEAT_Pin,self.__COOL_Pin],(GPIO.HIGH, GPIO.HIGH))
		
		return 
	
	''' Read-only attribute'''
	@property
	def Current_Temperature(self):
		sensor = Adafruit_DHT.DHT22
		humidity, temp = Adafruit_DHT.read_retry(sensor,self.__Sensor_Pin)
		temp = round(temp * 9/5.0 + 32,2)
		return temp
	
	@property
	def Current_Temperature_Humidity(self):
		sensor = Adafruit_DHT.DHT22
		humidity, temp = Adafruit_DHT.read_retry(sensor,self.__Sensor_Pin)
		temp = round(temp * 9/5.0 + 32,2)
		humidity=round(humidity,2)
		return temp, humidity

	@property 
	def Status(self):
		if GPIO.input(self.__HEAT_Pin)==GPIO.LOW:
			return "HEAT"
		elif GPIO.input(self.__COOL_Pin)==GPIO.LOW:
			return "COOL"
		else:
			return "OFF"

	def Set(self, targetTemp, Mode):
		self.Target_Temperature = int(targetTemp)
		self.Mode = Mode
		conf_file = open(self.__ConfigFile,"w+")
		'''with open("thermostat.conf","w") as conf_file:'''
		conf_file.write("TargetTemperature=%s\n" % self.Target_Temperature)
		conf_file.write("Mode=" + self.Mode + "\n")
		conf_file.close()
		self.Process()

	def Process(self):
		temp=self.Current_Temperature
		mode=self.Mode
		
		if self.Target_Temperature - temp > 1 and (mode == "HEAT" or mode=="AUTO"):
			# Set HEAT=ON, COOL=OFF
			GPIO.output([self.__HEAT_Pin,self.__COOL_Pin],(GPIO.LOW, GPIO.HIGH))
			return "ON"
		elif temp - self.Target_Temperature > 1 and (mode == "COOL" or mode=="AUTO"):
			# Process Cool Logic
			#Set HEAT=OFF, COOL=ON"
			GPIO.output([self.__HEAT_Pin,self.__COOL_Pin],(GPIO.HIGH, GPIO.LOW))
			return "ON"
		else:
			GPIO.output([self.__HEAT_Pin,self.__COOL_Pin],(GPIO.HIGH, GPIO.HIGH))
			return "OFF"
		GPIO.cleanup()
		
	




		


		








