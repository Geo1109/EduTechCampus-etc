import RPi.GPIO as GPIO

from time import time, sleep

from vars import IR_senzor

GPIO.setmode(GPIO.BOARD)

GPIO.setup(11, GPIO.IN, pull_up_down = GPIO.PUD_UP)

GPIO.setup(33, GPIO.OUT)

p = GPIO.PWM(33, 50)

p.start(2.5)

p.ChangeDutyCycle(0)

def servo_open():
    global time_passed2

    time_passed2 = time()

    while True:
        if time() - time_passed2 >1:

            p.start(2.5)

            p.ChangeDutyCycle(10.5)

            servo_pos = 1

            break
    while True:
        if time() - time_passed2 >1.1:
            p.ChangeDutyCycle(0)
            
            break

def servo_close():
    global time_passed2

    while True:
        if time() - time_passed2 >15 or GPIO.input(IR_senzor):
            sleep(1)
            p.ChangeDutyCycle(2.5)

            break
    while True:
        if time() - time_passed2 >15 or GPIO.input(IR_senzor):
            sleep(1.1)
            p.ChangeDutyCycle(0)
            
            break
