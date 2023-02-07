# Programa que clasifica imagenes segun su extencion y las clasifica en carpetas
#Creador JP27

#Librerias utilizadas
import os
import shutil



#Funcion para enlistado de archivos y busqueda de existencia de carpetas requeridas
def Busqueda_carpeta():
    #creando arbol de archivos en la carpeta para su clasificación
    files = os.listdir()
    #Bandera de busqueda de coincidencia con carpetas a crear
    Band1 = False
    Band2 = False

    #Ciclo para buscar existencia de carpetas a crear
    for file in files:
        if 'Raw_Photos' == file:
            Band1 = True
        
        if 'JPG_Photos' == file:
            Band2 = True

    #Creacion de carpetas
    if Band1 == True and Band2 == True:
        print("Las carpetas Raw_Photos o JPG_Photos ya existen en esta dirección, Desea combinar los elementos de adentro con los nuevos?(Y/n)")
        respuesta = str(input("->"))
        if respuesta == "Y" or respuesta == "y":
            Mover(files)
        else:
            print('Si necesita este proceso de recomienda renombrar las carpetas antes mencionadas o moverlas a otra carpeta.')
    else:
        os.mkdir('Raw_Photos')
        os.mkdir('JPG_Photos')
        Mover(files)




#Funcion para mover los archivos a sus carpetas requeridas.
def Mover(files):
    
    #Clasificando archivos segun extensión
    for file in files:
        if '.CR2' in file:
            shutil.move(file,'Raw_Photos')
            
        if '.JPG' in file:
            shutil.move(file,'JPG_Photos')
        


#Arranque de programa
Busqueda_carpeta()




