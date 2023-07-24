import network
import time
import socket
import machine
import ure as re


def calculate_water_needs(weight, gender, activity_level):
    if ( gender == 'male' ):
        result = 37 * weight
    else:
        result = 27 * weight
        
    if activity_level == 1:
        result = 0.85 * result
    elif activity_level == 2:
        result = 0.9 * result
    elif activity_level == 3:
        result = 1 * result
    elif activity_level == 4:
        result = 1.1 * result
    else:
        result = 1.15 * result
    return result
    
    


# class to make wifi connectivity easier
class Router:
    def __init__(router, ssid, password):
        router.ssid = ssid
        router.password = password
    
# define most used connections
connection_katty = Router("DIGI_40f280", "a7eebf0d")
connection_Hotspostkatty = Router("katty", "87654321")
connection_Hotspotroli = Router("Olah's Galaxy A72", "xevr4131")
connection_OxyGenie = Router("OxyGenie", "bagiparolasidaienter")
#connection_OxyGenie = Router("OxyGenie", "admin1234")
connection_roli = Router("TP-Link_24E8", "22923929")
connection_ieg = Router("Tenda_128F", "50463883")
#bagiparolasidaienter


def connectToWifi():
    wlan = network.WLAN(network.STA_IF)
    wlan.active(True)
    # try to connect to a wifi address using ssid and password
    wlan.connect(connection_OxyGenie.ssid, connection_OxyGenie.password)
     
    # Wait for connect or fail
    wait = 60
    while wait > 0:
        print(f'status of wifi is:{wlan.status()}')
        if wlan.status() < 0 or wlan.status() >= 3:
            print("we have a good status. Breaking while loop")
            break
        wait -= 1
        print('waiting for connection...')
        time.sleep(1)
    print(f'exited while loop with wifi status: {wlan.status()}')
    ip = wlan.ifconfig()[0]
    # Handle connection error
    if wlan.status() != 3:
        return False
    else:
        print('connected')
        #print('IP: ', ip)
        return ip

def open_socket(ip):
    # Open a socket
    address = (ip, 80)
    connection = socket.socket()
    
    connection.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    
    connection.bind(address)
    connection.listen(1)
    return connection
    

def webpage(result):
    #Template HTML
    html = f"""
            <!DOCTYPE html>
<html>
<head>
    <title>Calculator necesitati hidratare</title>
</head>
<body>
    <h1>Calculator necesitati hidratare</h1>
    <form id="water-needs-form" action = "./values">
        
        <label for="weight">Greutate (in kg):</label>
        <input type="number" id="weight" name="weight" required><br>

        <label for="gender">Gen:</label>
        <select id="gender" name="gender" required>
            <option value="male">Masculin</option>
            <option value="female">Feminin</option>
        </select><br>

        <label for="activity-level">Nivel de Activitate:</label>
        <select id="activity-level" name="activity-level" required>
            <option value="1">1 - Inactiv</option>
            <option value="2">2 - Activitate scazuta</option>
            <option value="3">3 - Activitate moderata</option>
            <option value="4">4 - Activ</option>
            <option value="5">5 - Foarte activ</option>
        </select><br>

        <button type="submit">Calculeaza Necesarul de Apa</button>
    </form>
    
    <p>Actualizati pagina pentru a afisa rezultatul:</p>
    <p>Necesarul zilnic de apa este: { result } ml.</p>

</body>
</html>


            """
    return str(html)

def serve(connection):
    #Start a web server
    i=3
    result = 0
    while True:
        client = connection.accept()[0]
        request = client.recv(1024)
        request = str(request)
        print(request)
        print("valoare medie apa              ", result)
        html = webpage(result)
        client.send(html)
        
        match = re.search("weight=(\d+)&gender=(\w+)&activity-level=(\d+)", request)
        if match:
            weight = int(match.group(1))
            gender = match.group(2)
            activity_level = int(match.group(3))
            print("valori",weight,gender,activity_level)
        
            result = calculate_water_needs(weight,gender,activity_level)
            print(result)
            html = webpage(result)
            i = i-1
            print("i========",i)
            
        #weight = request.form['weight']
        #gender = request.value['gender']
        #activity_level = request.value['activity-level']
        
        

        client.close()
        if i == 0:
            return result
        
        


#ip = connectToWifi()
#print(ip)
#connection = open_socket(ip)
#serve(connection)

    


    

