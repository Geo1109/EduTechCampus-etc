from machine import Pin, Timer
from hx711 import HX711
import time
from wifi import connectToWifi, open_socket, serve



timer = Timer(-1)
# GPIO pins for the HX711 module
pd_sck = Pin(27, Pin.OUT)
dout = Pin(28, Pin.IN)

# GPIO pins for the 3-color LED (GP2, GP3, GP4)
led_red = Pin(2, Pin.OUT)
led_green = Pin(3, Pin.OUT)
led_blue = Pin(4, Pin.OUT)

water_drank = 0
# Create an instance of the HX711 class
load_cell = HX711(pd_sck, dout)


water_level = 0

time_passed = time.ticks_ms() // 1000

water_level2 = water_level + 80
# Set the gain of the HX711 (options: 128, 64, 32)
load_cell.set_gain(128)

# Perform Tare (optional, but recommended)
load_cell.tare()

# Set the calibration factor for 500 grams
load_cell.set_scale(738)  # Adjust this value based on your calibration

# Set the time interval in seconds (5 seconds)
time_interval_seconds = 5

# Set the threshold for detecting weight changes (adjust based on your needs)
weight_threshold = 10  # For example, consider it changed if the weight differs by 5 grams

# Set the time threshold in seconds to consider a weight stable
stable_time_threshold = 5   # 5 seconds for testing! 30 min real use

# Variables to store the last weight reading and its timestamp
last_weight_reading = int(load_cell.get_units())
last_reading_time = time.ticks_ms()

# Function to turn on the LED with the specified color
def turn_on_led(color):
    led_red.off()
    led_green.off()
    led_blue.off()

    if color == 'red':
        led_red.on()
    elif color == 'green':
        led_green.on()
    elif color == 'blue':
        led_blue.on()
        
#blink the red LED when the time is reached        
def red_led_blink():
    led_green.off()
    led_blue.off()
    led_red.value(not led_red.value())
        
        
        
        
        
        
        
        
turn_on_led('blue')
ip = connectToWifi()
print(ip)
connection = open_socket(ip)
result = serve(connection)

amount_to_drink=result/48
        
while True:
    
    weight_in_grams = int(load_cell.get_units())

    if abs(weight_in_grams - last_weight_reading) >= weight_threshold or weight_in_grams < 20:
        last_reading_time = time.ticks_ms()
        last_weight_reading = weight_in_grams
        weight_in_grams = int(load_cell.get_units())
        turn_on_led('green')
        if weight_in_grams == last_weight_reading:
            water_level = weight_in_grams
    else: 
        if( last_weight_reading == weight_in_grams ):
            water_level = weight_in_grams
            #
            if (water_level2 > water_level):
                
                print(stable_time_threshold,"   ", 36 * ( water_level2 - water_level ),"  ",time.ticks_ms()//1000 - time_passed)
               
                stable_time_threshold = stable_time_threshold + ( 5 * ( water_level2 - water_level )/amount_to_drink )   # for real use
               
                last_weight_change_time = time.ticks_ms() // 1000
                
                print(water_level2 - water_level)
                
                print("timp           egal:", stable_time_threshold )
                
            water_level2 = weight_in_grams
            print("water         level=", water_level)
        last_weight_reading = weight_in_grams
            

    current_time = time.ticks_ms()

    elapsed_time_seconds = (current_time - last_reading_time) // 1000

    if elapsed_time_seconds >= stable_time_threshold:
        weight_before_drinking = weight_in_grams
        last_weight_reading2 = weight_in_grams
        time.sleep(1)
        print("g.inainte", weight_before_drinking)
        
        while True:
            
            last_weight_reading2 = weight_in_grams;
            print("grutate1", last_weight_reading2)
            print("Bea apa")
            red_led_blink()
               
            
            weight_in_grams = int(load_cell.get_units())
            print("grutate2", weight_in_grams)
            if (20 < weight_in_grams <= (weight_before_drinking - amount_to_drink)) and (last_weight_reading2 == weight_in_grams):
                break
        stable_time_threshold = 5 #reset timer
        
        water_drank = water_drank + weight_in_grams #store amount of water drank

    if 20 < weight_in_grams < 50:
        while 20 < weight_in_grams < 50:
            turn_on_led('blue')
            weight_in_grams = int(load_cell.get_units())
    print("Weight [g]:", weight_in_grams)
    
 
 

    


