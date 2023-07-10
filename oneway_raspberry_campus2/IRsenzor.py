import RPi.GPIO as GPIO  
from time import sleep, time    # this lets us have a time delay (see line 15)  


GPIO.setmode(GPIO.BOARD)
GPIO.setup(11, GPIO.IN, pull_up_down = GPIO.PUD_UP)

try:  
    while True:            # this will carry on until you hit CTRL+C  
        if GPIO.input(IR_senzor): # if port 25 == 1  
            print("ON")

        else:  
            print("OFF")
            break
finally KeyboardInterrupt:
    break

