#Archivo donde se programaran las diferentes funciones necesarias para el funcionamiento.

#Librerias necesarias:
import random
import datetime
import re
import serial 
"""
Inicio = [(7, 'Parque Central de Santiago', '8.097376, -80.983128', 2), 
          (8, 'Parque La Amistad', '8.095923, -80.982879', 2), 
          (9, 'Parada frente al Hotel Piramidal', '8.100513, -80.966507', 2),
          (10, 'Terminal de transporte de Santiago', '8.104835, -80.973554', 1), 
          (11, 'Parque La Alameda', '8.094559, -80.975553', 2)]
"""
#Funcion para leer el serial de la esp32 y obtener los datos de los sensores de las antenas
def LecturaSerial():
    #Iniciando variables para leer
    esp32 = serial.Serial("/dev/ttyUSB0", 115200)
    #Leyendo datos
    lectura = esp32.readline().decode()
    #Extrayendo datos de la cadena recibida
    a, b, c, d = re.findall(r"[\w\.\-]+", lectura)
    x = float(d)
    #Transformando a porcentaje
    if x < 90 and x > 5:
        porcentaje =  (-1.176*(x-90))
    elif x < 15:
        porcentaje = 100
    else:
        porcentaje = 0
    #Creando variable de retorno con los datos
    nivel = [int(c), int(porcentaje)]
    return nivel

#Clases con funciones para el programa principal   
class Funciones:

    def Gen_Key():
        caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
        Numeros = []
        for i in range(25):
            Numeros.append(random.randint(0, 61))
        key = []
        for num in Numeros:
            key.append(caracteres[num])
        return "".join(key)
    
    def Fecha():
        return datetime.datetime.now()
    
    #Funciones para dividir los datos segun la necesidad
    def seccionar_datos(coordenadas):
        #Declarando arreglos
        estatus = []
        localizacion = []
        datos = []

        #Dividiendo datos para registros
        for id, ubicacion, coordenada, cont in coordenadas:
            #cortando el string de las coordenadas
            x, y = re.findall(r"[\w\.\-]+", coordenada)
            #Armando arreglo con las localizaciones 
            localizacion.append([ubicacion, float(x), float(y)])
            
            #Armando arreglo para el estatus
            datos.append([id, ubicacion, float(x), float(y), cont])

            #Armando arreglo de los datos  
            i = 0
            while i < cont:
                estatus.append([id, 0]) 
                i += 1
        return localizacion, estatus, datos
    
    #Funcion para actualizar los datos los basureros
    def actualizar_niveles(basureros, datos):
        print(datos)
        tamano = len(datos)
        id_final = datos[tamano-1][0]
        for registro in basureros:
            if registro[0] != id_final:
                registro[1] = random.randint(0, 100)
            else:
                a, b =LecturaSerial()
                registro[1] = b
        
        return basureros
             

        
        



    
