
'''
Dependiendo del sistema se requeriran estas dependecias
Windows:
    Nada

Mac oc:
    pip install pyobjc-core
    pip install pyobjc

linux:
    pip install python3-xlib

Instalando libreria:
    pip install pyautogui
'''
#Importando libreria
import pyautogui as move

#Clase para ser llamada en el programa principal
class Control():

    def Subir():
        move.moveRel(-10, 0, 0.3)

    def Bajar():
        move.moveRel(10, 0, 0.3)

    def Derecha():
        move.moveRel(0, 10, 0.3)

    def Izquierda():
        move.moveRel(0, -10, 0.3)

    def Click(boton):
        x, y = move.position()
        if(boton == 1):
            move.click(x, y, 1, 0.4)
        
        elif(boton == 2):
            move.click(x, y, 1, 0.4, 'right')
        
        print(f"x: {x} || y: {y}")

    def Scroll(opcion):
        #Opcion para bajar
        if(opcion == 1):
            move.scroll(100, 200, 120)
        
        elif (opcion == 2):
            move.scroll(-100, 200, 120)

    