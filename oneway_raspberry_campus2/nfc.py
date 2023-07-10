from mfrc522 import SimpleMFRC522

from requests import get

from playsound import playsound

from threading import Thread

from time import time

from led import GPIO, record_time

from vars import RED_LED, GREEN_LED, Pin_inchis, Pin_deschis, IR_senzor

from random import randint

import datetime

from servo import servo_open, servo_close

reader = SimpleMFRC522()

sound_done = 0

def nfc_loop():
    global sound_done
    
    while True:
        id, text = reader.read()

        print(id, text)

        r = get(f'https://campus.lmvineu.ro/google_login/getBilet.php?opt=tot&mac={text}')

        valid = False

        for row in r.json():
            data1 = row['data1']
            ora1 = row['ora1']

            data2 = row['data2']
            ora2 = row['ora2']

            formated_date = datetime.datetime.strptime(data1 + " " + ora1, "%Y-%m-%d %H:%M:%S")
            dt1 = datetime.datetime.timestamp(formated_date)

            formated_date = datetime.datetime.strptime(data2 + " " + ora2, "%Y-%m-%d %H:%M:%S")
            dt2 = datetime.datetime.timestamp(formated_date)

            time2 = time()

            if dt1 < time2 < dt2:
                valid = True
                
                break

        if valid == True:
            GPIO.output(GREEN_LED, 1)

            GPIO.output(RED_LED, 0)

            GPIO.output(Pin_deschis, 0)
            
            servo_open()

            servo_close()

        else:
            GPIO.output(RED_LED, 1)

            GPIO.output(GREEN_LED, 0)

            if sound_done == 0:
                Thread(target = play_rsound).start()

                sound_done = 1

        record_time()

Thread(target = nfc_loop).start()

def play_asound():
    n = randint(1, 2)

    names = {
        1: "raducanu1.mp3",
        2: "raducanu2.mp3",
        3: "raducanu3.mp3"
    }

    playsound(names[n])

    global sound_done

    sound_done = 0

def play_rsound():
    n = randint(1, 4)

    names = {
        1: "morariu1.mp3",
        2: "morariu2.mp3",
        3: "morariu3.mp3",
        4: "morariu4.mp3"
    }

    playsound(names[n])

    global sound_done

    sound_done = 0