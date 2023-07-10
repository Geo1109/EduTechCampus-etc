from mfrc522 import SimpleMFRC522

from threading import Thread

reader = SimpleMFRC522()

def nfc_loop():
    while True:
        id, text = reader.read()

        print(id, text)

Thread(target = nfc_loop).start()