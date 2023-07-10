import RPi.GPIO as GPIO

from time import sleep, time

from threading import Thread

from vars import RED_LED, GREEN_LED, Pin_deschis, Pin_inchis, IR_senzor

GPIO.setwarnings(False)

GPIO.setmode(GPIO.BOARD)

GPIO.setup(RED_LED, GPIO.OUT)
GPIO.setup(GREEN_LED, GPIO.OUT)
GPIO.setup(Pin_inchis, GPIO.OUT)
GPIO.setup(Pin_deschis, GPIO.OUT)
GPIO.output(Pin_deschis, 1)
GPIO.output(Pin_inchis, 1)

def led_loop():
    global time_passed

    time_passed = time()

    while True:
        if time() - time_passed > 0.2:
            GPIO.output(RED_LED, 0)

            GPIO.output(GREEN_LED, 0)

            GPIO.output(Pin_deschis, 1)
            GPIO.output(Pin_inchis, 1)

        sleep(0.1)

Thread(target = led_loop).start()

def record_time():
    global time_passed
    
    time_passed = time()