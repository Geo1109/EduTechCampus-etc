from mfrc522 import SimpleMFRC522

from threading import Thread

reader = SimpleMFRC522()

string = input("Type what string do you want to write: ")

def nfc_loop():
    while True:
        id, text = reader.read()

        reader.write(string)

        id, text = reader.read()

        if string == text.replace(" ", ""):
            print("The string was succesfully uploaded.")

            break

        else:
            print("The string was not sucessfully uploaded.")

Thread(target = nfc_loop).start()